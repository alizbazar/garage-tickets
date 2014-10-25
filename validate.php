<?php
require_once 'common.php';

$token = $db->escape_string($_GET['token']);



$invite = getInvite($token);

if (!$invite) {
    $response = array('status' => 'error', 'message' => 'No invitation found', 'code' => 'NOTFOUND');
} else {
    $response = array('status' => 'success', 'data' => $invite);
}


respond(json_encode($response));




?>