<?php
require_once 'common.php';

$token = $db->escape_string($_GET['token']);



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