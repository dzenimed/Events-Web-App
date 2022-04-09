<?php
// echo 'hello';
require_once dirname(__FILE__).'/vendor/autoload.php';


// FLight::route('/', function(){
//
//   echo 'This is my first route.';
//
// });


$servername = 'localhost';
$schema = 'events_db';
$username = 'events';
$password = 'events123';

try {
	$conn = new PDO("mysql:host=$servername;dbname=$schema", $username, $password);

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected to the $db database successfully!";
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

// Flight::route('/hello', function () {
//     echo 'hello world!';
// });
//
// Flight::start();
?>
