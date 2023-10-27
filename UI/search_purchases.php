<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login Form</title>
	<!-- Add Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">
</head>
<body style="background: skyblue;">
	<div class="container bg-white p-5 mt-5">
		<div class="row">
			<div class="col-md-6">
				<form method="get" action="" autocomplete="off" id="searchForm">
					<div class="mb-3">
						<label class="form-label"><b>Search : </b></label>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" id="getRequest" name="requestMethod" >
							<label class="form-check-label" for="getRequest">By Purchase ID</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" id="postRequest" name="requestMethod" >
							<label class="form-check-label" for="postRequest">By User ID</label>
						</div>
					</div>

					<div id="purchaseIdInput" class="mb-3" style="display: none;">
						<label for="username" class="form-label">Enter Purchase Id</label>
						<input name="purchaseId" type="text" class="form-control" id="purchaseId">
					</div>

					<div id="searchIdInput" class="mb-3" style="display: none;">
						<label for="username" class="form-label">Enter User Id</label>
						<input name="userId" type="text" class="form-control" id="searchId">
					</div>
					<button type="submit" class="btn btn-primary" id="searchButton" style="display: none;">Search</button>

				</form>

			</div>
		</div>
	</div>

	<script type="text/javascript">
		// Get references to the radio buttons, input field divs, and the "Search" button
		const getRequestRadio = document.getElementById("getRequest");
		const postRequestRadio = document.getElementById("postRequest");
		const purchaseIdInput = document.getElementById("purchaseIdInput");
		const searchIdInput = document.getElementById("searchIdInput");
		const searchButton = document.getElementById("searchButton");
		const searchForm = document.getElementById("searchForm");

// Add event listeners to the radio buttons
		getRequestRadio.addEventListener("change", () => {
			if (getRequestRadio.checked) {
				purchaseIdInput.style.display = "block";
				searchIdInput.style.display = "none";
        searchButton.style.display = "block"; // Show the button
    }
});

		postRequestRadio.addEventListener("change", () => {
			if (postRequestRadio.checked) {
				purchaseIdInput.style.display = "none";
				searchIdInput.style.display = "block";
        searchButton.style.display = "block"; // Show the button
    }
});

// Add an event listener to the form submission
		searchForm.addEventListener("submit", (event) => {
			if (getRequestRadio.checked) {
        // If "By Purchase ID" is selected, remove the "Enter Search Id" input
				searchForm.removeChild(searchIdInput);
			} else if (postRequestRadio.checked) {
        // If "By User ID" is selected, remove the "Enter Purchase Id" input
				searchForm.removeChild(purchaseIdInput);
			}
		});



	</script>


	<div class="container bg-white p-5 text-primary  token">

		<?php 
		require 'vendor/autoload.php';
		use GuzzleHttp\Client;

		if (isset($_GET['purchaseId']) ||isset($_GET['userId'])) {
			$keyword= isset($_GET['purchaseId'] )?  $_GET['purchaseId'] : $_GET['userId'];
			$val=isset($_GET['purchaseId'] )? "pur_id" : "user_id";

			$api_url="http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/market/search_purchase.php?".$val.'='.$keyword;

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
                // Successful response
						echo $responseBody;
					} elseif ($statusCode === 400) {    
                // Bad Request
						echo 'API Error: Bad Request - ' . $responseBody;
					} elseif ($statusCode === 401) {
                // Unauthorized
						echo 'API Error: Unauthorized - ' . $responseBody;
					} else {
                // Handle other status codes as needed
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