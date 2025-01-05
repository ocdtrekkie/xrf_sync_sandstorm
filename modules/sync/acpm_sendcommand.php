<?php
require("ismodule.php");
$do = $_GET['do'] ?? '';
if ($do == "send")
{
$descr = mysqli_real_escape_string($xrf_db, $_POST['descr']);
$command = mysqli_real_escape_string($xrf_db, $_POST['command']);

if ($command == "reset installed software") {
	$resetsoftwaretable = mysqli_prepare($xrf_db, "DELETE FROM y_nodesoftware WHERE descr = ?");
	mysqli_stmt_bind_param($resetsoftwaretable, "s", $descr);
	mysqli_stmt_execute($resetsoftwaretable) or die(mysqli_error($xrf_db));
}

$commandnode = mysqli_prepare($xrf_db, "INSERT INTO y_messages (source, dest, sent, mesg) VALUES ('server', ?, NOW(), ?)");
mysqli_stmt_bind_param($commandnode, "ss", $descr, $command);
mysqli_stmt_execute($commandnode) or die(mysqli_error($xrf_db));
http_response_code(202);

$logcommandnode = mysqli_prepare($xrf_db, "INSERT INTO g_log (uid, date, event) VALUES (?, NOW(), ?)");
$logcommandnodetext = "Sync: Manual command to " . $descr . ": " . $command;
mysqli_stmt_bind_param($logcommandnode, "is", $xrf_myid, $logcommandnodetext);
mysqli_stmt_execute($logcommandnode) or die(mysqli_error($xrf_db));

xrf_go_redir("acp_module_panel.php?modfolder=$modfolder&modpanel=nodelist","Message queued for delivery.",6);
}
else
{
echo "<b>Send Command</b><p>";

$query="SELECT descr FROM y_nodes ORDER BY descr ASC";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

echo "<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=sendcommand&do=send\" method=\"POST\"><table><tr><td><select name=\"descr\">";
$qq = 0;
while ($qq < $num) {
$descr=xrf_mysql_result($result,$qq,"descr");

echo "<option value=\"$descr\">$descr</option>";
$qq++;
}


echo "</select></td><td><input type=\"text\" name=\"command\" size=\"80\"></td></tr><tr><td></td><td><input type=\"submit\" value=\"Send\"></td></tr></table></form>";
}
?>