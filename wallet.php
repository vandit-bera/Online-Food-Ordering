<?php
include ("header.php");
if(!isset($_SESSION['FOOD_USER_ID'])){
	redirect(FRONT_SITE_PATH.'shop.php');
}

$err_msg='';
if(isset($_POST['add_money'])){
	$amt=get_safe_value($_POST['amt']);
	if($amt>0){
		$_SESSION['IS_WALLET']='yes';
		$html='<form method="post" action="pgRedirect.php" name="frmPayment" style="display:none;">
					<input id="ORDER_ID" tabindex="1" maxlength="20" size="20"
								name="ORDER_ID" autocomplete="off"
								value="ORDS'.rand(10000,99999999).'">
							<input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="'.$_SESSION['FOOD_USER_ID'].'"><input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail"><input id="CHANNEL_ID" tabindex="4" maxlength="12" size="12" name="CHANNEL_ID" autocomplete="off" value="WEB"><input title="TXN_AMOUNT" tabindex="10"
								type="text" name="TXN_AMOUNT"
								value="'.$amt.'"><input value="CheckOut" type="submit"	onclick=""></td></form><script type="text/javascript">document.frmPayment.submit();
				
			</script>';
			echo $html;
	}else{
		$err_msg="Please enter valid amount";
	}
}
?>

<div class="cart-main-area pt-50 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
						<div id="add_money_box">
							<form method="post" id="frmAddMoney">
								<input type="number" placeholder="Add Money" name="amt" class="w150" required/>
								<input type="submit" class="btn w150" name="add_money"/>
								<div id="error_msg"><?php echo $err_msg?></div>
							</form>
						</div>
							<?php
							$getWallet=getWallet($_SESSION['FOOD_USER_ID']);
							?>
                            <div class="table-content table-responsive">
								
                                <table>
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Amt</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php 
									   $kk=1;
									   foreach($getWallet as $list){?>
											<tr class="wallet_loop">
												<td><?php echo $kk?></td>
												<td><?php echo $list['amt']?></td>
												<td>
													<span class="<?php echo $list['type']?>">
														<?php echo $list['msg']?>
													</span>
												</td>
												<td><?php echo $list['added_on']?></td>
											</tr>
									   <?php 
									   $kk++;
									   } ?>
                                    </tbody>
                                </table>
								
                            </div>
                            
                        
                    </div>
                </div>
            </div>
        </div>

<?php
include("footer.php");
?>