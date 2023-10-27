<?php 
require 'functions.php';

if (isset($_GET['user_id']) || isset($_GET['pur_id'])) {
	
	if (isset($_GET['user_id'])) {
		$key="user_id";
		$id=$_GET['user_id'];	}


	if (isset($_GET['pur_id'])) {
		$key="pur_id";
		$id=$_GET['pur_id'];
	}

	
	searchPurchase($key,$id);
}else{
	http_response_code(400); 
	$response = ['message' => 'Parameter Missing'];
	echo json_encode($response);

}




?>