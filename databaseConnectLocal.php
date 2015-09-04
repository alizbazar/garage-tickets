<?php
//Connection Data

  $user = 'root';
  $password = 'root';
  $db_host = 'localhost';
  $db_name = 'vntradeo_vntrade';
	
  $db = new mysqli($db_host, $user, $password, $db_name);
  if ($db->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']');
  }
  
?>