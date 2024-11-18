<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address']; // If address field is included

    $sql = "INSERT INTO suppliers (supplier_name, contact_number, email, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $name, $contact_number, $email, $address);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Supplier added successfully.";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: supplier_management.php");
    exit();
}
?>
