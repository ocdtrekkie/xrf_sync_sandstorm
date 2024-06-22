<?php
require_once("/opt/app/includes/global.php");
$handled = false;
$access_key=mysqli_real_escape_string($xrf_db, $_GET['access_key']); //00accesskey
$message_type=mysqli_real_escape_string($xrf_db, $_GET['message_type']); //heartbeat, alert, command, message
$destination=mysqli_real_escape_string($xrf_db, $_GET['destination']); //server, broadcast, NODE
$message=mysqli_real_escape_string($xrf_db, $_GET['message'] ?? ''); //ciphertext
$user_agent=mysqli_real_escape_string($xrf_db, $_GET['user_agent']);
$new_ip_addr=mysqli_real_escape_string($xrf_db, $_GET['ip_address'] ?? '');
$new_winver=mysqli_real_escape_string($xrf_db, $_GET['windows_version'] ?? '');

$identifysender = mysqli_prepare($xrf_db, "SELECT pool_id, descr, static, last_ip_addr FROM y_nodes WHERE access_key=?");
mysqli_stmt_bind_param($identifysender, "s", $access_key);
mysqli_stmt_execute($identifysender);
mysqli_stmt_store_result($identifysender);
if (mysqli_stmt_num_rows($identifysender) == 1)
{
	mysqli_stmt_bind_result($identifysender, $senderpool_id, $descr, $static, $last_ip_addr);
	mysqli_stmt_fetch($identifysender);
	
	if (getenv("REMOTE_ADDR") != "127.0.0.1") { $new_ip_addr = getenv("REMOTE_ADDR"); }
	if ($new_ip_addr != $last_ip_addr && $static == 1) {
		// Static IP change detected on always-on node
		$logipchange = mysqli_prepare($xrf_db, "INSERT INTO g_log (uid, date, event) VALUES (?, NOW(), ?)");
		$logiptext = "Sync: Node " . $descr . " IP changed from " . $last_ip_addr . " to " . $new_ip_addr . ".";
		mysqli_stmt_bind_param($logipchange, "is", $xrf_myid, $logiptext);
		mysqli_stmt_execute($logipchange) or die(mysqli_error($xrf_db));
		}
	
	$updatenode = mysqli_prepare($xrf_db, "UPDATE y_nodes SET last_seen = NOW(), last_ip_addr = ?, last_winver = ?, user_agent = ? WHERE access_key = ?");
	if ($user_agent == "") { $user_agent = mysqli_real_escape_string($xrf_db, $_SERVER['HTTP_USER_AGENT']); }
	mysqli_stmt_bind_param($updatenode, "ssss", $new_ip_addr, $new_winver, $user_agent, $access_key);
	mysqli_stmt_execute($updatenode) or die(mysqli_error($xrf_db));
	
	if ($message_type == "fetch" && $destination == "server") {
		// This is a request for unread messages
		$getmessages = mysqli_prepare($xrf_db, "SELECT * FROM y_messages WHERE dest=? AND recv=0");
		mysqli_stmt_bind_param($getmessages, "s", $descr);
		mysqli_stmt_execute($getmessages) or die(mysqli_error($xrf_db));
		$messageresult = mysqli_stmt_get_result($getmessages);
		$responsearray = $messageresult->fetch_all(MYSQLI_ASSOC);
		http_response_code(200); echo json_encode($responsearray);
		// Mark the messages as read
		$messagesrecv = mysqli_prepare($xrf_db, "UPDATE y_messages SET recv=1 WHERE dest=?");
		mysqli_stmt_bind_param($messagesrecv, "s", $descr);
		mysqli_stmt_execute($messagesrecv) or die(mysqli_error($xrf_db));
		$handled = true;
	}
	
	if ($message_type == "heartbeat" && $destination == "server") {
		// This is a heartbeat, all heartbeats should go to the server
		$getmessagecount = mysqli_prepare($xrf_db, "SELECT id FROM y_messages WHERE dest=? AND recv=0");
		mysqli_stmt_bind_param($getmessagecount, "s", $descr);
		mysqli_stmt_execute($getmessagecount) or die(mysqli_error($xrf_db));
		mysqli_stmt_store_result($getmessagecount);
		$messageswaiting = mysqli_stmt_num_rows($getmessagecount);
		http_response_code(200); echo $messageswaiting . " unread message(s).";
		$handled = true;
	}
	
	if ($message_type == "nodedata" && $destination == "server" && $message = "newsoftware") {
		// This is a node updating it's installed software list
		$requestBody = file_get_contents('php://input');
		$softwareData = json_decode($requestBody);
		foreach ($softwareData->newsoftware as $software) {
			$appname = $software->Name;
			$appver = $software->Version;
			$apppub = $software->Publisher;
			$appdate = $software->InstallDate;
			$storeapps = mysqli_prepare($xrf_db, "INSERT INTO y_nodesoftware (descr, appname, appver, apppub, appdate) VALUES (?, ?, ?, ?, ?)");
			mysqli_stmt_bind_param($storeapps, "sssss", $descr, $appname, $appver, $apppub, $appdate);
			mysqli_stmt_execute($storeapps) or die (mysqli_error($xrf_db));
		}
		http_response_code(200); echo "[]";
		$handled = true;
	}
	
	if ($message_type == "message" && $destination != "server" && $destination != "broadcast") {
		// This is a message to another node
		$identifyrecvr = mysqli_prepare($xrf_db, "SELECT pool_id FROM y_nodes WHERE descr=?");
		mysqli_stmt_bind_param($identifyrecvr, "s", $destination);
		mysqli_stmt_execute($identifyrecvr);
		mysqli_stmt_store_result($identifyrecvr);
		if (mysqli_stmt_num_rows($identifyrecvr) == 1)
		{
			mysqli_stmt_bind_result($identifyrecvr, $recvrpool_id);
			mysqli_stmt_fetch($identifyrecvr);
			if ($recvrpool_id == $senderpool_id) {
				$storemessage = mysqli_prepare($xrf_db, "INSERT INTO y_messages (source, dest, sent, mesg) VALUES (?, ?, NOW(), ?)");
				mysqli_stmt_bind_param($storemessage, "sss", $descr, $destination, $message);
				mysqli_stmt_execute($storemessage) or die (mysqli_error($xrf_db));
				http_response_code(202); echo "Message queued for delivery.";
			} else { http_response_code(403); echo "Unauthorized inter-pool communication."; }
		} else { http_response_code(404); echo "Unknown destination."; }
		$handled = true;
	}
	
	if ($handled == false) {
		http_response_code(501); echo "Unknown message path.";
	}
}
else { http_response_code(401); echo "Access denied."; }

mysqli_close($xrf_db);
?>
