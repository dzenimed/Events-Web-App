<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../vendor/autoload.php';
//require_once dirname(__FILE__).'/dao/BaseDao.class.php';
Flight::set('flight.log_errors', TRUE);

// 
// Flight::route('/hello', function () {
//     echo 'hello world!';
// });


/* REST API documentation endpoint */
Flight::route('GET /docs.json', function(){
  $openapi = \OpenApi\scan(['routes']);
  header('Content-Type: application/json');
  echo $openapi->toJson();
});

Flight::start();
?>
