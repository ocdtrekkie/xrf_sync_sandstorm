<?php
require("ismodule.php");
$do = $_GET['do'] ?? '';
if ($do == "request")
{
$descr = mysqli_real_escape_string($xrf_db, $_POST['descr']);

if ($xrf_style == "xrflight") {
	$tcolor = "#000000"; $bcolor = "#ffffff";
} elseif ($xrf_style == "xrfdark") {
	$tcolor = "#ffffff"; $bcolor = "#000000";
}

echo "<script>
  function requestIframeURL() {
    var templateToken = \"\$API_TOKEN\";
	var templateHost = \"https://\$API_HOST/v1.php\"
    window.parent.postMessage({renderTemplate: {
      rpcId: \"0\",
      template: templateToken,
      petname: '$descr',
      forSharing: true,
      roleAssignment: {roleId: 3}, // guest
      clipboardButton: 'left',
	  style: { color: '$tcolor' }
    }}, \"*\");
	window.parent.postMessage({renderTemplate: {
      rpcId: \"1\",
      template: templateHost,
      petname: '$descr',
      forSharing: true,
      roleAssignment: {roleId: 3}, // guest
      clipboardButton: 'left',
	  style: { color: '$tcolor' }
    }}, \"*\");
  }

  document.addEventListener(\"DOMContentLoaded\", requestIframeURL);
  
  var copyIframeURLToElement = function(event) {
    if (event.data.rpcId === \"0\") {
      if (event.data.error) {
        console.log(\"ERROR: \" + event.data.error);
      } else {
        var el = document.getElementById(\"offer-token\");
        el.setAttribute(\"src\", event.data.uri);
      }
    }
	if (event.data.rpcId === \"1\") {
      if (event.data.error) {
        console.log(\"ERROR: \" + event.data.error);
      } else {
        var el = document.getElementById(\"offer-host\");
        el.setAttribute(\"src\", event.data.uri);
      }
    }
  };

  window.addEventListener(\"message\", copyIframeURLToElement);
</script><b>Remote Rekey Node</b><p>

<p>Sandstorm Host URL is:<p>
<iframe style=\"background-color: $bcolor; width: 100%; height: 30px; margin: 0; border: 0;\" id=\"offer-host\"></iframe><p>Sandstorm Access Token is:<p>
<iframe style=\"background-color: $bcolor; width: 100%; height: 30px; margin: 0; border: 0;\" id=\"offer-token\"></iframe><p>

<p>To submit a remote rekey command, enter the following command below:<br /><b>remote rekey \$HOST_URL \$SANDSTORM_TOKEN</b></p>

<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=remoterekey&do=rekey\" method=\"POST\">
<input type=\"text\" name=\"rekeycmd\" style=\"width: 100%\" value=\"remote rekey \"><br>
<input type=\"hidden\" name=\"descr\" value=\"$descr\">
<input type=\"submit\" value=\"Submit Rekey\"></form>";

}
elseif ($do == "rekey") {
	$descr = mysqli_real_escape_string($xrf_db, $_POST['descr']);
	$rekeycmd = mysqli_real_escape_string($xrf_db, $_POST['rekeycmd']);

	$rekeynode = mysqli_prepare($xrf_db, "INSERT INTO y_messages (source, dest, sent, mesg) VALUES ('server', ?, NOW(), ?)");
	mysqli_stmt_bind_param($rekeynode,"ss", $descr, $rekeycmd);
	mysqli_stmt_execute($rekeynode) or die(mysqli_error($xrf_db));

	$logrekeynode = mysqli_prepare($xrf_db, "INSERT INTO g_log (uid, date, event) VALUES (?, NOW(), ?)");
	$logrekeynodetext = "Sync: Node " . $descr . " remote rekeyed.";
	mysqli_stmt_bind_param($logrekeynode, "is", $xrf_myid, $logrekeynodetext);
	mysqli_stmt_execute($logrekeynode) or die(mysqli_error($xrf_db));

	xrf_go_redir("acp_module_panel.php?modfolder=$modfolder&modpanel=nodelist","Node \"$descr\" remote rekeyed.",6);
}
else {
echo "<b>Remote Rekey Node</b><p>

<p>This should be done very quickly while a node is actively online and updating with Sync. If the offer template expires before the node attempts to rekey, the rekey operation may fail. To ensure a more reliable rekey, access the rekeyed API URL and validate it appears in the webkeys dropdown before submitting the rekey instruction to the node.</p>";

$query="SELECT descr FROM y_nodes ORDER BY descr ASC";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

echo "<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=remoterekey&do=request\" method=\"POST\"><table><tr><td><b>Sync Node:</b></td><td><select name=\"descr\">";
$qq = 0;
while ($qq < $num) {
$descr=xrf_mysql_result($result,$qq,"descr");

echo "<option value=\"$descr\">$descr</option>";
$qq++;
}


echo "</select></td></tr><tr><td></td><td><input type=\"submit\" value=\"Request Rekey\"></td></tr></table></form>";
}
?>