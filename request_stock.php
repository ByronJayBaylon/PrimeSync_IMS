<?php
session_start();
include 'db_connector.php'; // Include the database connection script

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Fetch supplier contact number
    $sql = "SELECT contact_number FROM suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $supplier_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($contact_number);
    $stmt->fetch();

    // Use Twilio API to send the stock request
    $message = "Requesting $quantity of $item_name. Please restock.";
    sendNotification($contact_number, $message);

    $_SESSION['message'] = "Stock request sent successfully.";
    header("Location: supplier_management.php");
    exit();
}

function sendNotification($contact_number, $message) {
    $account_sid = 'AC652d32575d40b6bd2953fb4c35912c20';
    $auth_token = '2513a5a328ecfe2ff8d5f628dbc47319';
    $twilio_number = 'MGbb8922f107b38312cad7d6d6dd2d29ac';

    $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json';
    $data = [
        'To' => $contact_number,
        'MessagingServiceSid' => $twilio_number,
        'Body' => $message
    ];

    $post = http_build_query($data);
    $x = curl_init($url);
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_USERPWD, "$account_sid:$auth_token");
    curl_setopt($x, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($x);
    
    // Log the request and response for debugging
    if (curl_errno($x)) {
        error_log('Twilio cURL error: ' . curl_error($x));
    } else {
        error_log('Twilio API Request: ' . $url);
        error_log('Twilio API Request Data: ' . print_r($data, true));
        error_log('Twilio API Response: ' . $result);
    }
    
    curl_close($x);
}

$conn->close();
?>
