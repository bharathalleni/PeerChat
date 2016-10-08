<?php
$userId    =$_REQUEST["userId"];

$strangerId=$_REQUEST["strangerId"];
$log       ="";
include ('config.inc.php');
include ('database.inc.php');

$result    =mysql_query(
                "SELECT * FROM oldmsgs WHERE userId = $userId OR randomUserId = $userId order by archivedDate ;");
$log       =$log . "<div style='padding:15px;' >";

while ($row=mysql_fetch_array($result))
    {
    $msg   = $row["msg"];
    $sender=$row["userId"];

    if ($sender == $userId)
        {
        $log=$log . "<div class='logitem'><div class='youmsg'><span class='msgsource'>Jij:</span> $msg</div></div>";
        }
    else if ($sender == $strangerId)
        {
        $log=$log
            . "<div class='logitem'><div class='strangermsg'><span class='msgsource'>Onbekende:</span> $msg</div></div>";
        }
    }

$log=$log . "</div>";
mysql_close ($con);
echo $log;
?>