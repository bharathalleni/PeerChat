<?php
$userId=$_REQUEST["userId"];

include('config.inc.php');
include('database.inc.php');

$result=mysql_query("SELECT * FROM typing WHERE id=$userId ;");
$flag  =false;

while ($row=mysql_fetch_array($result))
    {
    $flag=true;
    }

if (!$flag)
    {
    mysql_query("INSERT INTO typing(id) VALUES($userId); ");
    }

mysql_close($con);
?>