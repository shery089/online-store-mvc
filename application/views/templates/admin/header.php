<?php  
    if(!$this->session->userdata['logged_in'])
    {
        redirect('/admin/login/');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $layout_title ?></title>

    <!-- CSS includes -->
    
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>bower_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Bootstrap Select -->
    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>bootstrap-select/dist/css/bootstrap-select.css">
        
    <!-- Custom CSS -->
    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>dist/css/sb-admin-2.css">
        
    <!-- Custom Fonts -->
    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>jquery-ui/jquery-ui.min.css">
    
    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>jquery-ui/jquery-ui.theme.min.css">

    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>metisMenu/metisMenu.min.css">

    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>pick-a-color/css/pick-a-color-1.2.3.min.css">

    <link rel="stylesheet" href= "<?= ADMIN_ASSETS ?>dropzone/dropzone.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</script>

</head>

<body>
    
    <div id="wrapper">
    
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= site_url('dashboard') ?>">Easy Shop Admin v1.0</a> <!--  - Appointment Management System -->
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="active"><a href="<?= base_url('admin/product') ?>">Product</a></li>
                <li><a href="<?= base_url('admin/user') ?>">User</a></li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <?= isset($this->session->userdata['admin_record']) ? array_column($this->session->userdata['admin_record'], 'user_name')[0] : '' ?>
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?= isset($this->session->userdata['admin_record']) ? base_url('admin/user/edit_user_lookup') . '/' . array_column($this->session->userdata['admin_record'], 'id')[0] : '' ?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="<?= base_url('admin/user/change_password_lookup') ?>"><i class="fa fa-lock fa-fw"></i> Change Password</a>
                        </li>
                        <li class="divider"></li>
                         <li><a href="<?= site_url('admin/login/logout_lookup'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        <!-- </li> -->
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->