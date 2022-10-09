<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");
require_once("includes/functions_get.php");

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
<tr><td width=175><b>User</b></td><td width=175><b>Date</b></td><td width=450><b>Event</b></td></tr>";
$qq=0;
while ($qq < $num) {

$uid=xrf_mysql_result($result,$qq,"uid");
$date=xrf_mysql_result($result,$qq,"date");
$event=xrf_mysql_result($result,$qq,"event");
$username=xrf_get_username($xrf_db, $uid);

echo "<tr><td><font size=2><a href=\"acp_users.php\">$username ($uid)</a></font></td><td><font size=2>$date</font></td><td><font size=2>$event</font></td></tr>";
$qq++;
}

echo "</table>";
}

require_once("includes/footer.php");
?>