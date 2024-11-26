<?php
include '../db_connector.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$query = $conn->real_escape_string($query);

$sql = "SELECT item_id AS id, item_name AS name, (item_price * 1.10) AS price, StockQuantity AS stock_quantity 
        FROM items WHERE item_name LIKE '%$query%'";
$result = $conn->query($sql);

$inventory = [];
while ($row = $result->fetch_assoc()) {
    $inventory[] = $row;
}

$conn->close();

echo json_encode($inventory);
?>
