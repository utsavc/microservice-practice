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
				<form method="get" action="" autocomplete="off">
					<div class="mb-3">
						<label for="username" class="form-label">Enter Keyword</label>
						<input name="keyword" type="text" class="form-control" id="keyword" >
					</div>  

					<button type="submit" class="btn btn-primary">Search</button>
				</form>
			</div>
		</div>
	</div>


	<div class="container bg-white p-5  token">

		<?php 
			require 'vendor/autoload.php';
			use GuzzleHttp\Client;

			if (isset($_GET['keyword'])) {
				$keyword=$_GET['keyword'];
				$url="http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/search/search_item.php?item_name=".$keyword;

				$client = new Client();

				try {
					$response = $client->request('GET', $url);

					if ($response->getStatusCode() === 200) {
						$data = json_decode($response->getBody(), true);

						if ($data !== null) {
							http_response_code(200); 
							echo json_encode(['data' => $data]);
							exit;
						} else {
							return ['message' => 'Error decoding JSON response'];
						}
					} else {
						return ['message' => 'HTTP Error: ' . $response->getStatusCode()];
					}
				} catch (GuzzleHttp\Exception\RequestException $e) {
					return ['message' => 'Request Exception: ' . $e->getMessage()];
				}
			}

		?>
	</div>
	<!-- Add Bootstrap JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-lzy6U5dfqjpx6nT6vcXMJz5qFj2b9FJw5Gz2FQkFvokv2tk5lBdk5LylN2Bwp5z" crossorigin="anonymous"></script>
</body>
</html>