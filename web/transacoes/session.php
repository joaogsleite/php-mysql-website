<?php
include('config.php');

if(!isset($_SESSION['userid'])){ header("Location: ./login.php"); }

$userid=$mysql->real_escape_string($_SESSION['userid']);

$login_query=$mysql->query("SELECT * FROM utilizador WHERE userid='$userid'");

if($login_query->num_rows != 1){ header("Location: ./login.php");  }

$row = $login_query->fetch_assoc();
$userid = $row['userid'];

?>

