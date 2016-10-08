<?php
/* ###########################################################
 * File brought to you by TeamColtra of NulledScriptz.com
 * There are big ships
 * There are small ships
 * but the best ship
 * is friendship!
 * Sharing is caring... please pass this along but leave this
 * intact. Donatations accepted at gppaypal@gmail.com anything
 * helps. Support those who bring you free stuff!
 ##############################################################*/
include('config.inc.php');

include('database.inc.php');

$result=mysql_query("SELECT * FROM users");

$count =0;

while ($row=mysql_fetch_array($result))
    {
    $count++;
    }

mysql_close($con);

echo $count;
?>