<?php
require_once("includes/global.php");
require_once("includes/header.php");

echo "<p><b>begin XRFinfo</b><br>
SITEname: $xrf_site_name<br>
SITEurl: $xrf_site_url<br>
SITEcontact: $xrf_admin_email<br>
XRFserver: $xrf_server_name<br>
XRFversion: $xrf_auth_version_db ($xrf_auth_version_page)<br>
XRFlicense: $xrf_site_key<br>
<b>end XRFinfo</b></p>

<p>PHPversion: " . phpversion() . "<br>SQLversion: " . mysqli_get_server_info($xrf_db) . "</p>";

require_once("includes/footer.php"); ?>