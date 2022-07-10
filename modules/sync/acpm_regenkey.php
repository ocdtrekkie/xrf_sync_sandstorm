<?php
require("ismodule.php");
$do = $_GET['do'];
if ($do == "rekey")
{
$descr = mysqli_real_escape_string($xrf_db, $_POST['descr']);
$access_key = "00" . xrf_generate_password(126);
$acc1 = substr($access_key, 0, 64);
$acc2 = substr($access_key, 64, 64);

$rekeynode = mysqli_prepare($xrf_db, "UPDATE y_nodes SET access_key = ? WHERE descr = ?");
mysqli_stmt_bind_param($rekeynode,"ss", $access_key, $descr);
mysqli_stmt_execute($rekeynode) or die(mysqli_error($xrf_db));

$logrekeynode = mysqli_prepare($xrf_db, "INSERT INTO g_log (uid, date, event) VALUES (?, NOW(), ?)");
$logrekeynodetext = "Sync: Node " . $descr . " rekeyed.";
mysqli_stmt_bind_param($logrekeynode, "is", $xrf_myid, $logrekeynodetext);
mysqli_stmt_execute($logrekeynode) or die(mysqli_error($xrf_db));

echo "<p>Node rekeyed. $descr's access key is:</p><p><font size=2>$acc1<br>$acc2</font></p>";
}
else
{
echo "<b>Rekey Sync Node</b><p>";

$query="SELECT descr FROM y_nodes ORDER BY descr ASC";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

echo "<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=regenkey&do=rekey\" method=\"POST\"><table><tr><td><b>Sync Node:</b></td><td><select name=\"descr\">";
$qq = 0;
while ($qq < $num) {
$descr=xrf_mysql_result($result,$qq,"descr");

echo "<option value=\"$descr\">$descr</option>";
$qq++;
}


echo "</select></td></tr><tr><td></td><td><input type=\"submit\" value=\"Rekey\"></td></tr></table></form>";
}
?>