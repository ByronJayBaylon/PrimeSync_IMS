<?php

session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $item_id = $_GET['id'];

    // Fetch item details
    $sql = "SELECT item_name, category, StockQuantity, item_price FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $item_id);
    $stmt->execute();
    $stmt->bind_result($item_name, $category, $stock_quantity, $item_price);
    $stmt->fetch();
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $stock_quantity = $_POST['stock_quantity'];

    $sql = "UPDATE items SET StockQuantity = StockQuantity + ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $stock_quantity, $item_id);

    if ($stmt->execute()) {
        header("Location: inventory.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Add Stock Quantity</title>
    <style>
        .item-details {
            float: right;
            width: 30%;
            padding: 20px;
            margin-top: -80px;
        }
        .add-quantity-form {
            float: left;
            width: 50%;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    
    <div class="dashboard-container add-quantity-div">
        <h1>Add Stock Quantity</h1>

        <div class="item-details">
            <h2>Item Details</h2>
            <p><strong>Item ID:</strong> <?php echo htmlspecialchars($item_id); ?></p>
            <p><strong>Item Name:</strong> <?php echo htmlspecialchars($item_name); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
            <p><strong>Stock Quantity:</strong> <?php echo htmlspecialchars($stock_quantity); ?></p>
            <p><strong>Item Price:</strong> <?php echo '₱'; echo htmlspecialchars($item_price); ?></p>
        </div>

        <div class="add-quantity-form">
            <form method="POST" action="add_quantity.php">
                <div class="form-input add-quantity">
                    <label for="stock_quantity"><b>Enter Stock Quantity to Add:</b></label>
                    <input type="number" id="stock_quantity" name="stock_quantity" placeholder="Enter Stock Quantity to Add" required>
                    <input type="hidden" id="item_id" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">
                    <button type="submit" class="modal-btn add">Add Stocks</button>
                    <button type="button" class="modal-btn cancel" onclick="window.location.href='inventory.php'">Cancel</button>
                </div>
            </form>
        </div>
        
        <div style="clear: both;"></div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
