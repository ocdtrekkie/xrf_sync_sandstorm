<?php

//Function xrf_check_auth_version
//Use: Checks authorization version.
function xrf_check_auth_version($xrf_auth_version_page, $xrf_auth_version_db)
{
	if (strpos($xrf_auth_version_db, $xrf_auth_version_page) === false)
	{
		die("The authentication version for this module is outdated.  Please report to the system administrator.");
	}
	else
		return true;
}

//Function xrf_generate_password
//Use: Generates a random password.
function xrf_generate_password($length)
{
	$password = "";
	$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ"; // Possible characters
	$maxlength = strlen($possible);
	$i = 0;
	while ($i < $length) {
		$char = substr($possible, mt_rand(0, $maxlength-1), 1);
		$password .= $char;
		$i++;
	}
	return ($password);
}

?>