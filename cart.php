<?php
include ("header.php");
if($website_close==1){
	redirect(FRONT_SITE_PATH.'shop.php');
}
?>

<div class="cart-main-area pt-95 pb-100">
            <div class="container">
                <h3 class="page-title">Your cart items</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form method="post">
							<?php
							$cartArr=getUserFullCart();
							if(count($cartArr)>0){
							?>
                            <div class="table-content table-responsive">
								
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product Name</th>
                                            <th>Until Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										foreach($cartArr as $key=>$list){
										?>
											<tr>
												<td class="product-thumbnail">
													<a href="#"><img src="<?php echo SITE_DISH_IMAGE.$list['image']?>" alt="" width="75px"></a>
												</td>
												<td class="product-name"><a href="#"><?php echo $list['dish']?> </a></td>
												<td class="product-price-cart"><span class="amount"><?php echo $list['price']?> Rs</span></td>
												<td class="product-quantity">
													<div class="cart-plus-minus">
														<input class="cart-plus-minus-box" type="text" name="qty[<?php echo $key?>][]" value="<?php echo $list['qty']?>">
													</div>
												</td>
												<td class="product-subtotal"><?php echo $list['qty']*$list['price']?> Rs</td>
												<td class="product-remove">
													<a href="javascript:void(0)" onclick="delete_cart('<?php echo $key?>','load')"><i class="fa fa-times"></i></a>
											   </td>
											</tr>
										<?php } ?>
                                    </tbody>
                                </table>
								
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                            <a href="<?php echo FRONT_SITE_PATH?>shop.php">Continue Shopping</a>
                                        </div>
                                        <div class="cart-clear">
                                            <button name="update_cart">Update Shopping Cart</button>
                                            <a href="<?php echo FRONT_SITE_PATH?>checkout.php">Checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<?php } else {
								echo "Empty Cart";
							}?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
include("footer.php");
?>