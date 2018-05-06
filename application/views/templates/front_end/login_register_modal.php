<div class="modal fade login" id="loginModal">
  <div class="modal-dialog login animated">
      <div class="modal-content">
         <div class="modal-header" style="padding-bottom: 0;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Login with</h4>
        </div>
        <div class="modal-body no-padding" style="padding-top: 0;">
            <div class="box">
                 <div class="content">
                    <!-- <div class="social">
                        <a class="circle github" href="#">
                            <i class="fa fa-github fa-fw"></i>
                        </a>
                        <a id="google_login" class="circle google" href="javascript:void(0)">
                            <i class="fa fa-google-plus fa-fw"></i>
                        </a>
                        <a id="facebook_login" class="circle facebook" href="javascript:void(0)">
                            <i class="fa fa-facebook fa-fw"></i>
                        </a>
                    </div>
                    <div class="division">
                        <div class="line l"></div>
                          <span>or</span>
                        <div class="line r"></div>
                    </div>
                     -->
                    <div>
                        <img class="full-width img img-responsive" src="<?= FRONT_END_ASSETS ?>images/logo.png" alt="logo">
                    </div>
                    <br>
                    <div class="error"></div>
                    <div class="form loginBox">
                        <form method="POST" action="<?= base_url('front_end/login/login_lookup'); ?>" accept-charset="UTF-8" id="login-form">
                            <input id="login_email" class="form-control" type="text" placeholder="Email or Mobile Number" name="login_email">
                            <div id="login_email_error"></div>
                            
                            <input id="login_password" class="form-control" type="password" placeholder="Password" name="login_password">
                            <div id="login_password_error"></div>
                            
                            <input class="btn btn-default btn-login" type="submit" id="login-btn" value="Login">
                        </form>
                    </div>
                 </div>
            </div>
            <div class="box">
                <div class="content registerBox" style="display:none;">
                    <div class="form">
                        <form html="{:multipart=>true}" data-remote="true" action="<?= base_url('front_end/user/add_user_lookup'); ?>" method="POST" accept-charset="UTF-8"
                        id="registeration-form">
                        
                            <input class="form-control" type="text" placeholder="First Name" id="first_name" name="first_name">
                            <div id="first_name_error"></div>
                            
                            <input class="form-control" type="text" placeholder="Last Name" id="last_name" name="last_name">
                            <div id="last_name_error"></div>
                            
                            <input class="form-control" type="text" placeholder="Mobile Number" id="mobile_number" name="mobile_number">
                            <div id="mobile_number_error"></div>
                            
                            <input id="email" class="form-control" type="text" placeholder="Email" name="email">
                            <div id="email_error"></div>
                            
                            <input id="password" class="form-control" type="password" placeholder="Password" name="password">
                            <div id="password_error"></div>
                            
                            <input id="password_confirmation" class="form-control" type="password" placeholder="Repeat Password" name="password_confirmation">
                            <div id="password_confirmation_error"></div>
                            
                            <input class="btn btn-default btn-register" type="submit" value="Create account" id="register_user" name="register_user">
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="forgot login-footer">
                <span id="create_account">Looking to
                     <a href="javascript:void(0);">create an account</a>
                ?</span>

            </div>
            <div class="forgot register-footer" style="display:none">
                <span id="already_account">Already have an account?
                    <a href="javascript:void(0);">Login</a>
                </span>
            </div>
        </div>
      </div>
  </div>
</div>