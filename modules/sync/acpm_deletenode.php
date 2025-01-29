<?php
require("ismodule.php");
$do = $_GET['do'] ?? '';
if ($do == "delete")
{
	if(isset($_POST['confirm']))
	{
		$confirm = 1;
	}
	else
	{
		$confirm = 0;
	}
	
	if ($confirm == 1) {
$descr = mysqli_real_escape_string($xrf_db, $_POST['descr']);

$cleandetailstable = mysqli_prepare($xrf_db, "DELETE FROM y_nodekv WHERE descr = ?");
mysqli_stmt_bind_param($cleandetailstable, "s", $descr);
mysqli_stmt_execute($cleandetailstable) or die(mysqli_error($xrf_db));
echo "System details cleared.<br>";

$cleansoftwaretable = mysqli_prepare($xrf_db, "DELETE FROM y_nodesoftware WHERE descr = ?");
mysqli_stmt_bind_param($cleansoftwaretable, "s", $descr);
mysqli_stmt_execute($cleansoftwaretable) or die(mysqli_error($xrf_db));
echo "Software inventory records cleared.<br>";

$cleanmessagestable = mysqli_prepare($xrf_db, "DELETE FROM y_messages WHERE dest = ?");
mysqli_stmt_bind_param($cleanmessagestable, "s", $descr);
mysqli_stmt_execute($cleanmessagestable) or die(mysqli_error($xrf_db));
echo "Messages waiting for delivery cleared.<br>";

$logdeletenode = mysqli_prepare($xrf_db, "INSERT INTO g_log (uid, date, event) VALUES (?, NOW(), ?)");
$logdeletenodetext = "Sync: Node " . $descr . " deleted.";
mysqli_stmt_bind_param($logdeletenode, "is", $xrf_myid, $logdeletenodetext);
mysqli_stmt_execute($logdeletenode) or die(mysqli_error($xrf_db));

$cleannodetable = mysqli_prepare($xrf_db, "DELETE FROM y_nodes WHERE descr = ?");
mysqli_stmt_bind_param($cleannodetable, "s", $descr);
mysqli_stmt_execute($cleannodetable) or die(mysqli_error($xrf_db));
echo "<p>Node \"$descr\" deleted.</p>";
	}
	else
	{
		echo "<p>Deletion request was not confirmed. No changes made.</p>";
	}
}
else
{
echo "<b>Delete Sync Node</b><p>This will permanently remove all pending messages and stored information about a node. It is not reversible.<p>";

$query="SELECT descr FROM y_nodes ORDER BY descr ASC";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

echo "<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=deletenode&do=delete\" method=\"POST\"><table><tr><td><b>Sync Node:</b></td><td><select name=\"descr\">";
$qq = 0;
while ($qq < $num) {
$descr=xrf_mysql_result($result,$qq,"descr");

echo "<option value=\"$descr\">$descr</option>";
$qq++;
}


echo "</select></td></tr><tr><td></td><td><input type=\"checkbox\" name=\"confirm\"> Confirm<br><input type=\"submit\" value=\"Delete\"></td></tr></table></form>";
}
?>