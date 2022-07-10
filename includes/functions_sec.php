<?php

//Function xrf_sanitize_string
//Use: Strips HTML tags
function xrf_sanitize_string($string)
{
$string = strip_tags($string);
return htmlentities($string);
}

//Function xrf_mysql_sanitize_string
//Use: Escapes and strips HTML tags
function xrf_mysql_sanitize_string($string)
{
if (get_magic_quotes_gpc())
	$string = stripslashes($string);
$string = xrf_sanitize_string($string);
return mysqli_real_escape_string($xrf_db, $string);
}

?>