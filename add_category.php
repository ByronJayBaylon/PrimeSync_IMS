<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$category_name = $_POST['category_name'];
$date_time = date('Y-m-d H:i:s');
$creator = $_SESSION['username'];

$sql = "INSERT INTO category (category, date_time, creator) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $category_name, $date_time, $creator);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding category.']);
}

$stmt->close();
$conn->close();
?>
