<?php
$userId=$_REQUEST["userId"];

include('config.inc.php');
include('database.inc.php');

mysql_query("DELETE FROM typing WHERE id = $userId ");

mysql_close($con);
?>