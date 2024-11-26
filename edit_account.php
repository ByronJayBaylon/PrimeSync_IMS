<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$logged_in_username = $_SESSION['username'];

// Fetch the account type of the logged-in user
$sql = "SELECT account_type FROM accounts WHERE username = '$logged_in_username'";
$result = $conn->query($sql);
$logged_in_user_type = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logged_in_user_type = $row['account_type'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $account_type = $_POST['account_type'];

    // Check for duplicate username and account type
    $sql = "SELECT id FROM accounts WHERE username = ? AND account_type = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $username, $account_type, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "Account already taken. Choose another username and account type.";
        header("Location: user_management.php");
        exit();
    }

    // Check if the logged-in user is allowed to change the account type
    if ($logged_in_user_type !== 'Admin' && ($account_type === 'Owner' || $account_type === 'Admin')) {
        $_SESSION['message'] = "You do not have permission to change the account type to Owner or Admin.";
        header("Location: user_management.php");
        exit();
    }

    // Update the user in the database
    $sql = "UPDATE accounts SET username = ?, account_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $username, $account_type, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Account updated successfully.";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: user_management.php");
    exit();
} else {
    header("Location: user_management.php");
    exit();
}
?>
