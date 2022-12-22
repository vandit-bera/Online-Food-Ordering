<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

session_start();
include('database.inc.php');
include('function.inc.php');
include('constant.inc.php');
/*include('smtp/PHPMailerAutoload.php');*/

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;


$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; 
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		$TXNID=$_POST['TXNID'];
		$amt=$_POST['TXNAMOUNT'];
		if(isset($_SESSION['IS_WALLET'])){
			manageWallet($_SESSION['FOOD_USER_ID'],$amt,'in','Added',$TXNID);
			unset($_SESSION['IS_WALLET']);
			redirect(FRONT_SITE_PATH.'wallet.php');
		}else{
			$oid=$_POST['ORDERID'];
		
			$oArr=explode('_',$oid);
			$oid=$oArr[0];
			echo $oArr[1];
			$getUserDetailsBy=getUserDetailsByid($oArr[1]);
			$email=$getUserDetailsBy['email'];
			
			$emailHTML=orderEmail($oid);
			$_SESSION['ORDER_ID']=$oid;
		
			mysqli_query($con,"update  order_master set payment_status='success', 	payment_id='$TXNID' where id='$oid'");
			send_email($email,$emailHTML,'Order Placed');
			redirect(FRONT_SITE_PATH.'success.php');
		}
		
	}
	else {
		$oid=$_POST['ORDERID'];
		$oArr=explode('_',$oid);
		$oid=$oArr[0];
		$TXNID=$_POST['TXNID'];
		mysqli_query($con,"update payment_status='failed', payment_id='$TXNID' where id='$oid'");
		redirect(FRONT_SITE_PATH.'error.php');
	}
}else{
	$oid=$_POST['ORDERID'];
	$oArr=explode('_',$oid);
	$oid=$oArr[0];
	$TXNID=$_POST['TXNID'];
	mysqli_query($con,"update payment_status='failed', payment_id='$TXNID' where id='$oid'");
	redirect(FRONT_SITE_PATH.'error.php');
}

?>