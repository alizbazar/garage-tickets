<?php

require_once 'common.php';


$token = $db->escape_string($_GET['token']);
$email = $db->escape_string($_GET['email']);

logRequest('invite', 'token: ' . $token . '; email: ' . $email);

$invite = getInvite($token);

// TODO: make sure only 

if ($invite && $invite['freeInvites'] > 0) {
    $newToken = generateToken();
    $sql = "INSERT INTO garage_invites SET token = '$newToken', email = '$email', referredBy = '$token'";
    if (!good_query($sql)) {
        // TODO: person might have already been invited -> inform that X is coming & inform X if he registers 
        respond(json_encode(array('status' => 'error', 'message' => 'Invitation didn\'t go through')));
    } else {
        good_query("UPDATE garage_invites SET freeInvites = (freeInvites - 1) WHERE token = '$token'");

        $name = $invite['name'];

        $message .= "Hi!\n\n" . $name . " just registered for Elisa Garage Chillax and invites you to join the fun, too.  

Elisa Garage Chillax takes place on the first day of Slush, November 18th at 6-9pm on Ratavartijankatu 5, Helsinki.

Yes, we will be hosting you in our garage. And this time you won't see any businessmen in black suits hurrying to their cars. You'll see disco balls, soft lights, and people enjoying this slushy November night.

Sign up with your personal link right now. Our garage space is huge but has a guest limit. 

The program includes free food & drinks, dev comps, playing with facial recognition, and relaxing in big couches. 

For the Slush participants, the night will continue at the official Slush after party in Messukeskus which is conveniently located within 600 meters from our headquarters.

We look forward to having you here,

Hilla & Albert
Team behind Elisa X Slush

";

        $message = str_replace("\n", "\r\n", $message);

        // TODO: Sender address
        $header = "From: Elisa X Slush <info@elisaxslush.com>\r\n";

        mail($email, $name . ' invites you to Elisa Garage Chillax on 18.11.', $message, $header);


        respond(json_encode(array('status' => 'success')));
    }
} else {
    respond(json_encode(array('status' => 'error', 'message' => 'Token not found or no free invites available')));
}

?>