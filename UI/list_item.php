<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>List Item</title>
	<!-- Add Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>
<body style="background: skyblue;">
	<div class="container bg-white p-5 mt-5">
		<div class="row">
			<h2 class="mb-3">List Items</h2>
			<div class="col-md-6">
				<form method="get" action="" autocomplete="off" id="searchForm">
					<button type="submit" name="seller1" class="btn btn-primary" id="searchButton">Seller 1</button>
				</form>
			</div>

			<div class="col-md-6">


				<form method="get" action="" autocomplete="off" id="searchForm">
					<button type="submit" name="seller2" class="btn btn-primary" id="searchButton">Seller 2</button>
				</form>


			</div>

		</div>
	</div>



	<div class="container bg-white p-5 text-primary  token">

		<?php 
		require 'vendor/autoload.php';
		use GuzzleHttp\Client;

		if (isset($_GET['seller1'])) {
			$api_url='http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/seller1/itemlist.php';
			$client = new Client();

			try {
				$response = $client->get($api_url);
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
		}


		if (isset($_GET['seller2'])) {
			$api_url='http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/seller2/listitem.php';
			$client = new Client();

			try {
				$response = $client->get($api_url);
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
		}


		?>
	</div>


	<!-- Add Bootstrap JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-lzy6U5dfqjpx6nT6vcXMJz5qFj2b9FJw5Gz2FQkFvokv2tk5lBdk5LylN2Bwp5z" crossorigin="anonymous"></script>
</body>
</html>