<?php
require 'vendor/autoload.php';
require 'functions.php';
use GuzzleHttp\Client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['amount'])) {
        http_response_code(400); // Bad Request
        $response = ['message' => 'Data is missing'];
        echo json_encode($response);
        exit;
    }

    if (!isset($_SERVER['HTTP_COOKIE'])) {
        http_response_code(401); 
        $response = ['message' => 'Token Missing'];
        echo json_encode($response);
        exit;
    }

    $amount = $_POST['amount'];
    $authToken=tokenRetriever();
    $headers = ['Cookie' => 'auth_token=' . $authToken];
    $api_url="http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/bank/validate_card.php";


    if ($authToken != null) {
        $client = new Client();
        try {
            
            $response = $client->post($api_url, [
                'headers' => $headers, 
            ]);

            $responseBody = $response->getBody()->getContents();            
            if ($responseBody) {
                $responseData = json_decode($responseBody, true);

                if ($responseData !== null) {
                    $userExist = $responseData['user_exist'];
                    if ($userExist) {
                        $decoded=verifyJWT($authToken);
                        $userId = $decoded->user_id;

                        $balance = $amount;
                        addBalance($userId, $balance);
                        http_response_code(200);
                        echo json_encode(['message' => 'Balance added successfully']);
                        
                    }else{
                        http_response_code(401);
                        $response = ['message' => 'Unauthorized Access'];
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    echo 'Error decoding JSON response.';
                }
            } else {
                echo 'Empty response body.';
            }

        } catch (Exception $e) {
            echo 'Guzzle error: ' . $e->getMessage();
        }
    }
}
?>
