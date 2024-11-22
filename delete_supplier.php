<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_id = $_POST['supplier_id'];

    $sql = "DELETE FROM suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing the statement: " . $conn->error;
        exit();
    }
    $stmt->bind_param('i', $supplier_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error executing the query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
