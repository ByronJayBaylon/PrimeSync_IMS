<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Fetch supplier details
    $sql = "SELECT contact_number, email FROM suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $supplier_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($contact_number, $email);
    $stmt->fetch();

    // Use an SMS or Email API to send the stock request (this is a simplified example)
    $message = "Requesting $quantity of $item_name. Please restock.";
    sendNotification($contact_number, $email, $message);

    $_SESSION['message'] = "Stock request sent successfully.";
    header("Location: supplier_management.php");
    exit();
}

function sendNotification($contact_number, $email, $message) {
    // This is a placeholder function. Integrate with your SMS or Email API here.
    // Example using an SMS API:
    /*
    $api_key = 'your_api_key';
    $api_url = "https://api.smsprovider.com/send?api_key=$api_key&to=$contact_number&message=" . urlencode($message);
    file_get_contents($api_url);
    */
    // For email:
    /*
    mail($email, "Stock Request", $message);
    */
}

$conn->close();
?>
