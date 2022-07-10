<?php

//Function xrf_get_username
//Use: Gets username from a user id
function xrf_get_username($xrf_db, $uid)
{
$query="SELECT username FROM g_users WHERE id='$uid'";
$result=mysqli_query($xrf_db, $query);
$username=xrf_mysql_result($result,0,"username");
return ($username);
}

?>
