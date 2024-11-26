<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$logged_in_username = $_SESSION['username'];
$admin_password = $_POST['admin_password'];
$supplier_id = $_POST['supplier_id'];

// Use prepared statements for fetching the admin's hashed password
$sql = "SELECT password FROM accounts WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $logged_in_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (!password_verify($admin_password, $row['password'])) {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit();
}

$stmt->close();

// Delete items associated with the supplier
$sql = "DELETE FROM items WHERE supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $supplier_id);
$stmt->execute();
$stmt->close();

// Delete the supplier
$sql = "DELETE FROM suppliers WHERE supplier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $supplier_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting supplier.']);
}

$stmt->close();
$conn->close();
?>
