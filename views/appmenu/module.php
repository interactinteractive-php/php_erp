<?php echo $this->contentHtml; ?>

<script type="text/javascript">
$(function(){
    
    <?php if (isset($this->getStartupMeta['ACTION_META_DATA_ID']) && $this->getStartupMeta['ACTION_META_DATA_ID'] && Session::get(SESSION_PREFIX.'startupMeta') !== '1') { ?>
        var fillDataParams = '';
        fillDataParams = 'userKeyId=<?php echo Ue::sessionUserKeyId() ?>';    
        var actionMetaDataId = '<?php echo $this->getStartupMeta['ACTION_META_DATA_ID'] ?>';
            startupmeta(actionMetaDataId, fillDataParams, '0');    
    <?php } ?>
    
    <?php if (!isset($this->getStartupMeta['ACTION_META_DATA_ID']) && isset($this->getStartupMeta2['ACTION_META_DATA_ID']) && $this->getStartupMeta2['ACTION_META_DATA_ID'] && Session::get(SESSION_PREFIX.'startupMeta') !== '1') { ?>
        var fillDataParams = '';
        fillDataParams = 'userKeyId=<?php echo Ue::sessionUserKeyId() ?>';    
        var actionMetaDataId = '<?php echo $this->getStartupMeta2['ACTION_META_DATA_ID'] ?>';
            startupmeta(actionMetaDataId, fillDataParams, '1');    
    <?php } ?>    

    <?php if ($this->getResetUser) { ?>
        var $dialogName = 'dialog-user-startup-resetpassword';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        var showMessage = '<?php echo $this->getResetUser['PASSWORD_RESET_DATE'] ? Lang::lineVar('UM_0001', array('day' => Config::getFromCache('ChangePasswordDate'))) : Lang::line('UM_0002'); ?>'

        $.ajax({
            type: 'post',
            url: 'profile/changePasswordForm',
            dataType: 'json',
            data: {no_nowpassword: '1', showMessage: showMessage},
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    minWidth: 500,
                    height: 'auto',
                    modal: true,
                    closeOnEscape: false, 
                    open: function () {
                        $dialog.parent().find('.ui-dialog-titlebar-close').remove();
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            "class": 'btn btn-sm green-meadow',
                            click: function() {
                                $.validator.addMethod(
                                    "regex",
                                    function(value, element, regexp) {
                                        if (regexp.constructor != RegExp) {
                                            regexp = new RegExp(regexp);
                                        } else if (regexp.global) {
                                            regexp.lastIndex = 0;
                                        }
                                        return this.optional(element) || regexp.test(value);
                                    },
                                    'Хамгийн багадаа 8 тэмдэгт, том жижиг үсэг, тоо болон тусгай тэмдэгт оролцсон байх'
                                );

                                $("#form-change-password").validate({
                                    rules: {
                                        currentPassword: {
                                            required: true
                                        },
                                        newPassword: {
                                            required: true,
                                            minlength: 8,
                                            regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})'
                                        },
                                        confirmPassword: {
                                            required: true,
                                            minlength: 8,
                                            equalTo: "#newPassword",
                                            regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})'
                                        }
                                    },
                                    messages: {
                                        currentPassword: {
                                            required: plang.get('user_insert_password')
                                        },
                                        newPassword: {
                                            required: plang.get('user_insert_password'),
                                            minlength: plang.get('user_minlenght_password')
                                        },
                                        confirmPassword: {
                                            required: plang.get('user_insert_password'),
                                            minlength: plang.get('user_minlenght_password'),
                                            equalTo: plang.get('user_equal_password')
                                        }
                                    }
                                });

                                if ($("#form-change-password").valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'profile/changePassword',
                                        data: $("#form-change-password").serialize()+'&resetPassword=1',
                                        dataType: "json",
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(data) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });

                                            if (data.status === 'success') {
                                                $dialog.dialog("close");
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert("Error");
                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        });        
    <?php } ?>    
});

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