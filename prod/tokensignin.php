<?php
require_once 'vendor/autoload.php';


$id_token = $_POST['idtoken'];
// Get $id_token via HTTPS POST.

$client = new Google_Client(['839195140874-8o19v9ttp9f0deulgocmtuqligqc5u4n.apps.googleusercontent.com' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  $userid = $payload['sub'];
  // If request specified a G Suite domain:
  //$domain = $payload['hd'];
	
	$email = $payload['email'];
	$name = $payload['name'];
	$picture = $payload['picture'];
	
	
	echo $name.test;
} else {
  echo "Invalid";
}

?>