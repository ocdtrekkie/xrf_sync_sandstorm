<?php
require("ismodule.php");

echo "<p><a href=\"acp_module_panel.php?modfolder=$modfolder&modpanel=nodelist\">View Sync Nodes</a><br>
<a href=\"acp_module_panel.php?modfolder=$modfolder&modpanel=addnode\">Add New Sync Node</a><br>
<a href=\"acp_module_panel.php?modfolder=$modfolder&modpanel=regenkey\">Rekey Sync Node</a></p>";

?>