<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // Get user role from session

if ($role === 'Clerk') {
    header('Location: inventory.php'); // Redirect to inventory page if the role is Clerk
    exit();
}

// Fetch data for the dashboard
$total_items_result = $conn->query("SELECT COUNT(*) AS count FROM items");
$total_sales_result = $conn->query("SELECT COUNT(*) AS total_sales_count, SUM(sub_total) AS total_sales_amount FROM sales");
$available_categories_result = $conn->query("SELECT COUNT(*) AS count FROM category");
$total_users_result = $conn->query("SELECT COUNT(*) AS count FROM accounts");

// Fetch low stock items
$low_stock_result = $conn->query("SELECT item_id, item_name, category, StockQuantity, supplier_name, MinimumStockLevel FROM items WHERE StockQuantity <= MinimumStockLevel");

if ($total_items_result) {
    $total_items = $total_items_result->fetch_assoc()['count'];
} else {
    echo "Error fetching total items: " . $conn->error;
    exit();
}

if ($total_sales_result) {
    $sales_data = $total_sales_result->fetch_assoc();
    $total_sales_count = $sales_data['total_sales_count'];
    $total_sales_amount = $sales_data['total_sales_amount'];
} else {
    echo "Error fetching total sales: " . $conn->error;
    exit();
}

if ($available_categories_result) {
    $available_categories = $available_categories_result->fetch_assoc()['count'];
} else {
    echo "Error fetching available categories: " . $conn->error;
    exit();
}

if ($total_users_result) {
    $total_users = $total_users_result->fetch_assoc()['count'];
} else {
    echo "Error fetching total users: " . $conn->error;
    exit();
}

// Calculate total profit
$total_profit_result = $conn->query("SELECT SUM(sub_total - (sub_total / 1.1)) AS total_profit FROM sales");
if ($total_profit_result) {
    $total_profit = $total_profit_result->fetch_assoc()['total_profit'];
} else {
    echo "Error fetching total profit: " . $conn->error;
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Merlie Rice Trading | Dashboard</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>Welcome to Dashboard, <?php echo $username; ?>!</h1>
        <p>Monitor your inventory, sales, and more from this dashboard.</p>
        <div class="dashboard-overview">
            <div class="overview-box">
                <h2><?php echo $total_items; ?></h2>
                <p>Total Items</p>
            </div>
            <div class="overview-box">
                <h2><?php echo $available_categories; ?></h2>
                <p>Available Categories</p>
            </div>
            <div class="overview-box">
                <h2><?php echo $total_users; ?></h2>
                <p>Users</p>
            </div>
            <div class="overview-box">
                <h2><?php echo $total_sales_count; ?></h2>
                <p>Total Sales</p>
            </div>
            <div class="overview-box">
                <h2>₱<?php echo number_format($total_sales_amount, 2); ?></h2>
                <p>Total Revenue</p>
            </div>
            <div class="overview-box">
                <h2>₱<?php echo number_format($total_profit, 2); ?></h2>
                <p>Total Profit</p>
            </div>
        </div>
        
        <?php if ($low_stock_result && $low_stock_result->num_rows > 0): ?>
            <div class="low-stock-alert">
                <h2><i class="fas fa-exclamation-triangle" style="color: red;"></i> Low Stock Alert</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Stock Quantity Left (in kg)</th>
                            <th>Supplier Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $low_stock_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['StockQuantity']); ?></td>
                                <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
