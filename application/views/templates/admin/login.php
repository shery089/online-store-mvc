<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= ADMIN_ASSETS; ?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= ADMIN_ASSETS; ?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= ADMIN_ASSETS; ?>dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= ADMIN_ASSETS; ?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                    <?php 
                    
                        if($this->session->flashdata('error_message'))
                        {
                        ?>
                        <p class="alert alert-danger alert-dismissable fade in text-center top-height">
                            <?= $this->session->flashdata('error_message');  ?>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </p>
                        <?php
                        }
                    ?>
                        <?= form_open('admin/login/login_lookup','role="form" id="login_form" novalidate'); ?>
                        <form>
                            <?= form_fieldset(); ?>
                                <div class="form-group">
                                    <?php 
                                        $data = array(
                                            'name'          => 'email',
                                            'id'            => 'email',
                                            'placeholder'   => 'E-mail or Phone Number',
                                            'class'         => 'form-control',
                                            'value'         => set_value('email'),
                                            'autofocus'     => 'autofocus'
                                        );
                                    ?>
                            
                                        <?= form_input($data); ?>   
                                    <div class="error_prefix text-right" id="email_error"></div>
                                </div>
                                <div class="form-group">
                                    <?php 
                                        $data = array(
                                            'name'          => 'password',
                                            'id'            => 'password',
                                            'placeholder'   => 'Password',
                                            'class'         => 'form-control'
                                        );
                                    ?>

                                        <?= form_password($data); ?>

                                    <div class="error_prefix text-right" id="password_error"></div>
                                </div><!-- 
                                <div class="checkbox">
                                    <?php 
                                        $data = array(
                                            'name'          => 'remember_me',
                                            'id'            => 'remember_me'
                                        );
                                    ?>
                                    <? form_label(form_checkbox($data) . 'Remember me') ?>
                                
                                </div> -->
                                    <?php 
                                        $data = array(
                                            'name'          => 'login',
                                            'class'          => 'btn btn-lg btn-success btn-block',
                                            'value'          => 'Login',
                                        );
                                    ?>

                                    <?= form_submit($data); ?>
                            <?= form_fieldset_close(); ?>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= ADMIN_ASSETS; ?>bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?= ADMIN_ASSETS; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?= ADMIN_ASSETS; ?>bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?= ADMIN_ASSETS; ?>dist/js/sb-admin-2.js"></script>
    
</body>

</html>
