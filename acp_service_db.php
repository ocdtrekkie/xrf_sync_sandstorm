<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");

if ($xrf_myulevel < 4)
{
	xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{
	$do = $_GET['do'] ?? '';
	if ($do == "execute")
	{
		$sqltoexecute = $_POST['sqltoexecute'];
		if(isset($_POST['confirm']))
		{
		$confirm = 1;
		}
		else
		{
		$confirm = 0;
		}

		if ($confirm == 1)
		{
			$result=mysqli_multi_query($xrf_db, $sqltoexecute);
			$query="INSERT INTO g_log (uid, date, event) VALUES ('$xrf_myid',NOW(),'Executed arbitrary SQL servicing.')";
			mysqli_query($xrf_db, $query);
			xrf_go_redir("acp.php","Servicing complete.",2);
		}
		else
			xrf_go_redir("acp.php","Servicing not performed.",2);
	}
	else
	{
		echo "
		<p><b>Service Database</b></p>
		<p align=\"left\"><b><font color=\"red\">WARNING!</font> This page runs unprotected SQL queries against the database. You can irreparabily and unrecoverably damage the information in this system.</b></p>
		<p align=\"left\">SQL entered into the box below will be executed in full against the database. This can be useful for bulk operations not supported by the user interface, or to repair issues with tables or records. You should not use this unless you are well-versed in the table formats used by XRF, or so instructed by support.</p>
		<form action=\"acp_service_db.php?do=execute\" method=\"POST\">
		<textarea name=\"sqltoexecute\" rows=\"20\" cols=\"80\"></textarea><p>
		<input type=\"checkbox\" name=\"confirm\"> Confirm intent to send.<br><input type=\"submit\" value=\"Send!\">
		</form>";
	}
}

require_once("includes/footer.php");
?>
