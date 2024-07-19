<?php
require("ismodule.php");
(int)$nodeid=$_GET['nodeid'];

function formatBytes($bytes, $precision = 2) {
    if ($bytes == '') { return ''; } else { $bytes = floatval($bytes); }
    if ($bytes > pow(1024,4)) return round($bytes / pow(1024,4), $precision)."TB";
	else if ($bytes > pow(1024,3)) return round($bytes / pow(1024,3), $precision)."GB";
    else if ($bytes > pow(1024,2)) return round($bytes / pow(1024,2), $precision)."MB";
    else if ($bytes > 1024) return round($bytes / 1024, $precision)."KB";
    else return ($bytes)."B";
} 

echo "<div align=left><a href=\"acp_module_panel.php?modfolder=$modfolder&modpanel=nodelist\">< Back</a></div>";

$query="SELECT * FROM y_nodes WHERE id = $nodeid";
$result=mysqli_query($xrf_db, $query);

$id=xrf_mysql_result($result,0,"id");
$descr=xrf_mysql_result($result,0,"descr");
$pool_id=xrf_mysql_result($result,0,"pool_id");
$last_seen=xrf_mysql_result($result,0,"last_seen");
$last_ip_addr=xrf_mysql_result($result,0,"last_ip_addr");
$last_winver=xrf_mysql_result($result,0,"last_winver");
$user_agent=xrf_mysql_result($result,0,"user_agent");
$static=xrf_mysql_result($result,0,"static");

$kvquery="SELECT nkey, nvalue FROM y_nodekv WHERE descr = '$descr'";
$kvresult=mysqli_query($xrf_db, $kvquery);
$kvarray = array(); $drivearray = array(); $fixarray = array();
while ($row = mysqli_fetch_assoc($kvresult)) {
    $kvarray[$row['nkey']] = $row['nvalue'];
    if (str_starts_with($row['nkey'],"System_Drive_")) {
        $dletter = substr($row['nkey'],13,1);
        if (!in_array($dletter,$drivearray)) { $drivearray[] = $dletter; }
    }
    if (str_starts_with($row['nkey'],"Security_")) {
        $fixarray[] = $row['nkey'];
    }
}

echo "<b>$descr</b> (ID: $id)<p>";

echo "<table width=100%><tr><td width=50%>Hostname: " . @$kvarray['System_Hostname'] . "<br> <br>OS: " . @$kvarray['System_OSProductName'] . "<br>Build: $last_winver";

echo "<br> <br>Last Seen: $last_seen<br>Last IP Address: $last_ip_addr<br>User Agent: $user_agent";

if (isset($kvarray['HAController_Version'])) {
	echo "<br>HAC Version: HAController/" . $kvarray['HAController_Version'];
}

echo "<br>Node Pool: $pool_id</td><td width=50%>";

echo "Processor: " . @$kvarray['System_ProcessorName'] . "<br>Memory: " . @$kvarray['System_TotalPhysicalMemory'];
echo "<br> <br>System OEM: " . @$kvarray['System_SystemManufacturer'] . "<br>System Model: " . @$kvarray['System_SystemProductName'];
echo "<br>Mobo OEM: " . @$kvarray['System_BaseBoardManufacturer'] . "<br>Mobo Model: " . @$kvarray['System_BaseBoardProduct'];

echo "</td></tr></table>";

if (!empty($fixarray)) {
    echo "<p><b>Security Fixes</b></p>";

    foreach ($fixarray as $fix) {
        echo $fix . "&nbsp;" . $kvarray[$fix];
    }
}

if (!empty($drivearray)) {
    echo "<p><b>Drives</b></p>
    <table><tr><td width=30></td><td width=100><b>Label</b></td><td width=100><b>Type</b></td><td width=100 align=right><b>Free Space</b></td><td width=100 align=right><b>Total Size</b></td></tr>";

    foreach ($drivearray as $drive) {
        echo "<tr><td>" . $drive . "</td><td>" . @$kvarray['System_Drive_' . $drive . '_Label'] . "</td><td>" . @$kvarray['System_Drive_' . $drive . '_Type'] . "</td><td align=right>" . formatBytes(@$kvarray['System_Drive_' . $drive . '_TotalFreeSpace']) . "</td><td align=right>" . formatBytes(@$kvarray['System_Drive_' . $drive . '_TotalSize']) . "</td></tr>";
    }

    echo "</table>";
}

$softquery="SELECT * FROM y_nodesoftware WHERE descr = '$descr' ORDER BY appname ASC";
$softresult=mysqli_query($xrf_db, $softquery);

$num=mysqli_num_rows($softresult);

if ($num != 0) {
    echo "<p><b>Installed Software</b></p>";

    echo "<table><tr><td width=300><b>App Name</b></td><td width=180><b>Version</b><td width=220><b>Publisher</b></td><td width=120><b>Installed</b></td></tr>";
    $qq=0;
    while ($qq < $num) {
        $appname=xrf_mysql_result($softresult,$qq,"appname");
        $appver=xrf_mysql_result($softresult,$qq,"appver");
        $apppub=xrf_mysql_result($softresult,$qq,"apppub");
        $appdate=xrf_mysql_result($softresult,$qq,"appdate");

        echo "<tr><td><small>$appname</small></td><td><small>$appver</small></td><td><small>$apppub</small></td><td><small>$appdate</small></td></tr>";
        $qq++;
    }

    echo "</table>";
}

?>