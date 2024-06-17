<?php

//Function xrf_mysql_result
//Use: Replaces mysql_result for mysqli
//Credit: http://php.net/manual/en/function.mysql-result.php#116670
function xrf_mysql_result($result, $number, $field=0)
{
mysqli_data_seek($result, $number);
$row = mysqli_fetch_array($result);
return $row[$field] ?? false;
}

?>