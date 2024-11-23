<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $item_id = $_GET['id'];
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $item_id);

    if ($stmt->execute()) {
        echo "<script>alert('Item successfully deleted in inventory.'); window.location.href='inventory.php';</script>";
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
    <title>Delete Item</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->
    <div class="dashboard-container">
        <h1>Delete Item</h1>
        <form method="POST" action="delete_item.php">
            <p>Are you sure you want to delete this item?</p>
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
            <button type="submit" class="modal-btn confirm-red">Delete</button>
            <button type="button" class="modal-btn cancel" onclick="window.location.href='inventory.php';">Cancel</button>
        </form>
    </div>
    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
