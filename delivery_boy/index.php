<?php
session_start();
include('../database.inc.php');
include('../function.inc.php');

if(!isset($_SESSION['DELIVERY_BOY_USER_LOGIN'])){
	redirect('login.php');
}

if(isset($_GET['set_order_id'])){
	$set_order_id=get_safe_value($_GET['set_order_id']);
	$delivered_on=date('Y-m-d h:i:s');
   mysqli_query($con,"update order_master set order_status=4,delivered_on='$delivered_on',payment_status='success' where id='$set_order_id' and delivery_boy_id='".$_SESSION['DELIVERY_BOY_ID']."'");
   
	
}

$sql="select order_master.*,order_status.order_status as order_status_str from order_master,order_status where order_master.order_status=order_status.id and order_master.delivery_boy_id='".$_SESSION['DELIVERY_BOY_ID']."' and order_master.order_status!=4 order by order_master.id desc";
$res=mysqli_query($con,$sql);
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Order Master</title>
      <link rel="stylesheet" href="../admin/assets/css/dataTables.bootstrap4.css">
      <link rel="stylesheet" href="../admin/assets/css/style.css">
   </head>
   <body class="sidebar-light">
      <div class="container-scroller">
         <!-- partial:partials/_navbar.html -->
         <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between">
               <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                  <a class="navbar-brand brand-logo" href="index.php"><img src="../admin/assets/images/logo.png" alt="logo"/></a>
                  <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../admin/assets/images/logo.png" alt="logo"/></a>
               </div>
               <ul class="navbar-nav navbar-nav-right">
                  <li class="nav-item nav-toggler-item-right d-lg-none">
                     <a href="logout.php">Logout</a>
                  </li>
               </ul>
            </div>
         </nav>
         <!-- partial -->
         <div class="container-fluid page-body-wrapper">
            <div class="main-panel" style="width:100%;">
               <div class="content-wrapper">
                  <div class="card">
                     <div class="card-body">
                        <h1 class="grid_title">Order Master</h1>
                        <div class="row grid_box">
                           <div class="col-12">
                              <div class="table-responsive">
                                 <table id="order-listing" class="table">
                                    <thead>
                                       <tr>
                                          <th width="5%">Order Id</th>
                                          <th width="20%">Name/Mobile</th>
                                          <th width="20%">Address/Zipcode</th>
                                          <th width="5%">Price</th>
										  <th width="10%">Payment Type</th>
                                          <th width="10%">Payment Status</th>
                                          <th width="10%">Order Status</th>
                                          <th width="15%">Added On</th>
                                       </tr>
                                    </thead>
                                    <tbody>
									   <?php if(mysqli_num_rows($res)>0){
										$i=1;
										while($row=mysqli_fetch_assoc($res)){
										?>	
                                       <tr>
                                          <td>
                                             <div class="div_order_id">
                                                <?php echo $row['id']?>
                                             </div>
                                          </td>
                                          <td>
                                            <p><?php echo $row['name']?></p>
											<p><?php echo $row['mobile']?></p>
                                          <td>
                                            <p><?php echo $row['address']?></p>
											<p><?php echo $row['zipcode']?></p>
                                          </td>
                                         <td style="font-size:14px;"><?php echo $row['final_price']?>
											
										
										</td>
										<td><?php echo $row['payment_type']?></td>
                                          <td>
											<div class="payment_status payment_status_<?php echo $row['payment_status']?>"><?php echo ucfirst($row['payment_status'])?></div>
										</td>
                                          <td><a href="?set_order_id=<?php echo $row['id']?>">Set Delivered</a></td>
											<td>
											<?php 
											$dateStr=strtotime($row['added_on']);
											echo date('d-m-Y h:s',$dateStr);
											?>
											</td>
							
                                       </tr>
                                       <?php 
										$i++;
										} } else { ?>
										<tr>
											<td colspan="6">No data found</td>
										</tr>
										<?php } ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- content-wrapper ends -->
               <!-- partial:partials/_footer.html -->
               <footer class="footer">
                  <div class="d-sm-flex justify-content-center justify-content-sm-between">
                     <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 <a href="#">Online Food Ordering</a>. All rights reserved.</span>
                  </div>
               </footer>
               <!-- partial -->
            </div>
            <!-- main-panel ends -->
         </div>
         <!-- page-body-wrapper ends -->
      </div>
      <!-- container-scroller -->
      <!-- plugins:js -->
      <script src="../admin/assets/js/jquery.dataTables.js"></script>
      <script src="../admin/assets/js/dataTables.bootstrap4.js"></script>
      <!-- End custom js for this page-->
   </body>
</html>