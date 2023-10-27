<?php 
require 'functions.php';

if (isset($_GET['pur_id'])) {
	$pur_id=$_GET['pur_id'];
	
	$authToken=tokenRetriever();

	if ($authToken != null) {

		$decoded=verifyJWT($authToken);
		
		if ($decoded === null) {
			http_response_code(498);
			echo json_encode(['message' => 'Invalid Token']);
			exit;
		} elseif ($decoded->exp < time()) {
			http_response_code(498);
			echo json_encode(['message' => 'Token has expired']);
			exit;
		}

		$userId=$decoded->user_id;
		$exists=verifyUserById($userId);

		if ($exists) {
			cancelPurchase($pur_id);
		}else{
			http_response_code(401);
			echo json_encode(['message' => 'Unauthorized Access']);
			exit;
		}
	}

}else{
	http_response_code(400);
	$response = ['message' => 'pur_id is missing in the request'];
	echo json_encode($response);
}
?>


