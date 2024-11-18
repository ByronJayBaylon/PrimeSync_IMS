<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if (!isset($_SESSION['username']) || !isset($_POST['admin_password'])) {
    echo 'error';
    exit();
}

$username = $_SESSION['username'];
$admin_password = $_POST['admin_password'];

// Fetch the hashed password of the logged-in admin
$sql = "SELECT password FROM accounts WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    // Verify the entered password with the hashed password
    if (password_verify($admin_password, $hashed_password)) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}

$conn->close();
?>
