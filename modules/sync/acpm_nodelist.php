<?php
require("ismodule.php");

echo "<b>Sync Nodes</b><p>";

$query="SELECT * FROM y_nodes ORDER BY descr ASC";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

echo "<table><tr><td width=40><b>ID</b></td><td width=160><b>Friendly Name</b></td><td width=50><b>Pool</b></td><td width=220><b>Last Seen</b><td width=180><b>Last IP Address</b></td><td width=200><b>User Agent</b></td></tr>";
$qq=0;
while ($qq < $num) {

$id=xrf_mysql_result($result,$qq,"id");
$descr=xrf_mysql_result($result,$qq,"descr");
$pool_id=xrf_mysql_result($result,$qq,"pool_id");
$last_seen=xrf_mysql_result($result,$qq,"last_seen");
$last_ip_addr=xrf_mysql_result($result,$qq,"last_ip_addr");
$user_agent=xrf_mysql_result($result,$qq,"user_agent");
$static=xrf_mysql_result($result,$qq,"static");
// TODO: If static, last seen should be green or red based on how long since it's checked in

echo "<tr><td>$id</td><td>$descr</td><td>$pool_id</td><td>$last_seen</td><td>$last_ip_addr</td><td>$user_agent</td></tr>";
$qq++;
}

echo "</table>";
?>