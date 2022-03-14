<?php
// echo 'hello';
require_once dirname(__FILE__).'/vendor/autoload.php';


FLight::route('/', function(){

  echo 'First route';
});

Flight::start();
?>
