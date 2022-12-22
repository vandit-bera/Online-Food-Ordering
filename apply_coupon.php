<?php
session_start();
include('database.inc.php');
include('function.inc.php');
include('constant.inc.php');
include('smtp/PHPMailerAutoload.php');

$coupon_code=get_safe_value($_POST['coupon_code']);

$res=mysqli_query($con,"select * from coupon_code where coupon_code='$coupon_code' and status='1'");
$check=mysqli_num_rows($res);
if($check>0){	
	$row=mysqli_fetch_assoc($res);
	$cart_min_value=$row['cart_min_value'];
	$coupon_type=$row['coupon_type'];
	$coupon_value=$row['coupon_value'];
	$expired_on=strtotime($row['expired_on']);
	$cur_time=strtotime(date('Y-m-d'));
	$getcartTotalPrice=getcartTotalPrice();
	
	if($getcartTotalPrice>$cart_min_value){
		if($cur_time>$expired_on){
			$arr=array('status'=>'error','msg'=>'Coupon code expired');	
		}else{
			$coupon_code_apply=0;
			if($coupon_type=='F'){
				$coupon_code_apply=$getcartTotalPrice-$coupon_value;
			}if($coupon_type=='P'){
				$coupon_code_apply=$getcartTotalPrice-($coupon_value/100)*$getcartTotalPrice;
			}
			
			$_SESSION['COUPON_CODE']=$coupon_code;
			$_SESSION['FINAL_PRICE']=$coupon_code_apply;
			
			$arr=array('status'=>'success','msg'=>'Coupon code applied','field'=>'form_msg','coupon_code_apply'=>$coupon_code_apply);
		}
	}else{
		$arr=array('status'=>'error','msg'=>'Coupon code will be applied for cart value greater then '.$cart_min_value);	
	}
}else{
	$arr=array('status'=>'error','msg'=>'Coupon code not found');	
}
echo json_encode($arr);

?>