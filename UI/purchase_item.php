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
                <form method="post" action="" autocomplete="off">
                    <div class="mb-3">
                        <label for="username" class="form-label">Product ID</label>
                        <input name="item_id" type="text" class="form-control" id="item" >
                    </div>  


                    <div class="mb-3">
                        <label for="username" class="form-label">Quantity</label>
                        <input name="quantity" type="text" class="form-control" id="quantity" >
                    </div>  



                    <div class="mb-3">
                        <label for="username" class="form-label">Choose Seller</label>
                        <select name="seller_ip" class="form-control">
                            <option value="seller1">Seller 1</option>
                            <option value="seller2">Seller 2</option>
                        </select>
                    </div>  

                    <button type="submit" class="btn btn-primary">Purchase</button>
                </form>
            </div>
        </div>
    </div>


    <div class="container bg-white text-primary p-5  token">
        <?php
        require 'vendor/autoload.php';
        use GuzzleHttp\Client;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_id = $_POST['item_id'];
            $quantity = $_POST['quantity'];
            $seller_ip = $_POST['seller_ip'];

            

            $api_url = 'http://lab-3395bfef-7de6-4b90-af98-caa76e5daa8a.australiasoutheast.cloudapp.azure.com:7031/market/purchase.php';

            $data = [
                'item_id' => $item_id,
                'quantity'=>$quantity,
                'seller_ip'=>$seller_ip
            ];

            if (isset($_COOKIE['auth_token'])) {
                $token = $_COOKIE['auth_token'];
                $headers = ['Cookie' => 'auth_token=' . $token];


                $client = new Client();


                try {
                   $response = $client->post($api_url, [
                    'form_params' => $data,
                    'headers' => $headers
                ]);

                   $responseBody = $response->getBody()->getContents();

            // Check the response status code
                   $statusCode = $response->getStatusCode();
                   if ($statusCode === 200) {
                // Successful response
                    echo 'API Response: ' . $responseBody;
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
            } catch (Exception $e) {
            // Handle Guzzle client errors
                echo 'Guzzle error: ' . $e->getMessage();
            }
        } else {
            echo 'Guzzle error: Token is missing';
        }
    }
    ?>
</div>
<!-- Add Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-lzy6U5dfqjpx6nT6vcXMJz5qFj2b9FJw5Gz2FQkFvokv2tk5lBdk5LylN2Bwp5z" crossorigin="anonymous"></script>
</body>
</html>