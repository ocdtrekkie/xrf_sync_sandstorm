<?php
if ($xrf_myulevel >= 3)
$cplink = "<a href=\"acp.php\">Control Panel</a>";

echo "</div><div class=\"footer\" align=\"center\">
<table width=\"100%\"><tr><td><font size=\"1\">&copy;2010-2025 Jacob Weisz</font></td><td align=\"right\"><font size=\"1\">$cplink</font></td></tr></table>
</div></body></html>";
mysqli_close($xrf_db);
?>