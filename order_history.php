<?php
include ("header.php");
if(!isset($_SESSION['FOOD_USER_ID'])){
	redirect(FRONT_SITE_PATH.'shop.php');
}
$uid=$_SESSION['FOOD_USER_ID'];

if(isset($_GET['cancel_id'])){
	$cancel_id=get_safe_value($_GET['cancel_id']);
	$cancel_at=date('Y-m-d h:i:s');
	mysqli_query($con,"update order_master set order_status='5',cancel_by='user',cancel_at='$cancel_at' where id='$cancel_id' and order_status='1' and user_id='$uid'");
}

$sql="select order_master.*,order_status.order_status as order_status_str from order_master,order_status where order_master.order_status=order_status.id and order_master.user_id='$uid' order by order_master.id desc";

$res=mysqli_query($con,$sql);
?>

<div class="cart-main-area pt-95 pb-100">
            <div class="container">
                <h3 class="page-title">Order History</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form method="post">
							<div class="table-content table-responsive">
								
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Order No</th>
                                            <th>Price</th>
											<th>Coupon</th>
                                            <th>Address</th>
											<th>Zipcode</th>
                                            <th>Order Status</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php if(mysqli_num_rows($res)>0){
										$i=1;
										while($row=mysqli_fetch_assoc($res)){
										?>
										<tr>
                                            <td>
												<div class="div_order_id"><a href="<?php echo FRONT_SITE_PATH.'order_detail?id='.$row['id']?>"><?php echo $row['id']?></div></a>
											
											<br/>
											<a href="<?php echo FRONT_SITE_PATH?>download_invoice?id=<?php echo $row['id']?>"><img src='<?php echo FRONT_SITE_PATH?>assets/img/icon-img/pdf.png' width="20px" title="Download Invoice"/></a>
											</td>
                                            <td style="font-size:14px;">
											<?php echo $row['total_price']?></td>
                                            <td>
											<?php
											if($row['coupon_code']!=''){
											?>
											Coupon Code:- <?php echo $row['coupon_code']?><br/>
											Final Price:- <?php echo $row['final_price']?>
											<?php } ?>
											</td>
											<td><?php echo $row['address']?></td>
											<td><?php echo $row['zipcode']?></td>
											<td>
											<?php 
											echo $row['order_status_str'];
											
											if($row['order_status']==1){
												echo "<br/>";
												echo "<div style='margin-top:10px;'><a href='?cancel_id=".$row['id']."' class='cancel_btn'>Cancel</a></div>";
											}
											?>
											</td>
											<td>
												<div class="payment_status payment_status_<?php echo $row['payment_status']?>"><?php echo ucfirst($row['payment_status'])?></div>
											</td>
                                        </tr>
										<?php }} ?>
                                    </tbody>
                                </table>
								
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
include("footer.php");
?>