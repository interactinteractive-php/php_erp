<?php 
$colSplit = '12';
if (isset($this->getDataview)) { ?>
    <style type="text/css">
        #load-news-dataview-<?php echo $this->uniqId; ?> {
            margin-top: 35px;
            padding-left: 20px;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .table.table-light > tbody > tr > td {
            border-bottom-color: #f2f5f840;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .viewer-container .card.light .card-header {
            border: none;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .explorer-table-cell {
            background-color: #ffffff5c !important;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .object-height-row2-minus-<?php echo $this->getDataview ?> {
            background-color: #ffffff5c !important;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .meta-toolbar {
            background-color: #ffffff5c !important;
            background: none;
            min-height: 26px;
            /*height: 39px;*/
            padding: 6px 0 6px 0;
            margin-left: -10px;
            margin-right: -10px;
            margin-bottom: 0;
            border-bottom-color: #f2f5f840;
        }
        #load-news-dataview-<?php echo $this->uniqId; ?> .meta-toolbar span {
            color: #333;
            padding-left: 6px;
        }
        div.vr-card-submenu {
            padding: 0 !important;
            margin: 30px -20px 20px 0px;
        }
    </style>
<?php
    $colSplit = '9';
    echo '<div class="col-md-3" id="load-news-dataview-' . $this->uniqId . '"></div>';
} ?>
<div class="col-md-<?php echo $colSplit ?>">
    <?php
    if ($this->menuList['status'] == 'success' && isset($this->menuList['menuData'])) {
    ?>
    <div class="vr-card-menu vr-card-submenu animated zoomIn">
        <?php
        $metaDataId = isset($this->metaDataId) ? $this->metaDataId : '';
        foreach ($this->menuList['menuData'] as $row) {
            
            $linkHref = 'javascript:;';
            $linkTarget = '_self';
            $linkOnClick = '';
            $backLinkTarget = '';
            if ($row['isshowcard'] == 'true') {
                $linkHref = 'appmenu/sub/'.$row['code'];
                $linkTarget = '_self';
                $linkOnClick = '';
            } elseif (!empty($row['weburl'])) {
                if(strtolower(substr($row['weburl'], 0, 4)) == 'http') {
                    $linkHref = $row['weburl'];
                }else{
                    $linkHref = $row['weburl'].'&mmid='.$row['metadataid'];                
                }
                $linkTarget = $row['urltrg'];
                $linkOnClick = '';
            } elseif (empty($row['weburl']) && empty($row['actionmetadataid'])) {
                $linkHref = 'appmenu/module/'.$row['metadataid'].'?mmid='.$row['metadataid'];
                $linkTarget = '_self';
                $linkOnClick = '';
            } else {
                if ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
                    $linkHref = 'appmenu/module/'.$row['metadataid'].'/'.$row['actionmetadataid'].'?mmid='.$row['metadataid'];
                    $linkTarget = '_self';
                    $linkOnClick = '';
                } else {
                    $linkMeta = Mdmeta::menuServiceAnchor($row, $row['metadataid'], $row['metadataid'], $this->isTab, $this->uniqId, $metaDataId);
                    $linkHref = $linkMeta['linkHref'];
                    $backLinkTarget = $linkMeta['backLinkTarget'];
                    $linkTarget = $linkMeta['linkTarget'];
                    $linkOnClick = $linkMeta['linkOnClick'];
                }
            }
            if ($row['metadataid'] == '1484275008377720') {
                echo Form::create(array('id' => 'etoken-form', 'class' => 'etoken-form', 'method' => 'post', 'action' => 'login/runEToken', 'autocomplete' => 'off', 'style' => 'display: none;')); 
                echo Form::text(array('name' => 'seasonId', 'id' => 'seasonId', 'class' => 'form-control placeholder-no-fix', 'required' => 'required', 'autocomplete' => 'off')); 
                echo Form::text(array('name' => 'redirecturl', 'class' => 'form-control placeholder-no-fix', 'required' => 'required', 'autocomplete' => 'off', 'value' => $linkMeta['linkHref'])); 
                echo Form::close();   
                
                $linkHref = 'javascript:;';
                $linkTarget = '_self';
                $linkOnClick = 'ShowTokenLoginWin();';
            }
            
            $imgSrc = 'assets/custom/addon/admin/layout4/img/entrustment_05.png';
            if ($row['photoname'] != '' && file_exists($row['photoname'])) {
                $imgSrc = $row['photoname'];
            }
        ?>
        <a href="<?php echo $linkHref; ?>" target="<?php echo $linkTarget; ?>" onclick="<?php echo $linkOnClick; ?>" back-target-metadataid="<?php echo $backLinkTarget; ?>" class="vr-submenu-tile animated" data-metadataid="<?php echo $row['metadataid']; ?>" data-pfgotometa="1">
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <img class="dv-directory-img" src="<?php echo $imgSrc; ?>" data-default-image="assets/custom/addon/admin/layout4/img/entrustment_05.png" onerror="onDataViewImgError(this);">
                </div>
            </div>
            <div class="second-title">
                <h4>
                    <?php echo $this->lang->line($row['name']); ?>
                </h4>
            </div>
        </a> 
        <?php
        }
        ?>
        <div class="clearfix"></div>
    </div>
    <?php
    } else {
        echo html_tag('div', array('class' => 'alert alert-danger'), $this->menuList['message']);
    }
    ?>
</div>
    
