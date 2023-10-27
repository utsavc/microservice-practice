<?php
require 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$user = login($username,$password);

	if ($user) {
		$token = generateJWT($user);		
		header('Content-Type: application/json');
		echo json_encode(['token' => $token,'message'=> 'login success']);
	} else {
		http_response_code(401); 
		echo json_encode(['message' => 'Invalid username']);
		exit;
	}
}


?>