<?php
include ("header.php");

$msg="";
//Email id verify
if(isset($_GET['id']) && $_GET['id']!=''){
	$id=get_safe_value($_GET['id']);
	mysqli_query($con,"update user set email_verify=1 where rand_str='$id'");
	$msg="Email id verify";
	
	
	// $res=mysqli_query($con,"select from_referral_code,email from user where rand_str='$id'");
	// if(mysqli_num_rows($res)>0){
	// 	$row=mysqli_fetch_assoc($res);
	// 	$email=$row['email'];
	// 	$from_referral_code=$row['from_referral_code'];
	// 	$row=mysqli_fetch_assoc(mysqli_query($con,"select id from user where referral_code='$from_referral_code'"));
	// 	$uid=$row['id'];
	// 	$msg1='Referral Amt from '.$email;
	// 	manageWallet($uid,50,'in',$msg1);
	// }


    // xsmtpsib-91e66a60496662270d1f5b258fb2a738df2f28b9e0c31747e8cc6f72ad6b58c5-HDaIP2UNc4bvL53h
	
	
}else{
	redirect(FRONT_SITE_PATH);
}
?>

<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="<?php echo FRONT_SITE_PATH?>shop.php">Home</a></li>
                        <li class="active"> Email Verify </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="contact-area pt-100 pb-100">
            <div class="container">
                
                <div class="row">
                    <div class="col-12">
                        <div class="contact-message-wrapper">
                            <h4 class="contact-title">
								<?php
								echo $msg;
								?>
							</h4>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
include("footer.php");
?>