<?php if (isset($this->getDataview)) { ?>
    <script type="text/javascript">    
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/<?php echo $this->getDataview; ?>/0/json',
            data: {
            },
            dataType: 'json',
            success: function (data) {
                $('#load-news-dataview-<?php echo $this->uniqId; ?>').append(data.Html);
            }
        });     
    </script>
<?php } ?>

<script type="text/javascript">    
    <?php if (isset($this->getStartupMeta['ACTION_META_DATA_ID']) && $this->getStartupMeta['ACTION_META_DATA_ID'] && Session::get(SESSION_PREFIX.'startupMeta') !== '1') { ?>
        var fillDataParams = '';
    //    if (id) {
    //        fillDataParams = 'id='+id+'&defaultGetPf=1';
    //    } else {
    //    }
        fillDataParams = 'userKeyId=<?php echo Ue::sessionUserKeyId() ?>';    
        var actionMetaDataId = '<?php echo $this->getStartupMeta['ACTION_META_DATA_ID'] ?>';
            startupmeta(actionMetaDataId, fillDataParams, '0');    
    <?php } ?>

    <?php if (!isset($this->getStartupMeta['ACTION_META_DATA_ID']) && isset($this->getStartupMeta2['ACTION_META_DATA_ID']) && $this->getStartupMeta2['ACTION_META_DATA_ID'] && Session::get(SESSION_PREFIX.'startupMeta') !== '1') { ?>
        var fillDataParams = '';
    //    if (id) {
    //        fillDataParams = 'id='+id+'&defaultGetPf=1';
    //    } else {
    //    }
        fillDataParams = 'userKeyId=<?php echo Ue::sessionUserKeyId() ?>';    
        var actionMetaDataId = '<?php echo $this->getStartupMeta2['ACTION_META_DATA_ID'] ?>';
            startupmeta(actionMetaDataId, fillDataParams, '1');    
    <?php } ?>    
            
    function startupmeta(actionMetaDataId, fillDataParams, isAllUser) {
        var $dialogName = 'dialog-hrm-startup-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);        
        var actionMetaTypeId;
        
        $.ajax({
            type: 'get',
            url: 'mdmetadata/getMetaTypeById/'+actionMetaDataId,
            async: false,
            dataType: 'json',
            beforeSend: function () {
            },
            success: function (data) {
                actionMetaTypeId = data;
            },
            error: function () {
                alert("Error");
            }
        });             
        
        if (actionMetaTypeId == '200101010000016') {
            $.ajax({
                type: 'post',
                url: 'mdobject/dataview/' + actionMetaDataId,
                data: {},
                beforeSend: function() {
                    Core.blockUI({animate: true});
                },
                success: function(data) {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';

                    $dialog.empty().append(data);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [{text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}]
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });
                    if (data.dialogSize === 'fullscreen') {
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog('open');                    
                    Core.unblockUI();
                },
                error: function() {
                    alert('Error');
                    Core.unblockUI();
                }
            });
        } else if (actionMetaTypeId == '200101010000011') {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: actionMetaDataId, 
                    isDialog: true, 
                    isSystemMeta: false, 
                    fillDataParams: fillDataParams
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName), 
                        processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                                $processForm.validate({ 
                                    ignore: '', 
                                    highlight: function(element) {
                                        $(element).addClass('error');
                                        $(element).parent().addClass('error');
                                        if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                                var tabId = $(tab).attr('id');
                                                $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(element) {
                                        $(element).removeClass('error');
                                        $(element).parent().removeClass('error');
                                    },
                                    errorPlacement: function(){} 
                                });

                                var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                if ($processForm.valid() && isValidPattern.length === 0) {
                                    $processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({
                                                boxed: true, 
                                                message: 'Түр хүлээнэ үү'
                                            });
                                        },
                                        success: function (responseData) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: responseData.status,
                                                text: responseData.message,
                                                type: responseData.status, 
                                                sticker: false
                                            });

                                            if (responseData.status === 'success') {
                                                $dialog.dialog('close');
                                                if (isAllUser == '0') {
                                                    <?php if (isset($this->getStartupMeta2['ACTION_META_DATA_ID']) && $this->getStartupMeta2['ACTION_META_DATA_ID']) { ?>
                                                        var fillDataParams = '';
                                                        fillDataParams = 'userKeyId=<?php echo Ue::sessionUserKeyId() ?>';    
                                                        var actionMetaDataId = '<?php echo $this->getStartupMeta2['ACTION_META_DATA_ID'] ?>';
                                                            startupmeta(actionMetaDataId, fillDataParams, '1');                                                                        
                                                    <?php } ?>
                                                }                                                
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                            }    
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm bp-btn-close', click: function () {
                            $dialog.dialog('close');
                            if (isAllUser == '0') {
                                <?php if (isset($this->getStartupMeta2['ACTION_META_DATA_ID']) && $this->getStartupMeta2['ACTION_META_DATA_ID']) { ?>
                                    var fillDataParams = '';
                                    fillDataParams = 'userKeyId=<?php echo Ue::sessionUserKeyId() ?>';    
                                    var actionMetaDataId = '<?php echo $this->getStartupMeta2['ACTION_META_DATA_ID'] ?>';
                                        startupmeta(actionMetaDataId, fillDataParams, '1');                                                                        
                                <?php } ?>
                            }                             
                        }}
                    ];

                    var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });
                    if (data.dialogSize === 'fullscreen') {
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog('open');
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
                Core.unblockUI();
            });        
        }        
    }
</script>            