<?php 
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$secretKey = 'K-I-T-5-1-4-ASSIGNMENT-2';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$card_number = $_POST['card_number'];
	$pin_number = $_POST['pin_number'];
	validate($card_number,$pin_number);
}
function validate($card_number,$pin_number){


	if (!isset($_SERVER['HTTP_COOKIE'])) {
		http_response_code(401); 
		$response = ['message' => 'Token Missing'];
		echo json_encode($response);
		exit;
	}

	$receivedCookieHeader = $_SERVER['HTTP_COOKIE']; 
	$cookieParts = explode('; ', $receivedCookieHeader); 
	$authToken = null;
	foreach ($cookieParts as $cookiePart) {
		if (strpos($cookiePart, 'auth_token=') === 0) {
			$authToken = substr($cookiePart, strlen('auth_token='));
			break;
		}
	}


	$decoded=verifyJWT($authToken);

	verifyUserCard($decoded->user_id,$card_number,$pin_number);
}


function verifyUserCard($user_id,$card_number,$pin_number) {
	$servername = 'localhost';
	$dbUsername = 'root';
	$dbPassword = '';
	$database = 'bank';

	$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

	if ($conn->connect_error) {
		die('Connection failed: ' . $conn->connect_error);
	}


	$sql = "SELECT EXISTS (SELECT 1 FROM card WHERE user_id = '$user_id' and card_num='$card_number' and pin='$pin_number') AS user_exists";

	$result = $conn->query($sql);

	if ($result) {
		$row = $result->fetch_assoc();
		$status = (bool) $row['user_exists'];

		if ($status) {
            http_response_code(200); // Success
            $response = ['user_exist' => $status];
        } else {
            http_response_code(404); // Not Found
            $response = ['user_exist' => $status];
        }

        echo json_encode($response);
    } else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}



function verifyJWT($token) {
	global $secretKey;

	try {
		$decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
		return $decoded;
	} catch (Exception $e) {
		return null;
	}
}


?>