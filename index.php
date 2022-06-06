<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/vendor/autoload.php';
//require_once dirname(__FILE__).'/dao/BaseDao.class.php';
Flight::set('flight.log_errors', TRUE);

Flight::route('/hello', function () {
    echo 'hello world!';
});

$servername = 'localhost';
$schema = 'events_db';
$username = 'events';
$password = 'events123';

try {
	$conn = new PDO("mysql:host=$servername;dbname=$schema", $username, $password);

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected to the database successfully!";

    $stmt = $conn->prepare("SELECT * FROM company");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($result);

} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}


Flight::start();
?>
