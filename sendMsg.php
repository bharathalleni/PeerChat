<?php
$userId      =$_REQUEST["userId"];

$randomUserId=$_REQUEST["strangerId"];
$msg         =$_REQUEST["msg"];

include('config.inc.php');
include('database.inc.php');

mysql_query("INSERT INTO msgs(userId,randomUserId,msg) VALUES($userId, $randomUserId, '$msg'); ");

mysql_close($con);
?>