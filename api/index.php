<?php
$path = $_SERVER['REQUEST_URI'];
if($path === '/phpinfo') {
require 'app.php';
}
elseif ($path === '/home') {

require 'home.php';
}
elseif ($path === '/csv-json') {
 require 'excel-json.php';
}
elseif($path === '/dbview'){
 require 'db-viewer.php';
}
elseif($path === '/dashboard'){
  require 'dashboard.php';
}
elseif($path === '/random-user'){
  require 'randomuser.php';
}

else {
 require '404.php';
}

?>
