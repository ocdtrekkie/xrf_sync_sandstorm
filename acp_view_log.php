<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");
require_once("includes/functions_get.php");

(int)$page=$_GET['page'] ?? 1;
$perpage = 30;

if ($xrf_myulevel < 3)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

echo "<b>System Log</b><p>";

$start = ($page - 1) * $perpage;
$query="SELECT * FROM g_log ORDER BY date DESC LIMIT $start,$perpage";
$result=mysqli_query($xrf_db, $query);
$num=mysqli_num_rows($result);

$pagecontrols = "<p><small>";
if($page != 1) {
    $prevpage = $page - 1;
    $pagecontrols .= "<a href=\"acp_view_log.php?page=1\">[<<]</a> <a href=\"acp_view_log.php?page=$prevpage\">[<]</a> ";
}
if($num == $perpage) {
    $nextpage = $page + 1;
    $pagecontrols .= "<a href=\"acp_view_log.php?page=$nextpage\">[>]</a>";
}
$pagecontrols .= "</small></p>";

echo "$pagecontrols<table>
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

echo "</table>$pagecontrols";
}

require_once("includes/footer.php");
?>