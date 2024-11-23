<?php

session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $item_id = $_GET['id'];
    $sql = "SELECT * FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $stock_quantity = $_POST['StockQuantity'];
    $item_price = $_POST['item_price'];

    $sql = "UPDATE items SET item_name = ?, category = ?, StockQuantity = ?, item_price = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssidi', $item_name, $category, $stock_quantity, $item_price, $item_id);

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
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Edit Item</title>
</head>
<body>
    <div class="dashboard-container edit-items-div">
        <h1>Edit Item</h1>
        <form method="POST" action="edit_item.php">
            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
            <div class="form-input" style="border: none;">
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name" value="<?php echo $item['item_name']; ?>" required>
            </div>
            <div class="form-input" style="border: none;">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" value="<?php echo $item['category']; ?>" required>
            </div>
            <div class="form-input" style="border: none;">
                <label for="StockQuantity">Stock Quantity:</label>
                <input type="number" id="StockQuantity" name="StockQuantity" value="<?php echo $item['StockQuantity']; ?>" required>
            </div>
            <div class="form-input" style="border: none;">
                <label for="item_price">Item Price:</label>
                <input type="number" step="0.01" id="item_price" name="item_price" value="<?php echo $item['item_price']; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="modal-btn edit">Save Changes</button>
                <button type="button" class="modal-btn cancel" onclick="window.location.href='inventory.php'">Cancel</button>
            </div>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
