<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");

if ($xrf_myulevel < 3)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

$do = $_GET['do'];
if ($do == "change")
{
	$new_site_name = mysqli_real_escape_string($xrf_db, $_POST['site_name']);
	$new_site_url = mysqli_real_escape_string($xrf_db, $_POST['site_url']);
	$new_site_key = mysqli_real_escape_string($xrf_db, $_POST['site_key']);
	$new_server_name = mysqli_real_escape_string($xrf_db, $_POST['server_name']);
	$new_admin_email = mysqli_real_escape_string($xrf_db, $_POST['admin_email']);
	$new_vlog_enabled = $_POST['vlog_enabled'];
	$new_vlog_enabled = (int)$new_vlog_enabled;
	$new_style_default = mysqli_real_escape_string($xrf_db, $_POST['style_default']);
	
	if ($new_site_name != $xrf_site_name)
	{
		$query = "UPDATE g_config SET site_name = '$new_site_name'";
		mysqli_query($xrf_db, $query);
		$xrf_site_name = $new_site_name;
		if ($xrf_vlog_enabled == 1)
		{
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Changed site name to $new_site_name.')";
			mysqli_query($xrf_db, $query);
		}
	}
	
	if ($new_site_url != $xrf_site_url)
	{
		$query = "UPDATE g_config SET site_url = '$new_site_url'";
		mysqli_query($xrf_db, $query);
		$xrf_site_url = $new_site_url;
		if ($xrf_vlog_enabled == 1)
		{
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Changed site URL to $new_site_url.')";
			mysqli_query($xrf_db, $query);
		}
	}
	
	if ($new_site_key != $xrf_site_key)
	{
		$query = "UPDATE g_config SET site_key = '$new_site_key'";
		mysqli_query($xrf_db, $query);
		$xrf_site_key = $new_site_key;
		if ($xrf_vlog_enabled == 1)
		{
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Changed site license key.')";
			mysqli_query($xrf_db, $query);
		}
	}
	
	if ($new_server_name != $xrf_server_name)
	{
		$query = "UPDATE g_config SET server_name = '$new_server_name'";
		mysqli_query($xrf_db, $query);
		$xrf_server_name = $new_server_name;
		if ($xrf_vlog_enabled == 1)
		{
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Changed server name.')";
			mysqli_query($xrf_db, $query);
		}
	}
	
	if ($new_admin_email != $xrf_admin_email)
	{
		$query = "UPDATE g_config SET admin_email = '$new_admin_email'";
		mysqli_query($xrf_db, $query);
		$xrf_admin_email = $new_admin_email;
		if ($xrf_vlog_enabled == 1)
		{
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Changed admin email to $new_admin_email.')";
			mysqli_query($xrf_db, $query);
		}
	}
	
	if ($new_vlog_enabled != $xrf_vlog_enabled)
	{
		$query = "UPDATE g_config SET vlog_enabled = '$new_vlog_enabled'";
		mysqli_query($xrf_db, $query);
		$xrf_vlog_enabled = $new_vlog_enabled;
		if ($new_vlog_enabled == 1)
			$vlogch = "Enabled";
		else
			$vlogch = "Disabled";
		$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'$vlogch verbose logging.')";
		mysqli_query($xrf_db, $query);
	}
	
	if ($new_style_default != $xrf_style_default)
	{
		$query = "UPDATE g_config SET style_default = '$new_style_default'";
		mysqli_query($xrf_db, $query);
		$xrf_style_default = $new_style_default;
		if ($xrf_vlog_enabled == 1)
		{
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Changed default style to $new_style_default.')";
			mysqli_query($xrf_db, $query);
		}
	}
	
	xrf_go_redir("acp.php","Settings changed.",2); 
}
else
{
	if ($xrf_vlog_enabled == 1)
		$vlogy = " checked";
	else
		$vlogn = " checked";

	echo "
	<p><b>General Configuration</b></p>
	<form action=\"acp_general.php?do=change\" method=\"POST\">
	<table>
	<tr><td>Site Name:</td><td><input type=\"text\" name=\"site_name\" value=\"$xrf_site_name\" size=\"30\"></td></tr>
	<tr><td>Site URL:</td><td><input type=\"text\" name=\"site_url\" value=\"$xrf_site_url\" size=\"30\"></td></tr>
	<tr><td>Site Key:</td><td><input type=\"text\" name=\"site_key\" value=\"$xrf_site_key\" size=\"30\"></td></tr>
	<tr><td>Server Name:</td><td><input type=\"text\" name=\"server_name\" value=\"$xrf_server_name\" size=\"30\"></td></tr>
	<tr><td>Admin Email:</td><td><input type=\"text\" name=\"admin_email\" value=\"$xrf_admin_email\" size=\"30\"></td></tr>
	<tr><td>Verbose Logging:</td><td><input type=\"radio\" name=\"vlog_enabled\" value=1$vlogy> On <input type=\"radio\" name=\"vlog_enabled\" value=0$vlogn> Off</td></tr>
	<tr><td>Default Style:</td><td><select name=\"style_default\">";
	
	$query = "SELECT * FROM g_styles WHERE active = 1";
	$result = mysqli_query($xrf_db, $query);
	$num = mysqli_num_rows($result);
	$qq = 0;
	while ($qq < $num) {
		$current = "";
		$s_name = xrf_mysql_result($result,$qq,"name");
		$s_descr = xrf_mysql_result($result,$qq,"descr");
		if ($s_name == $xrf_style_default) $current = "selected";
		echo "<option value=\"$s_name\" $current>$s_descr</option>";
		$qq++;
	}
	
	echo "</select></td></tr>
	</table>
	<input type=\"submit\" value=\"Submit!\">
	</form>";
}

}

require_once("includes/footer.php");
?>