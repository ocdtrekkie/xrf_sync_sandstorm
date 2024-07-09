<?php
require("ismodule.php");
(int)$nodeid=$_GET['nodeid'];

echo "<div align=left><a href=\"acp_module_panel.php?modfolder=$modfolder&modpanel=nodelist\">< Back</a></div>";

$query="SELECT * FROM y_nodes WHERE id = $nodeid";
$result=mysqli_query($xrf_db, $query);

$id=xrf_mysql_result($result,0,"id");
$descr=xrf_mysql_result($result,0,"descr");
$pool_id=xrf_mysql_result($result,0,"pool_id");
$last_seen=xrf_mysql_result($result,0,"last_seen");
$last_ip_addr=xrf_mysql_result($result,0,"last_ip_addr");
$last_winver=xrf_mysql_result($result,0,"last_winver");
if ($last_winver != '') { $winbuild=substr($last_winver, 5); } else { $winbuild=''; }
$user_agent=xrf_mysql_result($result,0,"user_agent");
$static=xrf_mysql_result($result,0,"static");

echo "<b>$descr</b> (ID: $id)<p>";

echo "Last Seen: $last_seen<br>Last IP Address: $last_ip_addr<br>Windows Build: $winbuild<br>User Agent: $user_agent<br>Node Pool: $pool_id";

echo "<p><b>Installed Software</b></p>";

$softquery="SELECT * FROM y_nodesoftware WHERE descr = '$descr' ORDER BY appname ASC";
$softresult=mysqli_query($xrf_db, $softquery);

$num=mysqli_num_rows($softresult);

echo "<table><tr><td width=300><b>App Name</b></td><td width=180><b>Version</b><td width=220><b>Publisher</b></td><td width=120><b>Installed</b></td></tr>";
$qq=0;
while ($qq < $num) {

$appname=xrf_mysql_result($softresult,$qq,"appname");
$appver=xrf_mysql_result($softresult,$qq,"appver");
$apppub=xrf_mysql_result($softresult,$qq,"apppub");
$appdate=xrf_mysql_result($softresult,$qq,"appdate");

echo "<tr><td><small>$appname</small></td><td><small>$appver</small></td><td><small>$apppub</small></td><td><small>$appdate</small></td></tr>";
$qq++;
}

echo "</table>";

?>