<?php 
include('top.php');

if(isset($_POST['submit'])){
	$cart_min_price=get_safe_value($_POST['cart_min_price']);
	$cart_min_price_msg=get_safe_value($_POST['cart_min_price_msg']);
	$website_close=get_safe_value($_POST['website_close']);
	$website_close_msg=get_safe_value($_POST['website_close_msg']);
	$wallet_amt=get_safe_value($_POST['wallet_amt']);
	$referral_amt=get_safe_value($_POST['referral_amt']);
	mysqli_query($con,"update setting set cart_min_price='$cart_min_price', cart_min_price_msg='$cart_min_price_msg', website_close='$website_close', website_close_msg='$website_close_msg',wallet_amt='$wallet_amt',referral_amt='$referral_amt' where id='1'");
}

$row=mysqli_fetch_assoc(mysqli_query($con,"select * from setting where id='1'"));
$cart_min_price=$row['cart_min_price'];
$cart_min_price_msg=$row['cart_min_price_msg'];
$website_close=$row['website_close'];
$website_close_msg=$row['website_close_msg'];
$wallet_amt=$row['wallet_amt'];
$referral_amt=$row['referral_amt'];
$websiteCloseArr=array('No','Yes');
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Setting</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Cart min price</label>
                      <input type="text" class="form-control" placeholder="Cart min price" name="cart_min_price" required value="<?php echo $cart_min_price?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3" required>Order Number</label>
                      <input type="textbox" class="form-control" placeholder="Cart min price msg" name="cart_min_price_msg"  value="<?php echo $cart_min_price_msg?>">
                    </div>
                    
					<div class="form-group">
                      <label for="exampleInputEmail3" required>Website Close</label>
                      <select name="website_close" class="form-control">
						<option value="">Select Option</option>
						<?php foreach($websiteCloseArr as $key=>$val){
							if($website_close==$key){
								echo "<option value='$key' selected='selected'>$val</option>";
							}else{
								echo "<option value='$key'>$val</option>";
							}
						} ?>	
					  <select>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail3" required>Website Close Msg</label>
                      <input type="textbox" class="form-control" placeholder="Website close msg" name="website_close_msg"  value="<?php echo $website_close_msg?>">
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail3" required>Wallet Amt</label>
                      <input type="textbox" class="form-control" placeholder="Website close msg" name="wallet_amt"  value="<?php echo $wallet_amt?>">
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail3" required>Referral Amt</label>
                      <input type="textbox" class="form-control" placeholder="Website close msg" name="referral_amt"  value="<?php echo $referral_amt?>">
                    </div>
					
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
            
		 </div>
        
<?php include('footer.php');?>