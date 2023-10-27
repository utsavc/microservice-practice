<?php
// Database connection parameters
$servername1 = 'localhost';
$servername2 = 'localhost';
$username = 'root';
$password = 'kit514@@';
$database1 = 'seller1';
$database2 = 'seller2';

$item_name = $_GET['item_name'] ?? '';

$combinedData = [];

function searchDB($server, $db, $user, $pass, $item_name) {
    $conn = new mysqli($server, $user, $pass, $db);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $sql = "SELECT * FROM item";
    if (!empty($item_name)) {
        $sql .= " WHERE item_name = '$item_name'";
    }

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            global $combinedData;
            $combinedData[] = $row;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

// Query the first database
searchDB($servername1, $database1, $username, $password, $item_name);

// Query the second database
searchDB($servername2, $database2, $username, $password, $item_name);

// Sort the aggregated data by price in ascending order
usort($combinedData, function ($a, $b) {
    return $a['price_of_unit'] - $b['price_of_unit'];
});

// Return the aggregated data as JSON
header('Content-Type: application/json');
echo json_encode($combinedData);
?>
