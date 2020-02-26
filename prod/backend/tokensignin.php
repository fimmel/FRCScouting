<?php
require_once '../vendor/autoload.php';
include('db.php');

$id_token = $_POST['idtoken'];
// Get $id_token via HTTPS POST.

$client = new Google_Client(['839195140874-8o19v9ttp9f0deulgocmtuqligqc5u4n.apps.googleusercontent.com' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
	$sql = "SELECT * FROM `scout` WHERE `id` = :id"; 
	$result = $db->prepare($sql);
	$result->bindValue(":id", $payload['sub']);
	$result->execute(); 
	$scouts = $result->fetchAll();
	if ($result->rowCount() > 0) {
	  	foreach($scouts as $scout){
			//print_r($scout);
			echo $scout['permission'];
		}
	} else {
		echo 'Not In DB, adding';
		$sqli = "INSERT INTO `scout` (`id`, `email`, `name`, `permission`, `picture`)
						VALUES (:id, :email, :name, :permission, :picture);
						";
		$statementi = $db->prepare($sqli);
		$statementi->bindValue(":id",  $payload['sub']);
		$statementi->bindValue(":email",   $payload['email']);
		$statementi->bindValue(":name",   $payload['name']);
		$statementi->bindValue(":permission",   0);
		$statementi->bindValue(":picture", $payload['picture']);
		$count = $statementi->execute();
		
	}
	
	
	
	$userid = $payload['sub'];
	$email = $payload['email'];
	$name = $payload['name'];
	$picture = $payload['picture'];
	
	
	//echo $name;
} else {
	//Invalid User
  echo "Invalid";
}

?>