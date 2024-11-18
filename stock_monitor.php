<?php
include 'db_connector.php';

// Fetch stock levels and minimum stock thresholds
$sql = "SELECT item_id, stock, min_stock_threshold, supplier_id FROM inventory";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    if ($row['stock'] <= $row['min_stock_threshold']) {
        $supplier_id = $row['supplier_id'];
        $item_id = $row['item_id'];
        // Fetch supplier details
        $sqlSupplier = "SELECT contact_number FROM suppliers WHERE id = '$supplier_id'";
        $resultSupplier = $conn->query($sqlSupplier);
        if ($resultSupplier->num_rows > 0) {
            $supplier = $resultSupplier->fetch_assoc();
            $contact_number = $supplier['contact_number'];
            // Send SMS
            $message = "Requesting resupply for item ID: $item_id.";
            sendSMS($contact_number, $message); // Function to send SMS via API
        }
    }
}

$conn->close();

function sendSMS($contact_number, $message) {
    // SMS API integration here
    // Example using an SMS API
    $api_key = 'your_api_key';
    $api_url = "https://api.smsprovider.com/send?api_key=$api_key&to=$contact_number&message=" . urlencode($message);
    file_get_contents($api_url);
}
?>
