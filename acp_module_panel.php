<?php
$modfolder=$_GET['modfolder'];
$modpanel=$_GET['modpanel'];
require_once("includes/global_req_login.php");
require_once("includes/header.php");
require_once("includes/functions_get.php");

if ($xrf_myulevel < 3)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

include "modules/$modfolder/acpm_$modpanel.php";

}

require_once("includes/footer.php");
?>