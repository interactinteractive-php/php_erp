<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Appmarket_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
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
    
    public function getDataviewResultModel($dvId, $limit = 15) {

        $param = array(
            'systemMetaGroupId' => $dvId,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'paging' => array (
                'offset' => 1,
                'pageSize' => $limit
            )            
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'];
        } else {
            return null;
        }
    }    

}