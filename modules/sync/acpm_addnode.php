<?php
require("ismodule.php");
$do = $_GET['do'];
if ($do == "add")
{
$descr = mysqli_real_escape_string($xrf_db, $_POST['descr']);
$pool_id = mysqli_real_escape_string($xrf_db, $_POST['pool_id']);
$pool_id = (int)$pool_id;
$static = mysqli_real_escape_string($xrf_db, $_POST['static']);
$static = (int)$static;
$access_key = "00" . xrf_generate_password(62);

echo "<script>
  function requestIframeURL() {
    var templateToken = \"\$API_TOKEN\";
	var templateHost = \"https://\$API_HOST/v1.php\"
    window.parent.postMessage({renderTemplate: {
      rpcId: \"0\",
      template: templateToken,
	  petname: '$descr',
      clipboardButton: 'left'
    }}, \"*\");
	window.parent.postMessage({renderTemplate: {
      rpcId: \"1\",
      template: templateHost,
	  petname: '$descr',
      clipboardButton: 'left'
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
</script>";

$addnode = mysqli_prepare($xrf_db, "INSERT INTO y_nodes (pool_id, descr, access_key, static) VALUES(?, ?, ?, ?)");
mysqli_stmt_bind_param($addnode,"issi", $pool_id, $descr, $access_key, $static);
mysqli_stmt_execute($addnode) or die(mysqli_error($xrf_db));

$lognewnode = mysqli_prepare($xrf_db, "INSERT INTO g_log (uid, date, event) VALUES (?, NOW(), ?)");
$lognewnodetext = "Sync: Node " . $descr . " added to pool " . $pood_id . ".";
mysqli_stmt_bind_param($lognewnode, "is", $xrf_myid, $lognewnodetext);
mysqli_stmt_execute($lognewnode) or die(mysqli_error($xrf_db));

echo "<p>Node added. $descr's access key is:</p><p><font size=2>$access_key</font></p><p>Sandstorm bearer token is:<br>
<iframe style=\"background-color: white; color: black; width: 100%; height: 55px; margin: 0; border: 0;\" id=\"offer-token\"></iframe><p>Sandstorm host URL is:<br>
<iframe style=\"background-color: white; color: black; width: 100%; height: 55px; margin: 0; border: 0;\" id=\"offer-host\"></iframe>";
}
else
{
echo "<b>Add New Sync Node</b><p>";

echo "<form action=\"acp_module_panel.php?modfolder=$modfolder&modpanel=addnode&do=add\" method=\"POST\">
<table><tr><td><b>Nickname:</b></td><td><input type=\"text\" name=\"descr\" size=\"20\"></td></tr>
<tr><td><b>Pool ID:</b></td><td><input type=\"text\" name=\"pool_id\" value=\"0\" size=\"3\"></td></tr>
<tr><td><b>Always On?</b></td><td><select name=\"static\"><option value=\"0\">No</option><option value=\"1\">Yes</option></select></td></tr>
<tr><td></td><td><input type=\"submit\" value=\"Add\"></td></tr></table></form>";
}
?>