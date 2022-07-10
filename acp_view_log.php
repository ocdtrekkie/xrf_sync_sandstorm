<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");

if ($xrf_myulevel < 3)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

echo "<b>System Log</b><p>";

$query="SELECT * FROM g_log ORDER BY date DESC LIMIT 25";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

echo "<table>
<tr><td width=50><b>ID</b></td><td width=50><b>UID</b></td><td width=225><b>Date</b></td><td width=475><b>Event</b></td></tr>";
$qq=0;
while ($qq < $num) {

$id=xrf_mysql_result($result,$qq,"id");
$uid=xrf_mysql_result($result,$qq,"uid");
$date=xrf_mysql_result($result,$qq,"date");
$event=xrf_mysql_result($result,$qq,"event");

echo "<tr><td>$id</td><td><a href=\"acp_view_user.php?id=$uid\">$uid</a></td><td>$date</td><td>$event</td></tr>";
$qq++;
}

echo "</table>";
}

require_once("includes/footer.php");
?>