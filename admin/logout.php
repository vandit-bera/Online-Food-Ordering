<?php
session_start();
include('../function.inc.php');
unset($_SESSION['IS_LOGIN']);
unset($_SESSION['ADMIN_USER']);
redirect('login.php');
?>