<?php

require_once 'common.php';


$token = $db->escape_string($_GET['token']);
$name = $db->escape_string($_GET['name']);
$email = $db->escape_string($_GET['email']);
$phone = $db->escape_string($_GET['phone']);

logRequest('registration', 'token: ' . $token . '; email: ' . $email . '; name: ' . $name . '; phone: ' . $phone);

$timestamp = date('Y-m-d G:i:s');
$sql = "UPDATE garage_invites SET name = '$name', email = '$email', phone = '$phone', registered = '$timestamp', visited = '$timestamp' WHERE token = '$token'";

good_query($sql);
$tokenValid = $db->affected_rows > 0;
if ($tokenValid) {
    $response = array('status' => 'success');
} else {
    $response = array('status' => 'error', 'message' => 'No invitation found', 'code' => 'NOTFOUND');
}

$message = "Hi " . $name . "!

Thanks for registering for Elisa Garage Chillax that takes place on November 18 at 6-9pm.

The venue is conveniently located within a 5-minute walk from Messukeskus, at Ratavartijankatu 5.  

The program starts and food will be ready at 6pm so arrive early. 

We look forward to seeing you soon!

Hilla & Albert
Team behind Elisa X Slush 

PS. Should you have any questions, shoot us an email at info@elisaxslush.com .";

//$message = str_replace("\n", "\r\n", $message);

$header = "From: Elisa X Slush <info@elisaxslush.com>\r\n";

mail($email, 'Welcome to Elisa Garage Chillax', $message, $header);

respond(json_encode($response));

?>