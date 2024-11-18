<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $admin_password = $_POST['admin_password'];
    $username = $_SESSION['username'];

    // Fetch the account types
    $sql = "SELECT account_type FROM accounts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $accountToDelete = $result->fetch_assoc()['account_type'];

    $sql = "SELECT account_type FROM accounts WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentUserType = $result->fetch_assoc()['account_type'];

    // Check password verification
    $sql = "SELECT password FROM accounts WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $storedPassword = $result->fetch_assoc()['password'];
    
    if (!password_verify($admin_password, $storedPassword)) {
        $_SESSION['message'] = "Wrong password. Please try again.";
        header("Location: user_management.php");
        exit();
    }

    // Implement the deletion rules
    if (($currentUserType === 'Owner') || ($currentUserType === 'Admin' && ($accountToDelete !== 'Admin' && $accountToDelete !== 'Owner'))) {
        $sql = "DELETE FROM accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Account deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting account: " . $stmt->error;
        }
    } else {
        $_SESSION['message'] = "You do not have permission to delete this account.";
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
