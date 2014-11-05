<?php
require_once 'common.php';

$invite = good_query_value("SELECT token FROM `garage_invites` WHERE referredBy = 'open' AND registered IS NULL AND (visited IS NULL OR visited < SUBTIME(NOW(), '00:15:00')) LIMIT 1");

if (!$invite) {
    header('Location: http://elisaxslush.com/garage/?event_full=1');
} else {
    header('Location: http://elisaxslush.com/garage/?t=' . $invite);
}


?>