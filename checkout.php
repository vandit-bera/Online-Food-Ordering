<?php
include ("header.php");
if($website_close==1){
	redirect(FRONT_SITE_PATH.'shop.php');
}
$cartArr=getUserFullCart();
if(count($cartArr)>0){
	
	
}else{
	redirect(FRONT_SITE_PATH.'shop.php');
}

if(isset($_SESSION['FOOD_USER_ID'])){
	$is_show='';
	$box_id='';
	$final_show='show';
	$final_box_id='payment-2';
}else{
	$is_show='show';
	$box_id='payment-1';
	$final_show='';
	$final_box_id='';
}
$userArr=getUserDetailsByid();
$is_error='';
if(isset($_POST['place_order'])){
	//prx($_POST);
	if($cart_min_price!=''){
		if($totalPrice>=$cart_min_price){
			
		}else{
			$is_error='yes';
		}
		
	}
	if($is_error==''){
		$checkout_name=get_safe_value($_POST['checkout_name']);
		$checkout_email=get_safe_value($_POST['checkout_email']);
		$checkout_mobile=get_safe_value($_POST['checkout_mobile']);
		$checkout_zip=get_safe_value($_POST['checkout_zip']);
		$checkout_address=get_safe_value($_POST['checkout_address']);
		$payment_type=get_safe_value($_POST['payment_type']);
		
		if(isset($_SESSION['COUPON_CODE']) && isset($_SESSION['FINAL_PRICE'])){
			$coupon_code=get_safe_value($_SESSION['COUPON_CODE']);
			$final_price=get_safe_value($_SESSION['FINAL_PRICE']);
		}else{
			$coupon_code='';
			$final_price=$totalPrice;
		}
		
		$added_on=date('Y-m-d h:i:s');
		$sql="insert into order_master(user_id,name,email,mobile,address,zipcode,total_price,order_status,payment_status,added_on,coupon_code,final_price,payment_type) values('".$_SESSION['FOOD_USER_ID']."','$checkout_name','$checkout_email','$checkout_mobile','$checkout_address','$checkout_zip','$totalPrice','1','pending','$added_on','$coupon_code','$final_price','$payment_type')";
		mysqli_query($con,$sql);
		$insert_id=mysqli_insert_id($con);
		$_SESSION['ORDER_ID']=$insert_id;
		foreach($cartArr as $key=>$val){
			mysqli_query($con,"insert into order_detail(order_id,dish_details_id,price,qty) values('$insert_id','$key','".$val['price']."','".$val['qty']."')");
		}
		emptyCart();
		$getUserDetailsBy=getUserDetailsByid();
		$email=$getUserDetailsBy['email'];
		if($payment_type=='cod'){
			$emailHTML=orderEmail($insert_id);
			// echo $email;
			// $emailHTML=FRONT_SITE_PATH."verify/";
			// include('smtp/PHPMailerAutoload.php');
			send_email($email,$emailHTML,'Order Placed');
			// redirect(FRONT_SITE_PATH.'success.php');
		}
		
		if($payment_type=='wallet'){
			manageWallet($_SESSION['FOOD_USER_ID'],$final_price,'out','Order Id-'.$insert_id);
			mysqli_query($con,"update  order_master set payment_status='success' where id='$insert_id'");
			$emailHTML=orderEmail($insert_id);
			include('smtp/PHPMailerAutoload.php');
			send_email($email,$emailHTML,'Order Placed');
			redirect(FRONT_SITE_PATH.'success.php');
		}
		
		if($payment_type=='paytm'){
			$paytm_oid=$insert_id.'_'.$_SESSION['FOOD_USER_ID'];
			$html='<form method="post" action="pgRedirect.php" name="frmPayment" style="display:none;">
					<input id="ORDER_ID" tabindex="1" maxlength="20" size="20"
								name="ORDER_ID" autocomplete="off"
								value="'.$paytm_oid.'">
							<input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="'.$_SESSION['FOOD_USER_ID'].'"><input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail"><input id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB"><input title="TXN_AMOUNT" tabindex="10"
								type="text" name="TXN_AMOUNT"
								value="'.$final_price.'"><input value="CheckOut" type="submit"	onclick=""></td></form><script type="text/javascript">document.frmPayment.submit();
				
			</script>';
			echo $html;
		}
	}
	
}
?>

