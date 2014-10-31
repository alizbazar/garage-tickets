<?php

require_once('common.php');

$no = isset($_GET['no']) ? intval($_GET['no']) : 10;
if ($no <= 0) {
    $no = 10;
}

header('Content-type: text/plain');

for ($i=0; $i < $no; $i++) { 
    echo(generateToken() . "\n");
}

?>