<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Appmenu_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getMenuListModel($menuCode = null, $isDuplicateByTag = false) {
        
        $cache = phpFastCache();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $isDuplicateByTag = (int) $isDuplicateByTag;
        $menuCacheName = 'appmenu_' . $sessionUserKeyId.(is_null($menuCode) ? '' : '_'.$menuCode).'_'.$isDuplicateByTag;
        
        $data = $cache->get($menuCacheName);

        if ($data == null) {
            
            $param = array(
                'userId'       => $sessionUserKeyId,
                'menuCode'     => is_null($menuCode) ? 'ERP_MENU' : $menuCode, 
                'checkLicense' => 0
            );
            
            if ($isDuplicateByTag) {
                $param['isDuplicateByTag'] = true;
            }

            $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'one_level_menu', $param);
            
            if ($data['status'] == 'success') {
                
                if (isset($data['result']) && is_array($data['result'])) {
                    
                    $result = $data['result'];
                    $count = count($result);

                    if ($count < 11) {
                        foreach ($result as $k => $row) {
                            if ($row['code'] == 'ERP_MENU_MOBILE') {
                                unset($result[$k]);
                                $count--;
                                break;
                            }
                        }
                        $result = array_values($result);
                    }

                    $data = array(
                        'status'           => 'success', 
                        'menuData'         => $result, 
                        'count'            => $count, 
                        'menuId'           => $result[0]['metadataid'],
                        'actionmetadataid' => issetParam($result[0]['actionmetadataid']),
                        'actionmetatypeid' => issetParam($result[0]['actionmetatypeid']), 
                        'weburl'           => issetParam($result[0]['weburl'])
                    );

                    $cache->set($menuCacheName, $data, 43200);

                    return $data;
                    
                } else {
                    return array('status' => 'error', 'message' => 'Эрхийн тохиргоо хийгдээгүй тул та систем хариуцсан ажилтанд хандана уу!');
                }
                
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
            }
            
        } else {
            return $data;
        }
    }
    
    public function getChildMenuListModel($menuMetaDataId) {
        $param = array(
            'userId' => Ue::sessionUserKeyId(),
            'menuId' => $menuMetaDataId
        );
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'child_menus', $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            return array('status' => 'success', 'menuData' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function startupMeta() {
        $get = $this->db->GetRow("SELECT * FROM UM_META_BLOCK WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
        return $get;
    }    
    
    public function startupMeta2() {
        $get = $this->db->GetRow("SELECT * FROM UM_META_BLOCK WHERE IS_ALL_USER = ".$this->db->Param(0), array(1));
        return $get;
    }    
    
    public function startupUser() {
        $get = $this->db->GetRow("SELECT * FROM UM_USER WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
        return $get;
    }

    public function getMetaDataModel($metaDataId) {
        
        $metaDataIdPh = $this->db->Param('metaDataId');

        $bindVars = array(
            'metaDataId' => $this->db->addQ($metaDataId)
        );
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                MD.DESCRIPTION, 
                MD.ADDON_XML_DATA, 
                MD.COPY_COUNT 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
            WHERE MD.META_DATA_ID = $metaDataIdPh", $bindVars);

        return $row;
    }    
    
    public function getResetPasswordUser() {
        
        $day = Config::getFromCache('ChangePasswordDate');
        $userHash = Config::getFromCache('userPasswordHash');
        
        if ($day || $userHash) {
            
            $userId = Ue::sessionUserId();
            
            if ($userHash) {
                
                $get = $this->db->GetRow("
                    SELECT 
                        USER_ID, 
                        USERNAME, 
                        null AS PASSWORD_RESET_DATE 
                    FROM UM_SYSTEM_USER 
                    WHERE USER_ID = ".$this->db->Param(0)." 
                        AND PASSWORD_HASH = ".$this->db->Param(1), 
                array($userId, $userHash));
                
                if ($get) {
                    return $get;
                }
            }
            
            if ($day) {
                
                $get = $this->db->GetRow("
                    SELECT 
                        USER_ID, 
                        USERNAME, 
                        PASSWORD_RESET_DATE 
                    FROM UM_SYSTEM_USER 
                    WHERE USER_ID = ".$this->db->Param(0)." 
                        AND (PASSWORD_RESET_DATE IS NULL OR (PASSWORD_RESET_DATE + ".$this->db->Param(1).") < SYSDATE)", 
                array($userId, $day));     
                
                if ($get) {
                    return $get;
                }
            }
        }
        
        return false;
    }    

}