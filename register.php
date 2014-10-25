<?php

require_once 'common.php';


$token = $db->escape_string($_GET['token']);
$name = $db->escape_string($_GET['name']);
$email = $db->escape_string($_GET['email']);
$phone = $db->escape_string($_GET['phone']);

$timestamp = date('Y-m-d G:i:s');
$sql = "UPDATE garage_invites SET name = '$name', email = '$email', phone = '$phone', registered = '$timestamp', visited = '$timestamp' WHERE token = '$token'";

good_query($sql);
$tokenValid = $db->affected_rows > 0;
if ($tokenValid) {
    $response = array('status' => 'success');
} else {
    $response = array('status' => 'error', 'message' => 'No invitation found', 'code' => 'NOTFOUND');
}

respond(json_encode($response));

$message = $name . ' <' . $email . '> ' . $phone . ' ' . $token . ' ' . $tokenValid;
$header = "From: Hilla Pyykkönen <hilla.pyykkonen@elisa.fi>\r\n";

//mail('alizbazar@gmail.com', 'Registered: ' . $name, $message, $header);

?>