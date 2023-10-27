<?php 
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



$secretKey = 'K-I-T-5-1-4-ASSIGNMENT-2'; 

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'market';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


function generateJWT($user) {
    global $secretKey; // Define $secretKey as a global variable
    $payload = [
    	'user_id' => $user['user_id'],
    	'exp' => time() + 300,
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}



function verifyJWT($token) {
	global $secretKey;
	
	try {
		$decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
		return $decoded;
	} catch (Exception $e) {
		http_response_code(401); 
		$response = ['message' => 'Unauthorized Token'];
		echo json_encode($response);
		exit;
		return null;
	}
}



function login($username, $password) {
	global $conn;

	$username = $conn->real_escape_string($username);
	$password = $conn->real_escape_string($password);

    // Construct the SQL query to select the user data
	$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
	$result = $conn->query($sql);

	if ($result && $result->num_rows > 0) {
		$userData = $result->fetch_assoc();
		return $userData;
	} else {
        return null; // User not found in the database
    }

    $conn->close();
}


function cookieRefresher($cookie){
	setcookie("auth_token",$cookie, time() + 300, "/");
}





function makePurchase($user_id,$item_id,$quantity,$totalPrice,$seller_ip){
	global $conn;
	$date = date('Y-m-d');

	$sql = "INSERT INTO purchase (user_id, item_id, quantity, price, seller_id, date) VALUES ('$user_id', '$item_id', $quantity, $totalPrice, '$seller_ip', '$date')";

	$updateBalanceQuery = "UPDATE user SET balance = balance - $totalPrice WHERE user_id = $user_id";

	$conn->begin_transaction();
	if ($conn->query($updateBalanceQuery) === TRUE && $conn->query($sql) === TRUE) {
		$pur_id = $conn->insert_id;
		$conn->commit(); 
		http_response_code(200);
		$response = ['message' => 'Successful purchase', 'pur_id' => $pur_id];
		echo json_encode($response);
	} else {
		$conn->rollback(); 
		http_response_code(500); 
		$response['message'] = 'Error inserting into the database: ' . $conn->error;
		echo json_encode($response);
	}


	$conn->close();

}



function makeCall($url){
	$client = new Client();
	try {
		$response = $client->request('GET', $url);

        //Check the response status code.
		if ($response->getStatusCode() === 200) {
			$data = json_decode($response->getBody(), true);

			if ($data !== null) {
				return $data;
			} else {
				return null;
			}
		} else {
			echo 'HTTP Error: ' . $response->getStatusCode();
		}
	} catch (GuzzleHttp\Exception\RequestException $e) {
		echo 'Request Exception: ' . $e->getMessage();
	}
}



function searchPurchase($searchKey,$searchValue) {
	global $conn;

    // Define the SQL query based on the search key
	if ($searchKey === 'pur_id') {
		$sql = "SELECT * FROM `purchase` WHERE pur_id = $searchValue";
	} elseif ($searchKey === 'user_id') {
		$sql = "SELECT * FROM `purchase` WHERE user_id = '$searchValue'";
	} else {
        // Invalid search key
        http_response_code(400); // Bad Request
        $response = ['message' => 'Invalid search key'];
        echo json_encode($response);
        exit;
    }

    $result = $conn->query($sql);

    if ($result) {
    	if ($result->num_rows > 0) {

    		$previousPurchases = [];
    		while ($row = $result->fetch_assoc()) {
    			$previousPurchases[] = $row;
    		}
    		http_response_code(200);
    		echo json_encode($previousPurchases);
    	} else {
    		http_response_code(200);
    		$response = ['message' => 'No previous purchases found','record'=>false];
    		echo json_encode($response);
    	}
    } else {
        // Database query error
        http_response_code(500); // Internal Server Error
        $response = ['message' => 'Error querying the database: ' . $conn->error];
        echo json_encode($response);
    }

    $conn->close();
}




function cancelPurchase($pur_id) {
	global $conn;
	$userEndpoint="http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/market/search_purchase.php?pur_id=".$pur_id;
	
	$data=makeCall($userEndpoint);
	if ($data===null) {
		http_response_code(200);
		$response = ['message'=>'Purchase ID doesn not exist'];
		echo json_encode($response);
		exit;
	}

	http_response_code(200);
	$response = ['search_result'=>$data];
	echo json_encode($response);
	
	$deleteSql = "DELETE FROM `purchase` WHERE pur_id = $pur_id";
	$result = $conn->query($deleteSql);

	if ($result === TRUE) {
		$affectedRows = $conn->affected_rows;

		if ($affectedRows > 0) {
			http_response_code(200);
			$response = ['message' => 'Purchase canceled successfully'];
			echo json_encode($response);
		} else {
			http_response_code(200);
			$response = ['message' => 'Purchase not found'];
			echo json_encode($response);
			exit;
		}
	} else {
		http_response_code(500);
		$response = ['message' => 'Error canceling the purchase: ' . $conn->error];
		echo json_encode($response);
	}



	$conn->close();
}



function addBalance($user_id, $amount) {
	global $conn;

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$user_id = (int)$user_id;
	$amount = (float)$amount;

	$sql = "UPDATE user SET balance = balance+$amount WHERE user_id = $user_id";

	if ($conn->query($sql) === TRUE) {
		$amount;
		$selectSql = "SELECT balance FROM user WHERE user_id = $user_id";
		$result = $conn->query($selectSql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$amount = $row['balance'];
		}
		http_response_code(200);
		$response = ['message' => 'Balance Added', 'balance' => $amount];
		echo json_encode($response);
	} else {
		http_response_code(500);
		$response['message'] = 'Error updating the database: ' . $conn->error;
		echo json_encode($response);
	}

	$conn->close();
}


function tokenRetriever(){
	$authToken=null;
	$receivedCookieHeader = $_SERVER['HTTP_COOKIE']; 
	$cookieParts = explode('; ', $receivedCookieHeader); 
	$authToken = null;
	foreach ($cookieParts as $cookiePart) {
		if (strpos($cookiePart, 'auth_token=') === 0) {
			$authToken = substr($cookiePart, strlen('auth_token='));
			break;
		}
	}

	return $authToken;
}




function verifyUserById($user_id) {
	global $conn;

	$sql = "SELECT EXISTS (SELECT 1 FROM user WHERE user_id = '$user_id') AS user_exists";
	$result = $conn->query($sql);

	if ($result) {
		$row = $result->fetch_assoc();
		$status = (bool) $row['user_exists'];

		if ($status) {
			return true;
		} else {

			return false;
		}
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
}





?>