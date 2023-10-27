<?php
function getItems($item_name) {
$servername = 'localhost';
$username = 'root';
$password = 'kit514@@';
$dbname = 'seller1';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM item";
    if (!empty($item_name)) {
        $sql .= " WHERE item_name = '$item_name'";
    }

    $sql .= " ORDER BY price_of_unit ASC";
    $result = $conn->query($sql);

    if ($result) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}



$item_name = $_GET['item_name'] ?? '';
getItems($item_name);

?>
