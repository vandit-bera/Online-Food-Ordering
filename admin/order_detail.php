<?php 
include('top.php');
if(isset($_GET['id']) && $_GET['id']>0){
	
	$id=get_safe_value($_GET['id']);
	
	if(isset($_GET['order_status'])){
		$order_status=get_safe_value($_GET['order_status']);
		
		if($order_status==5){
			$cancel_at=date('Y-m-d h:i:s');
			$sql="update order_master set order_status='$order_status',cancel_by='admin',cancel_at='$cancel_at' where id='$id'";		
		}else{
			$sql="update order_master set order_status='$order_status' where id='$id'";
		}
		mysqli_query($con,$sql);
		$getSetting=getSetting();
		$referral_amt=$getSetting['referral_amt'];
		if($referral_amt>0){
			if($order_status==4){
				$getOrderById=getOrderById($id);
				
				$user_id=$getOrderById['0']['user_id'];
				$row=mysqli_fetch_assoc(mysqli_query($con,"select count(*) as total_order from order_master where user_id='$user_id' and order_status=4"));
				$total_order=$row['total_order'];
				if($total_order==1){
					
					$res=mysqli_query($con,"select from_referral_code,email from user where id='$user_id'");
					if(mysqli_num_rows($res)>0){
						$row=mysqli_fetch_assoc($res);
						$email=$row['email'];
						$from_referral_code=$row['from_referral_code'];
						$row=mysqli_fetch_assoc(mysqli_query($con,"select id from user where referral_code='$from_referral_code'"));
						$uid=$row['id'];
						$msg1='Referral Amt from '.$email;
						manageWallet($uid,$referral_amt,'in',$msg1);
					}
					
				}
				
			}
		}
		
		
		
		
		redirect(FRONT_SITE_PATH.'admin/order_detail.php?id='.$id);
	}
	
	if(isset($_GET['delivery_boy'])){
		$delivery_boy=get_safe_value($_GET['delivery_boy']);
		mysqli_query($con,"update order_master set delivery_boy_id='$delivery_boy' where id='$id'");
		redirect(FRONT_SITE_PATH.'admin/order_detail.php?id='.$id);
	}
	
	$sql="select order_master.*,order_status.order_status as order_status_str from order_master,order_status where order_master.order_status=order_status.id and order_master.id='$id' order by order_master.id desc";
	$res=mysqli_query($con,$sql);
	if(mysqli_num_rows($res)>0){
		$orderRow=mysqli_fetch_assoc($res);
	}else{
		redirect('index.php');
	}
}else{
	redirect('index.php');
}
?>
  <div class="page-header">
              <h3 class="page-title"> Invoice </h3>
              
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="card px-2">
                  <div class="card-body">
                    <div class="container-fluid">
                      <h3 class="text-right my-5">Order ID&nbsp;&nbsp;<?php echo $id?></h3>
                      <hr>
                    </div>
                    <div class="container-fluid d-flex justify-content-between">
                      <div class="col-lg-3 pl-0">
                        <p class="mt-5 mb-2"><b>Shop Name</b></p>
                        <p>Shop Address</p>
                      </div>
                      <div class="col-lg-3 pr-0">
                        <p class="mt-5 mb-2 text-right"><b>Invoice to</b></p>
                        <p class="text-right">
							<?php  echo $orderRow['name']?><br/>
							<?php  echo $orderRow['address']?><br/>
							<?php  echo $orderRow['zipcode']?><br/>
							Delhi
						</p>
                      </div>
                    </div>
                    <div class="container-fluid d-flex justify-content-between">
                      <div class="col-lg-3 pl-0">
                        <p class="mb-0 mt-5">Order Date : <?php  echo dateFormat($orderRow['added_on'])?></p>
                      </div>
                    </div>
                    <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                      <div class="table-responsive w-100">
                        <table class="table">
                          <thead>
                            <tr class="bg-dark">
                              <th>#</th>
                              <th>Description</th>
                              <th class="text-right">Quantity</th>
                              <th class="text-right">Unit cost</th>
                              <th class="text-right">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
							$getOrderDetails=getOrderDetails($id);
							$pp=0;
							$i=1;
							foreach($getOrderDetails as $list){
							$pp=$pp+($list['price']*$list['qty']);	
							?>
                            
                            <tr class="text-right">
                              <td class="text-left"><?php echo $i?></td>
                              <td class="text-left"><?php echo $list['dish']?>(<?php echo $list['attribute']?>)</td>
                              <td><?php echo $list['qty']?></td>
                              <td><?php echo $list['price']?></td>
                              <td><?php echo $list['price']*$list['qty']?></td>
                            </tr>
							<?php 
							$i++;
							} ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="container-fluid mt-5 w-100">
                      <h4 class="text-right mb-5">Total : <?php echo $pp?></h4>
					  <?php
						if($orderRow['coupon_code']!=''){
						?>
						  <h4 class="text-right mb-5">Coupon Code : <?php echo $orderRow['coupon_code']?></h4>
						  <h4 class="text-right mb-5">Final Total : <?php echo $orderRow['final_price']?></h4>
						<?php
						}
						?>
                      <hr>
                    </div>
					
					
                    <div class="container-fluid w-100">
                      <a href="../download_invoice.php?id=<?php echo $id?>" class="btn btn-primary float-right mt-4 ml-2"><i class="mdi mdi-printer mr-1"></i>PDF</a>
                    </div>
					<?php
					$orderStatusRes=mysqli_query($con,"select * from order_status order by order_status");
					
					$orderDeliveryBoyRes=mysqli_query($con,"select * from delivery_boy where status=1 order by name");
					
					?>
					<div>
						<?php
							echo "<h4>Order Status:- ".$orderRow['order_status_str']."</h4>";
						?>
						<select class="form-control wSelect200" name="order_status" id="order_status" onchange="updateOrderStatus()">
							<option val=''>Update Order Status</option>
							<?php 
							while($orderStatusRow=mysqli_fetch_assoc($orderStatusRes)){
								echo "<option value=".$orderStatusRow['id'].">".$orderStatusRow['order_status']."</option>";
							}
							?>
						</select>
						<br/>
						<?php
							echo "<h4>Delivery Boy:- ".getDeliveryBoyNameById($orderRow['delivery_boy_id'])."</h4>";
						?>
						<select class="form-control wSelect200" name="delivery_boy" id="delivery_boy" onchange="updateDeliveryBoy()">
							<option val=''>Assign Delivery Boy</option>
							<?php 
							while($orderDeliveryBoyRow=mysqli_fetch_assoc($orderDeliveryBoyRes)){
								echo "<option value=".$orderDeliveryBoyRow['id'].">".$orderDeliveryBoyRow['name']."</option>";
							}
							?>
						</select>
					</div>
					
                  </div>
				  
                </div>
              </div>     
<script>
function updateOrderStatus(){
	var order_status=jQuery('#order_status').val();
	if(order_status!=''){
		var oid="<?php echo $id?>";
		window.location.href='<?php echo FRONT_SITE_PATH?>admin/order_detail.php?id='+oid+'&order_status='+order_status;
	}
}

function updateDeliveryBoy(){
	var delivery_boy=jQuery('#delivery_boy').val();
	if(delivery_boy!=''){
		var oid="<?php echo $id?>";
		window.location.href='<?php echo FRONT_SITE_PATH?>admin/order_detail.php?id='+oid+'&delivery_boy='+delivery_boy;
	}
}


</script>			  
<?php include('footer.php');?>