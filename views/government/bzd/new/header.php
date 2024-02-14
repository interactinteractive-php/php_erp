<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/favicon.png">

    <title><?php echo $this->title ?></title>
    <base href="<?php echo URL; ?>">

    <link href="assets/core/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?php echo autoVersion('assets/custom/css/main.css'); ?>" rel="stylesheet"/>
    <link href="<?php echo autoVersion('assets/core/css/custom-helper.css'); ?>" rel="stylesheet"/>
    <link href="assets/core/icon/fontawesome/styles.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/core/icon/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="assets/custom/attend/style.css" rel="stylesheet" type="text/css">
    <?php $configSkinTheme = Config::getFromCacheDefault('erp_skin', null, 'blue'); ?>
    <link href="<?php echo autoVersion('assets/custom/css/theme-color/'.$configSkinTheme.'.css'); ?>" rel="stylesheet"/>
    
    <?php require 'views/header/globaljsvars.php';  ?>
    <script type="text/javascript">
    var URL_FN = URL,
        URL = "<?php echo URL; ?>",
        URL_APP = '<?php echo URL; ?>',
        max = -219.99078369140625,
        isreload = false,
        ENVIRONMENT = "<?php echo ENVIRONMENT; ?>",
        isCloseOnEscape = <?php echo Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE') ? 'true' : 'false'; ?>,
        pnotifyPosition = '<?php echo Config::getFromCache('CONFIG_PNOTIFY_POSITION'); ?>';
        ;
    </script>

    <script src="assets/core/js/main/jquery.min.js" type="text/javascript"></script>
    <script src="assets/core/js/main/jquery-migrate-3.0.0.min.js" type="text/javascript"></script>
    <script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
    <script src="assets/custom/js/core.js" type="text/javascript"></script>
    
    <script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>
    
    <script src="<?php echo autoVersion('middleware/assets/js/mdmetadata.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo autoVersion('middleware/assets/js/mdbp.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo autoVersion('middleware/assets/js/mdexpression.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo autoVersion('middleware/assets/js/mddv.js'); ?>" type="text/javascript"></script>  
    <script src="assets/custom/js/mixitup.min.js" type="text/javascript"></script>  
    <script src="<?php echo autoVersion('assets/core/js/plugins/socket/ws.js'); ?>" type="text/javascript"></script>
</head>
<body class="bg-light">
    <div class="header">
        <div class="topbar">
            <div class="container-fluid">
                <div class="row align-items-end justify-content-around p-2">
                    <div class="col-auto navbar-brand">
                        <a href="javascript:;" class="d-inline-block">
                            <img src="assets/custom/attend/logo.gif" alt="logo" onerror="onUserImgError(this);" class="logo">
                        </a>
                    </div>
                    <div class="col-6">
                        <h2><?php echo issetParam($this->data['name']); ?></h2>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <div class="">
                            <button class="btn btn-light rounded-pill mr-3 text-primary" style="padding: 10px 20px; font-size: 16px;" onclick="totalProtocolEnd<?php echo $this->uniqId ?>(this, '<?php echo $this->id ?>', '<?php echo $this->mapid ?>', '<?php echo $this->subjectId ?>')"><?php echo Lang::lineCode('finish_btn', $this->langCode) ?></button>
                        </div>
                        <div class="ymd mr-4">
                            <span class="day mr-1"><i class="fa fa-calendar-o"></i></span>
                            <span class="day" id="day"><?php echo issetParam($this->currentDate); ?></span>
                        </div>
                        <div class="clockminute">
                            <span class="time mr-1"><i class="fa fa-clock-o"></i></span>
                            <span class="time mr-0" id="digital-clock" style="margin-right: 0"><?php echo issetParam($this->currentTime); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid p-3 pt-5">
        <div class="bg-white" style="border-radius:10px">


        