<?php
include ("header.php");
if(!isset($_SESSION['FOOD_USER_ID'])){
	redirect(FRONT_SITE_PATH.'shop.php');
}
$getUserDetails=getUserDetailsByid();
?>

<div class="myaccount-area pb-80 pt-100">
            <div class="container">
                <div class="row">
				
                    <div class="ml-auto mr-auto col-lg-9">
						<div>
							<h4>Referral Code: <?php echo $getUserDetails['referral_code']?></h4><br/>
							<h4>Referral Link: <?php echo FRONT_SITE_PATH?>login_register?referral_code=<?php echo $getUserDetails['referral_code']?></h4><br/>
							
						</div>
                        <div class="checkout-wrapper">
                            <div id="faq" class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>1</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-1">Edit your account information </a></h5>
                                    </div>
                                    <div id="my-account-1" class="panel-collapse collapse show">
                                        <div class="panel-body">
                                            <form method="post" id="frmProfile">
												<div class="billing-information-wrapper">
													<div class="account-info-wrapper">
														<h4>My Account Information</h4>
														<h5>Your Personal Details</h5>
													</div>
													<div class="row">
														<div class="col-lg-6 col-md-6">
															<div class="billing-info">
																<label>Name</label>
																<input type="text" id="uname" name="name" required value="<?php echo $getUserDetails['name']?>">
															</div>
														</div>
														<div class="col-lg-6 col-md-6">
															<div class="billing-info">
																<label>Mobile Number</label>
																<input type="text" name="mobile" value="<?php echo $getUserDetails['mobile']?>" required>
															</div>
														</div>
														<div class="col-lg-12 col-md-12">
															<div class="billing-info">
																<label>Email Address</label>
																<input type="email" readonly="readonly" value="<?php echo $getUserDetails['email']?>">
															</div>
														</div>
														
													</div>
													<div class="billing-back-btn">
														<div class="billing-back">
															<a href="#"><i class="ion-arrow-up-c"></i> back</a>
														</div>
														<div class="billing-btn">
															<button type="submit" id="profile_submit">Save</button>
														</div>
														
													</div>
													<div id="form_msg"></div>
												</div>
												<input type="hidden" name="type" value="profile">
											</form>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>2</span> <a data-toggle="collapse" data-parent="#faq" href="#my-account-2">Change your password </a></h5>
                                    </div>
                                    <div id="my-account-2" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <form method="post" id="frmPassword">
												<div class="billing-information-wrapper">
													<div class="account-info-wrapper">
														<h4>Change Password</h4>
														<h5>Your Password</h5>
													</div>
													<div class="row">
														<div class="col-lg-12 col-md-12">
															<div class="billing-info">
																<label>Password</label>
																<input type="password" name="old_password" required>
															</div>
														</div>
														<div class="col-lg-12 col-md-12">
															<div class="billing-info">
																<label>Password Confirm</label>
																<input type="password" name="new_password" required>
															</div>
														</div>
													</div>
													<div class="billing-back-btn">
														<div class="billing-back">
															<a href="#"><i class="ion-arrow-up-c"></i> back</a>
														</div>
														<div class="billing-btn">
															<button type="submit" id="password_submit">Save</button>
														</div>
													</div>
													<input type="hidden" name="type" value="password">
													<div id="password_form_msg"></div>
												</div>
												
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