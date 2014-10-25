<?php

require_once 'common.php';


$token = $db->escape_string($_GET['token']);
$email = $db->escape_string($_GET['email']);



$invite = getInvite($token);

if ($invite && $invite['freeInvites'] > 0) {
    $newToken = generateToken();
    $sql = "INSERT INTO garage_invites SET token = '$newToken', email = '$email', referredBy = '$token'";
    if (!good_query($sql)) {
        // TODO: person might have already been invited -> inform that X is coming & inform X if he registers 
        respond(json_encode(array('status' => 'error', 'message' => 'Invitation didn\'t go through')));
    } else {
        good_query("UPDATE garage_invites SET freeInvites = (freeInvites - 1) WHERE token = '$token'");

        $name = $invite['name'];
        // TODO: Creaft correct message
        $message = 'John would like to invite you!';
        // TODO: Sender address
        $header = "From:  <hilla.pyykkonen@elisa.fi>\r\n";

        mail('alizbazar@gmail.com', 'Registered: ' . $name, $message, $header);


        respond(json_encode(array('status' => 'success')));
    }
} else {
    respond(json_encode(array('status' => 'error', 'message' => 'Token not found or no free invites available')));
}

?>