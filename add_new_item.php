<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Set the time zone to Manila
date_default_timezone_set('Asia/Manila');

$item_name = $_POST['item_name'];
$category = $_POST['category'];
$cat_id = $_POST['category_id'];
$price = $_POST['price'];
$supplier_name = $_POST['supplier'];
$supplier_id = $_POST['supplier_id'];

// Current date and time with correct timezone
$date_time = date('Y-m-d H:i:s'); 

// Insert new item into the items table with the current timestamp
$sql = "INSERT INTO items (item_name, cat_id, category, item_price, supplier_name, supplier_id, date_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('sisdsss', $item_name, $cat_id, $category, $price, $supplier_name, $supplier_id, $date_time);
    if ($stmt->execute()) {
        echo "Item added successfully!";
    } else {
        echo "Error adding item: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

$conn->close();
?>
