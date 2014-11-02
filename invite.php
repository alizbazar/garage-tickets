<?php

require_once 'common.php';


$token = $db->escape_string($_GET['token']);
$email = $db->escape_string($_GET['email']);

logRequest('invite', 'token: ' . $token . '; email: ' . $email);

$invite = getInvite($token);

if ($invite && $invite['freeInvites'] > 0) {
    $newToken = generateToken();
    $sql = "INSERT INTO garage_invites SET token = '$newToken', email = '$email', referredBy = '$token'";
    if (!good_query($sql)) {
        $error = $db->error;
        // Check if error is only due to duplicate email (person already invited)
        if (stripos($error, "Duplicate") !== FALSE && stripos($error, "'email'") !== FALSE) {
            $newToken = good_query_value("SELECT token FROM garage_invites WHERE email = '$email'");
            if (empty($newToken)) {
                respond(json_encode(array('status' => 'error', 'message' => "Invitation wasn't found")));
            }
        } else {
            respond(json_encode(array('status' => 'error', 'message' => 'Invitation didn\'t go through. Please try again.')));
        }
    } else {
        good_query("UPDATE garage_invites SET freeInvites = (freeInvites - 1) WHERE token = '$token'");
    }

        $name = $invite['name'];
        $link = 'http://elisaxslush.com/garage/?t=' . $newToken;

        $message .= "Hi!\n\n" . $name . " just registered for Elisa Garage Chillax and invites you to join the fun, too.  

**Elisa Garage Chillax takes place on the first day of Slush, November 18th at 6-9pm**, in our garage (Ratavartijankatu 5, Helsinki), just a 5-minute walk from the Slush venue.

Our garage space is huge but has a guest limit! So sign up with your personal link right now: " . $link . " .

The program includes free food & drinks, music, dev comps, playing with facial recognition, and relaxing in big couches. 

For the Slush participants, the night will continue at the official Slush after party in Messukeskus.

We look forward to having you here,

Hilla & Albert
Team behind Elisa X Slush


--
www.elisa.com
You received this message because somebody sent an invite to your email.
[" . date('Y-m-d G:i:s') . "]\n";

    //  $message = str_replace("\n", "\r\n", $message);

    $header = "From: Elisa X Slush <info@elisaxslush.com>\r\n";
    $header .= "Content-Type: text/plain;charset=utf-8\r\n";

    mail($email, $name . ' invites you to Elisa Garage Chillax on Nov 18 6-9pm', $message, $header);


    respond(json_encode(array('status' => 'success')));
} else {
    respond(json_encode(array('status' => 'error', 'message' => 'Token not found or no free invites available')));
}

?>