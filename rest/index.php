<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once __DIR__.'/services/UserService.class.php';
require_once __DIR__.'/services/CompanyService.class.php';
require_once __DIR__.'/services/EventService.class.php';
require_once __DIR__.'/services/EventTypeService.class.php';
require_once __DIR__.'/services/ReservationService.class.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/dao/UserDao.class.php';
require_once __DIR__.'/dao/CompanyDao.class.php';
require_once __DIR__.'/dao/EventDao.class.php';
require_once __DIR__.'/dao/EventTypeDao.class.php';
require_once __DIR__.'/dao/ReservationDao.class.php';

Flight::register('userDao', 'UserDao');
Flight::register('companyDao', 'CompanyDao');
Flight::register('eventDao', 'EventDao');
Flight::register('eventTypeDao', 'EventTypeDao');
Flight::register('reservationDao', 'ReservationDao');

Flight::register('userService', 'UserService');
Flight::register('companyService', 'CompanyService');
Flight::register('eventService', 'EventService');
Flight::register('eventTypeService', 'EventTypeService');
Flight::register('reservationService', 'ReservationService');


Flight::map('error', function(Exception $ex){
    // Handle error
    Flight::json(['message' => $ex->getMessage()], 500);
});

/* utility function for reading query parameters from URL */
Flight::map('query', function($name, $default_value = NULL){
  $request = Flight::request();
  $query_param = @$request->query->getData()[$name];
  $query_param = $query_param ? $query_param : $default_value;
  return urldecode($query_param);
});

/* REST API documentation endpoint */
Flight::route('GET /docs.json', function(){
  $openapi = \OpenApi\scan(['routes']);
  header('Content-Type: application/json');
  echo $openapi->toJson();
});

/* utility function for generating JWT token */
Flight::map('jwt', function ($user) {
    $jwt = \Firebase\JWT\JWT::encode(["exp" => (time() + Config::JWT_TOKEN_TIME), "id" => $user["id"], "r" => $user["role"]], Config::JWT_SECRET, 'HS256');
    return ["token" => $jwt];
});

// middleware method
Flight::route('/*', function(){
 //perform JWT decode
   $path = Flight::request()->url;
   if ($path == '/login' || $path == '/register' || $path == '/docs.json' || $path == '/events' || str_contains($path, '/event' ) || str_contains($path, '/events' )){
     return TRUE;
   }
  // if ($path == '/admin/add/event'){
  //   $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));    '%', $id, '%'
  //   if ($user['r'] != "ADMIN"){
  //       Flight::set('user', $decoded);
  //       return TRUE;
  //   }
  // }
  $headers = getallheaders();
  if (@!$headers['Authorization']){
    Flight::json(["message" => "Authorization is missing"], 403);
    return FALSE;
  }else{
    try {
      $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
      Flight::set('user', $decoded);
      return TRUE;
    } catch (\Exception $e) {
      Flight::json(["message" => "Authorization token is not valid"], 403);
      return FALSE;
    }
  }
});

//   exclude login route from middleware str_contains($path, '')
   // if ($path == '/login' || $path == '/register' || $path == '/docs.json'){
   //   return TRUE;
   // }

   // if ( str_contains($path, '/login') || str_contains($path, '/register') || str_contains($path, '/docs.json') || str_contains($path, '/events') || str_contains($path, '/event/@id')){
   //   return TRUE;
   // }

require_once __DIR__.'/routes/UserRoutes.php';
require_once __DIR__.'/routes/CompanyRoutes.php';
require_once __DIR__.'/routes/EventTypeRoutes.php';
require_once __DIR__.'/routes/EventRoutes.php';
require_once __DIR__.'/routes/ReservationRoutes.php';

Flight::start();
?>
