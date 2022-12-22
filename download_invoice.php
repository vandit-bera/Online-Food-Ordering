<?php
session_start();
include('database.inc.php');
include('function.inc.php');
include('constant.inc.php');
include('vendor/autoload.php');

if(isset($_SESSION['ADMIN_USER'])){
	
}else{
	if(!isset($_SESSION['FOOD_USER_ID'])){
		redirect(FRONT_SITE_PATH.'shop.php');
	}
}


if(isset($_GET['id'])  && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	
	$res=mysqli_query($con,"select * from order_master where id='$id'");
	if(isset($_SESSION['ADMIN_USER'])){
		$row=mysqli_fetch_assoc($res);
		$uid=$row['user_id'];
	}else{
		$check=mysqli_fetch_assoc($res);
	
		if($check['user_id']!=$_SESSION['FOOD_USER_ID']){
			redirect(FRONT_SITE_PATH.'shop.php');
		}
		$uid=$_SESSION['FOOD_USER_ID'];
	}
	$orderEmail=orderEmail($id,$uid);
	
	$mpdf=new \Mpdf\Mpdf();
	$mpdf->WriteHTML($orderEmail);
	$file=time().'.pdf';
	$mpdf->Output($file,'D');
}
?>