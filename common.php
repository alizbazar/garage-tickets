<?php
/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - http://www.w3.org/TR/cors/
 *
 *  source: http://stackoverflow.com/a/9866124/528675
 */
function cors() {
    global $_SERVER;
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // TODO: check header against a few cases
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}

function respond($data) {
  global $_GET;
  header('Content-type: application/json');
  if (isset($_GET['callback']) && strpos($_GET['callback'], '(') === FALSE) {
      echo($_GET['callback'] . '(' . $data . ');');
  } else {
      cors();
      echo($data);
  }
  die;
}



if (strpos($_SERVER['HTTP_HOST'], 'local') === FALSE) {
  require_once ('/home/vntradeo/alizweb/includes/databaseConnect.php');
} else {
  require_once ('../includes/databaseConnect.php');
}
//mysqli_select_db('vntradeo_vntrade');



require_once ('good-query.php');
require_once ('functions.php');
?>
