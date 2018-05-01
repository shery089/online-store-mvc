<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title><?= $layout_title ?></title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="<?= FRONT_END_ASSETS ?>css/bootstrap.min.css" rel="stylesheet">
        <!-- <link href="<?= FRONT_END_ASSETS ?>css/bootstrap-theme.min.css" rel="stylesheet"> -->
        
        <link href="<?= FRONT_END_ASSETS ?>css/styles.css" rel="stylesheet">             
       
        <!-- Custom Fonts -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

        <!-- login-register -->
        <link href="<?= FRONT_END_ASSETS ?>css/login-register.css" rel="stylesheet" />
        
        <link href="<?= FRONT_END_ASSETS ?>css/bootstrap-social.css" rel="stylesheet" />
 
        <!-- animate.css -->
        
        <link href="<?= FRONT_END_ASSETS ?>css/Fr.star.css" rel="stylesheet" />
        
        <!-- bootstrap-select -->
        <link href="<?= ADMIN_ASSETS ?>bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />

        <!-- jQuery UI -->
        <link href="<?= ADMIN_ASSETS ?>jquery-ui/jquery-ui.min.css" rel="stylesheet" />

        <!-- Font Awesome Fonts -->
        <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>bower_components/font-awesome/css/font-awesome.min.css">
        
    </head>
    <body>
<div class="container">
<div id="loading" style="display:none;">
    <img src="<?= ADMIN_IMAGES_PATH . 'ajax_clock_small.gif' ?>" alt="">
</div>
<div id="large_loading" class="col-lg-12" style="display:none;">
    <div class="col-lg-4" style="margin: 20px 0;"></div>
    <img class="col-lg-4 img img-responsive" src="<?= ADMIN_IMAGES_PATH . 'loading.gif' ?>" alt="">
    <div class="col-lg-4"></div>
    <br>
</div>