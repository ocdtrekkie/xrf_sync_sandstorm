<?php
require_once("includes/variables.php");
require_once("includes/functions_auth.php");
require_once("includes/functions_db.php");
require_once("includes/functions_redir.php");
require_once("includes/functions_sec.php");
$xrf_auth_version_page = "0S.2";

// Guest settings
$xrf_myid = 0;
$xrf_myusername = "";
$xrf_myuclass = "";
$xrf_myulevel = 1;

$xrf_db = @mysqli_connect($xrf_dbserver, $xrf_dbusername, $xrf_dbpassword, $xrf_dbname) or die(mysqli_connect_error());

ob_start();
session_start();

$xrf_config_query="SELECT * FROM g_config";
$xrf_config_result=mysqli_query($xrf_db, $xrf_config_query);

$xrf_site_name=xrf_mysql_result($xrf_config_result,0,"site_name");
$xrf_site_url=xrf_mysql_result($xrf_config_result,0,"site_url");
$xrf_site_key=xrf_mysql_result($xrf_config_result,0,"site_key");
$xrf_auth_version_db=xrf_mysql_result($xrf_config_result,0,"auth_version");
$xrf_server_name=xrf_mysql_result($xrf_config_result,0,"server_name");
$xrf_admin_email=xrf_mysql_result($xrf_config_result,0,"admin_email");
$xrf_admin_id=xrf_mysql_result($xrf_config_result,0,"admin_id");
$xrf_vlog_enabled=xrf_mysql_result($xrf_config_result,0,"vlog_enabled");
$xrf_style_default=xrf_mysql_result($xrf_config_result,0,"style_default");

xrf_check_auth_version($xrf_auth_version_page, $xrf_auth_version_db) or die("Unable to verify authentication version.  Please report to the system administrator.");

$xrf_myemail = $_SERVER['HTTP_X_SANDSTORM_USER_ID'];
$xrf_myusername = urldecode($_SERVER['HTTP_X_SANDSTORM_USERNAME']);
if (strpos($_SERVER['HTTP_X_SANDSTORM_PERMISSIONS'], "admin") !== false) { $xrf_myulevel = 4; }
elseif (strpos($_SERVER['HTTP_X_SANDSTORM_PERMISSIONS'], "mod") !== false) { $xrf_myulevel = 3; }
elseif (strpos($_SERVER['HTTP_X_SANDSTORM_PERMISSIONS'], "user") !== false) { $xrf_myulevel = 2; }

// Ensure user is logged in
if ($xrf_myusername == "Anonymous User")
{
die("You are not logged in!");
}
else
{
	$xrf_adduser_query=mysqli_prepare($xrf_db, "INSERT IGNORE INTO g_users (sandstormuserid, datereg) VALUES(?,now())") or die(mysqli_error($xrf_db));
	mysqli_stmt_bind_param($xrf_adduser_query,"s", $xrf_myemail);
	mysqli_stmt_execute($xrf_adduser_query) or die(mysqli_error($xrf_db));

	$xrf_updateuser_query=mysqli_prepare($xrf_db, "UPDATE g_users SET username = ?, lastlogin = now(), ulevel = ? WHERE sandstormuserid=?") or die(mysqli_error($xrf_db));
	mysqli_stmt_bind_param($xrf_updateuser_query,"sis", $xrf_myusername, $xrf_myulevel, $xrf_myemail);
	mysqli_stmt_execute($xrf_updateuser_query) or die(mysqli_error($xrf_db));

	$xrf_getuser_query=mysqli_prepare($xrf_db, "SELECT id, style_pref FROM g_users WHERE sandstormuserid=?") or die(mysqli_error($xrf_db));
	mysqli_stmt_bind_param($xrf_getuser_query,"s", $xrf_myemail);
	mysqli_stmt_execute($xrf_getuser_query) or die(mysqli_error($xrf_db));
	mysqli_stmt_bind_result($xrf_getuser_query, $xrf_getuser_id, $xrf_getuser_style_pref);
	while (mysqli_stmt_fetch($xrf_getuser_query)) {
        $xrf_myid = $xrf_getuser_id;
		$xrf_mystylepref = $xrf_getuser_style_pref;
    }
	mysqli_stmt_close($xrf_getuser_query);
}
?>