<div class="checkout-area pb-80 pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="checkout-wrapper">
                            <div id="faq" class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>1.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-1">Checkout method</a></h5>
                                    </div>
                                    <div id="<?php echo $box_id?>" class="panel-collapse collapse <?php echo $is_show?>">
                                        <div class="panel-body">
                                            <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    <div class="checkout-login">
                                                        <div class="title-wrap">
                                                            <h4 class="cart-bottom-title section-bg-white">LOGIN</h4>
                                                        </div>
                                                        <p>&nbsp;</p>
                                                        <form method="post" id="frmLogin">
                                                            <div class="login-form">
                                                                <label>Email Address * </label>
                                                                <input name="user_email" placeholder="Email" required>
                                                            </div>
                                                            <div class="login-form">
                                                                <label>Password *</label>
                                                                <input type="password" name="user_password" placeholder="Password" required>
																<input type="hidden" name="type" value="login"/>
																<input type="hidden" name="is_checkout" value="yes" id="is_checkout"/>
                                                            </div>
                                                        
                                                         <div class="checkout-login-btn">
                                                            <button type="submit" id="login_submit" class="my_btn">Login</button>
															<a href="<?php echo FRONT_SITE_PATH?>login_register.php" style="background-color: #e02c2b;color:#fff;">Register Now</a>
                                                        </div>
														<div id="form_login_msg"></div>
														</form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>2.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-2">Other information</a></h5>
                                    </div>
                                    <div id="<?php echo $final_box_id?>" class="panel-collapse collapse <?php echo $final_show?>">
                                        <div class="panel-body">
											<form method="post">
												<div class="billing-information-wrapper">
													<div class="row">
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>First Name</label>
																<input type="text" name="checkout_name" required value="<?php echo $userArr['name']?>">
															</div>
														</div>
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>Email Address</label>
																<input type="email"  name="checkout_email" required value="<?php echo $userArr['email']?>">
															</div>
														</div>
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>Mobile</label>
																<input type="text"  name="checkout_mobile" required value="<?php echo $userArr['mobile']?>">
															</div>
														</div>
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>Zip/Postal Code</label>
																<input type="text"  name="checkout_zip" required>
															</div>
														</div>
														<div class="col-lg-12 col-md-12">
															<div class="billing-info">
																<label>Address</label>
																<input type="text"  name="checkout_address" required>
															</div>
														</div>
														<div class="col-lg-3 col-md-12">
															<div class="billing-info">
																<label>Coupon Code</label>
																<input type="text"  name="coupon_code" id="coupon_code" >
															</div>
															<div id="coupon_code_msg"></div>
														</div>
														<div class="col-lg-5 col-md-12">
															<div class="billing-back-btn">
																<div class="billing-btn">
																	<button type="button" name="place_order" onclick="apply_coupon()" >Apply Coupon</button>
																</div>
															</div>
														</div>
													</div>
													
													<div class="ship-wrapper">
														<div class="single-ship">
															<input type="radio" name="payment_type" value="cod">
															<label>Cash on Delivery(COD)</label>
														</div>
														<div class="single-ship">
															<input type="radio" name="payment_type" value="paytm"   checked="checked">
															<label>PayTm</label>
														</div>
														<?php
														$is_dis='';
														$low_msg='';
														if($getWalletAmt>=$totalPrice){
															
														}else{
															$is_dis="disabled='disabled'";
															$low_msg="(Low Wallet Money)";
														}
														?>
														<div class="single-ship">
															<input type="radio" name="payment_type" value="wallet" <?php echo $is_dis?>>
															<label>Wallet</label>
															<span style="color:red;font-size:12px;">
															<?php
															echo $low_msg;
															?>
															</span>
														</div>
														
														<!--<div class="single-ship">
															<input type="radio" name="address" value="dadress">
															<label>Ship to different address</label>
														</div>-->
													</div>
													<div class="billing-back-btn">
														<div class="billing-btn">
															<button type="submit" name="place_order">Place Your Order</button>
														</div>
														
													</div>
													<?php
													if($is_error=='yes'){
														echo "<div style='color:red;'>$cart_min_price_msg</div>";
													}
													?>
												</div>
											</form>
                                        </div>
                                    </div>
                                </div>
                                
						   </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="checkout-progress">
                            <div class="shopping-cart-content-box">
								<h4 class="checkout_title">Cart Details</h4>
								<ul>
									<?php foreach($cartArr as $key=>$list){ ?>
									<li class="single-shopping-cart">
										<div class="shopping-cart-img">
											<a href="#"><img alt="" src="<?php echo SITE_DISH_IMAGE.$list['image']?>"></a>
										</div>
										<div class="shopping-cart-title">
											<h4><a href="#">Phantom Remote</a></h4>
											<h6>Qty: <?php echo $list['qty']?></h6>
											<span><?php echo 
														$list['qty']*$list['price'];?> Rs</span>
										</div>
										
									</li>
									<?php } ?>
								</ul>
								<div class="shopping-cart-total">
									<h4>Total : <span class="shop-total"><?php echo $totalPrice?> Rs</span></h4>
								</div>
								
								<div class="shopping-cart-total coupon_price_box">
									<h4>Coupon Code : <span class="shop-total coupon_code_str"></span></h4>
								</div>
								<div class="shopping-cart-total coupon_price_box">
									<h4>Final Price : <span class="shop-total final_price"></span></h4>
								</div>
								
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

<?php
if(isset($_SESSION['COUPON_CODE'])){
		unset($_SESSION['COUPON_CODE']);
		unset($_SESSION['FINAL_PRICE']);
}
include("footer.php");
?>