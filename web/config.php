<?php

session_start();

$db_host = "localhost";
$db_user = "yash";
$db_pass = "Yashraghav@";
$db_name = "web";

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysql->set_charset("utf8");

?>
