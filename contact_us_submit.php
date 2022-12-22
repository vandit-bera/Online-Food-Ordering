<?php
include('database.inc.php');
include('function.inc.php');
include('constant.inc.php');
$name=get_safe_value($_POST['name']);
$email=get_safe_value($_POST['email']);
$mobile=get_safe_value($_POST['mobile']);
$subject=get_safe_value($_POST['subject']);
$message=get_safe_value($_POST['message']);
$added_on=date('Y-m-d h:i:s');
mysqli_query($con,"insert into contact_us(name,email,mobile,subject,message,added_on) values('$name','$email','$mobile','$subject','$message','$added_on')");
echo "Thank you for connecting with us, will get back to you shortly";
?>
