<?php
require_once("includes/global_req_login.php");
require_once("includes/header.php");

if ($xrf_myulevel < 4)
{
xrf_go_redir("index.php","Invalid permissions.",2);
}
else
{

echo "<b>Database Export</b><p>";
// credit: https://stackoverflow.com/a/47605892/369990
$tables = array();

$result = mysqli_query($xrf_db,"SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
	$tables[] = $row[0];
}

$return = '';

foreach ($tables as $table) {
	$result = mysqli_query($xrf_db, "SELECT * FROM ".$table);
	$num_fields = mysqli_num_fields($result);

	$return .= 'DROP TABLE '.$table.';';
	$row2 = mysqli_fetch_row(mysqli_query($xrf_db, 'SHOW CREATE TABLE '.$table));
	$return .= "\n\n".$row2[1].";\n\n";

	for ($i=0; $i < $num_fields; $i++) { 
		while ($row = mysqli_fetch_row($result)) {
			$return .= 'INSERT INTO '.$table.' VALUES(';
			for ($j=0; $j < $num_fields; $j++) { 
				$row[$j] = addslashes($row[$j]);
				if (isset($row[$j])) {
					$return .= '"'.$row[$j].'"';} else { $return .= '""';}
					if($j<$num_fields-1){ $return .= ','; }
				}
				$return .= ");\n";
			}
		}
		$return .= "\n\n\n";
	
}

$handle = fopen('/var/backup' . time() . '.sql', 'w+');
fwrite($handle, $return);
fclose($handle);
echo "Database is backed up.";

}

require_once("includes/footer.php");
?>