<?php
require_once 'common.php';

$token = $db->escape_string($_GET['token']);

$count = good_query_value("SELECT COUNT(*) FROM garage_invites WHERE registered IS NOT NULL");

if ($count > 315) {
	respond(json_encode(array('status' => 'error', 'message' => 'The event is full', 'code' => 'EVENTFULL')));
}

$invite = getInvite($token);

if (!$invite) {
    $response = array('status' => 'error', 'message' => 'No invitation found', 'code' => 'NOTFOUND');
} else {
    $response = array('status' => 'success', 'data' => $invite);

    $timestamp = date('Y-m-d G:i:s');
    $sql = "UPDATE garage_invites SET visited = '$timestamp' WHERE token = '$token'";
    good_query($sql);
}


respond(json_encode($response));




?>