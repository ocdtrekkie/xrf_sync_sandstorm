<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");

if ($xrf_myulevel < 3)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

echo "
<table width=\"100%\"><tr><td width=\"50%\">

<!-- left side -->
<p><b>User Management</b></p>
<p><a href=\"acp_users.php\">View All Users</a><br>
<p><b>General Management</b></p>
<p><a href=\"acp_general.php\">General Configuration</a><br>
<a href=\"acp_view_log.php\">View System Log</a><br>
<p><b>Database Management</b></p>
<a href=\"acp_service_db.php\">Service Database</a><br>
<a href=\"acp_export_db.php\">Export Database</a></p>

</td><td width=\"50%\">

<!-- right side -->";

$query="SELECT * FROM g_modules WHERE active = 1 ORDER BY ord ASC";
$result=mysqli_query($xrf_db, $query);

$num=mysqli_num_rows($result);

$qq=0;
while ($qq < $num) {

$modid=xrf_mysql_result($result,$qq,"id");
$modname=xrf_mysql_result($result,$qq,"name");
$modfolder=xrf_mysql_result($result,$qq,"folder");

echo "<p><b>$modname</b></p>";

include "modules/$modfolder/controlpanel.php";
$qq++;
}

echo "</td></tr></table>";

}

require_once("includes/footer.php");
?>