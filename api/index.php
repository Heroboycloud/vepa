<?php
$path = $_SERVER['REQUEST_URI'];
if($path === '/app') {
require 'app.php';
}
elseif ($path === '/home') {

require 'home.php';
}
elseif ($path === '/csv-json') {
 require 'csv-json.php';
}
else {
 require '404.php';
}

?>
