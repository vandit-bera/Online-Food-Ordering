<?php
include ("header.php");
if(isset($_GET['referral_code']) && $_GET['referral_code']!=''){
	$_SESSION['FROM_REFERRAL_CODE']=get_safe_value($_GET['referral_code']);
}
?>
<div class="login-register-area pt-95 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                        <div class="login-register-wrapper">
                            <div class="login-register-tab-list nav">
                                <a class="active" data-toggle="tab" href="#lg1">
                                    <h4> login </h4>
                                </a>
                                <a data-toggle="tab" href="#lg2">
                                    <h4> register </h4>
                                </a>
                            </div>
                            <div class="tab-content">
                                <div id="lg1" class="tab-pane active">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form method="post" id="frmLogin">
                                                <input type="email" name="user_email" placeholder="Email" required>
                                                <input type="password" name="user_password" placeholder="Password" required>
                                                <div class="button-box">
                                                    <div class="login-toggle-btn">
                                                        <a href="<?php echo FRONT_SITE_PATH?>forgot_password.php">Forgot Password?</a>
                                                    </div>
                                                    <button type="submit" id="login_submit">Login</button>
													<input type="hidden" name="type" value="login"/>
													<input type="hidden" name="is_checkout" id="is_checkout" value=""/>
												   <div id="form_login_msg" class="success_field"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="lg2" class="tab-pane">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <form method="post" id="frmRegister">
                                                <input type="text" name="name" placeholder="Name" id="name" required>
												<input name="email" id="email" placeholder="Email" type="email" required>
												<div id="email_error" class="error_field"></div>
                                                <input type="password" name="password" placeholder="Password" id="password" required>
                                                <input type="text" name="mobile" placeholder="Mobile" id="mobile" required>
                                                <div class="button-box">
                                                    <button type="submit" id="register_submit">Register</button>
                                                </div>
												<input type="hidden" name="type" value="register"/>
												<div id="form_msg" class="success_field"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<?php
include("footer.php");
?>