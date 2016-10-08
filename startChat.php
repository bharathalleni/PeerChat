<?php
include('config.inc.php');

include('database.inc.php');

mysql_query("INSERT INTO users (inchat) values('N');");

echo mysql_insert_id();

mysql_close($con);
?>