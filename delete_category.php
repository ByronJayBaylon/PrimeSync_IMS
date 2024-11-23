<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$logged_in_username = $_SESSION['username'];
$category_id = $_POST['category_id'];
$username = $_POST['username'];
$password = $_POST['password'];

// Verify user credentials
if ($username && $password) {
    $sql = "SELECT password, account_type FROM accounts WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password']) && ($row['account_type'] === 'Admin' || $row['account_type'] === 'Owner')) {
            // User authenticated
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect credentials or insufficient permissions.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit();
    }
} else {
    $sql = "SELECT password FROM accounts WHERE username = '$logged_in_username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!password_verify($password, $row['password'])) {
            echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit();
    }
}

// Delete the category
$sql = "DELETE FROM category WHERE cat_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $category_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting category.']);
}

$stmt->close();
$conn->close();
?>
