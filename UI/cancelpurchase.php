<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cancel Purchase</title>
	<!-- Add Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>
<body style="background: skyblue;">
	<div class="container bg-white p-5 mt-5">
		<div class="row">
			<div class="col-md-6">
				<form method="get" action="" autocomplete="off" id="searchForm">

					<div id="purchaseIdInput" class="mb-3" >
						<label for="username" class="form-label">Enter Purchase Id to delete</label>
						<input name="purchaseId" type="text" class="form-control" id="purchaseId">
					</div>

					<button type="submit" class="btn btn-primary" id="searchButton">Search</button>

				</form>

			</div>
		</div>
	</div>

	

	<div class="container bg-white p-5 text-primary token">

		<?php 
		require 'vendor/autoload.php';
		use GuzzleHttp\Client;

		if (isset($_GET['purchaseId'])) {
			$val=$_GET['purchaseId'];


			$api_url='http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/market/cancel_purchase.php?pur_id='.$val;

			$client = new Client();


			if (isset($_COOKIE['auth_token'])) {
				$token = $_COOKIE['auth_token'];
				$headers = ['Cookie' => 'auth_token=' . $token];

				try {

					$response = $client->get($api_url, [
						'headers' => $headers,
					]);
					
					$responseBody = $response->getBody()->getContents();
					$statusCode = $response->getStatusCode();


					if ($statusCode === 200) {
						echo $responseBody;
					} elseif ($statusCode === 400) {
						echo 'API Error: Bad Request - ' . $responseBody;
					} elseif ($statusCode === 401) {
						echo 'API Error: Unauthorized - ' . $responseBody;
					} else {
						echo 'API Error: HTTP Status Code ' . $statusCode;
					}

				} catch (GuzzleHttp\Exception\RequestException $e) {
					echo json_encode (['message' => 'Request Exception: ' . $e->getMessage()]);
				}
			}else {
				http_response_code(400);
				echo json_encode(['message' => 'Token Missing']);
			}
		}

		?>
	</div>

	
	<!-- Add Bootstrap JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-lzy6U5dfqjpx6nT6vcXMJz5qFj2b9FJw5Gz2FQkFvokv2tk5lBdk5LylN2Bwp5z" crossorigin="anonymous"></script>
</body>
</html>