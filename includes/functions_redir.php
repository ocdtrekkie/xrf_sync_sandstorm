<?php

//Function xrf_go_redir
//Use: Redirects to another page.
function xrf_go_redir($url,$action,$delay)
{
echo "<meta http-equiv=\"REFRESH\" content=\"$delay;url=$url\">
$action<br>Redirecting... in $delay seconds. <a href=$url>Click here</a> if you dont wish to wait any longer.";
}

?>