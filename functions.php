<?php

function getInvite($token) {
    return good_query_assoc("SELECT name, email, freeInvites, registered FROM `garage_invites` WHERE token = '$token'");
}

function validateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function formatPhone($phone) {
    $phone = str_replace(' ', '', $phone);
    $phone = str_replace('-', '', $phone);
    $phone = str_replace('(', '', $phone);
    $phone = str_replace(')', '', $phone);
    if (stripos($phone, '00') === 0) {
        $phone = '+' . substr($phone, 2);
    }
    if (stripos($phone, '358') === 0) {
        $phone = '+' . $phone;
    }
    return $phone;
}


function generateToken() {
    $length = 7;
    $inviteCode = "";
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $max = strlen($characters) - 1;
    for ($p = 0; $p < $length; $p++) {
        $inviteCode .= $characters[mt_rand(0, $max)];
    }
    return $inviteCode;
}

?>