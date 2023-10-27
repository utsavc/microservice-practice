<?php
require 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['item_id']) || !isset($_POST['seller_ip']) || !isset($_POST['quantity'])) {
       http_response_code(400); 
       $response = ['message' => 'Data is missing'];
       echo json_encode($response);
       exit;
   } else {

    if (!isset($_SERVER['HTTP_COOKIE'])) {
        http_response_code(401); 
        $response = ['message' => 'Token Missing'];
        echo json_encode($response);
        exit;
    }


    $authToken=tokenRetriever();

    echo "Hello";
    exit;



    if ($authToken != null) {
        $token = $authToken;
        $decoded = verifyJWT($token);

        if ($decoded === null) {
            http_response_code(401);
            echo json_encode(['message' => 'Token is not valid']);
            exit;
        } elseif ($decoded->exp < time()) {
            http_response_code(401);
            echo json_encode(['message' => 'Token has expired']);
            exit;
        }


        $user_id = $decoded->user_id;
        $item_id = $_POST['item_id'];
        $seller_ip = $_POST['seller_ip'];
        $quantity = $_POST['quantity'];


        $response = [];


        if ($quantity<0 || !preg_match('/^[0-9]+(\.[0-9]+)?$/', $item_id)) {
            if($quantity<0){
                $response['quantity']="Invalid Quantity";
            }

            if(!preg_match('/^[0-9]+(\.[0-9]+)?$/', $item_id)){$response['item_id']="Invalid Item";}
            http_response_code(400);
            echo json_encode($response);
            exit;
        }



        $userEndpoint="http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/market/user.php?user_id=".$user_id;
        
        $data=makeCall($userEndpoint);
        $balance=$data['balance']; 

        $urlEndpoint="http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/".$seller_ip."/itemlist.php";


        $data=makeCall($urlEndpoint);

        foreach ($data as $item) {
            if ($item['item_id'] == $item_id) {
                $matchedItem = $item;
                break; 
            }
        }

        $price=$matchedItem['price_of_unit'];
        $availableQuantity=$matchedItem['stock_qty'];
        $totalPrice=$quantity*$price;


        if ($totalPrice > $balance) {
            $response['balanceError'] = 'Insufficient balance';
        }


        if ($availableQuantity < $quantity) {
            $response['quantityError'] = 'Insufficient quantity';
        }

        if (!empty($response)) {
            http_response_code(400);
            echo json_encode($response);
        } else {
            makePurchase($user_id,$item_id,$quantity,$totalPrice,$seller_ip);
        }

    }else{
     http_response_code(401); 
     $response = ['message' => 'Token Missing'];
     echo json_encode($response);
     exit;
 }
}
}else{
    echo "Bye";
}










?>
