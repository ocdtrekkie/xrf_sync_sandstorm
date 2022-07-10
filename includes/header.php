<?php
header('Content-Type: text/html; charset=iso-8859-15');
if ($xrf_mystylepref == "") {$xrf_style = $xrf_style_default;}
else {$xrf_style = $xrf_mystylepref;}
echo "<html><head><title>$xrf_site_name</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/$xrf_style/style.css\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/print/style.css\" media=\"print\" />
</head><body>";

echo "<div class=\"header\" align=\"center\">
<table width=\"100%\"><tr><td>
<p align=\"left\">
<font size=\"6\">$xrf_site_name</font><br>
<span class=\"navigation-box\">$xrf_myusername ($xrf_myemail)</span>
</p>
</td><td>
<p align=\"right\" class=\"navigation-box\"><a href=\"index.php\">Home</a></p>
</td></tr></table>
</div>

<div class=\"container\" align=\"center\">";
?>
