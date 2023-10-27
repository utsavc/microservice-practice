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
                        <label for="username" class="form-label">Username</label>
                        <input name="username" type="text" class="form-control" id="username" >
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input name="password" type="password" class="form-control" id="password">
                    </div>
                    <div class="mb-3">
                        <label for="api" class="form-label">API</label>
                        <input name="api" type="text" class="form-control" id="api">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><b>Request Method : </b></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="getRequest" name="requestMethod" value="GET">
                            <label class="form-check-label" for="getRequest">GET</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="postRequest" name="requestMethod" value="POST">
                            <label class="form-check-label" for="postRequest">POST</label>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>

        <div class="text-primary mt-2  token">


            <?php
            require 'vendor/autoload.php';
            use GuzzleHttp\Client;

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $data = [
                    'username' => $username,
                    'password' => $password,
                ];


                $client = new Client();
                try {

                    $api_url="http://localhost/ms2/market/login.php";
                    $response = $client->post($api_url, [
                        'form_params' => $data,
                    ]);


                    $responseBody = $response->getBody()->getContents();
                    echo $responseBody;

                    $data=json_decode($responseBody,true);
                    $token=$data['token'];                    
                    setcookie("auth_token",$token, time() + 300, "/");

                    

                    
                }catch(Exception $e){
                echo 'Guzzle error: ' . $e->getMessage();

                }
            }

            ?>

        </div>


    </div>

    <!-- Add Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-lzy6U5dfqjpx6nT6vcXMJz5qFj2b9FJw5Gz2FQkFvokv2tk5lBdk5LylN2Bwp5z" crossorigin="anonymous"></script>
</body>
</html>

