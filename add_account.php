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
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $account_type = $_POST['account_type'];
    
    // Check for duplicate username and account type
    $sql = "SELECT id FROM accounts WHERE username = ? AND account_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $account_type);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['message'] = "Account already taken. Choose another username and account type.";
        header("Location: user_management.php");
        exit();
    }

    // Check permissions for creating Owner and Admin accounts
    if (($logged_in_user_type !== 'Admin') && ($account_type === 'Owner' || $account_type === 'Admin')) {
        $_SESSION['message'] = "You do not have permission to create Owner or Admin accounts.";
        header("Location: user_management.php");
        exit();
    }

    // Insert the new account into the database
    $sql = "INSERT INTO accounts (username, password, account_type, date_created, created_by) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $username, $password, $account_type, $logged_in_username);

    if ($stmt->execute()) {
        $_SESSION['message'] = "New user added successfully.";
    } else {
        $_SESSION['message'] = "Error adding new user: " . $stmt->error;
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
