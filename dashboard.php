<?php
session_start();
include 'db_connector.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role']; // Get user role from session

if ($role === 'Clerk') {
    header('Location: inventory.php'); // Redirect to inventory page if the role is Clerk
    exit();
}

// Fetch data for the dashboard
$total_items_result = $conn->query("SELECT COUNT(*) AS count FROM items");
$total_sales_result = $conn->query("SELECT COUNT(*) AS total_sales_count, SUM(sub_total) AS total_sales_amount FROM sales"); // Adjusted query
$available_categories_result = $conn->query("SELECT COUNT(*) AS count FROM category");
$total_users_result = $conn->query("SELECT COUNT(*) AS count FROM accounts");

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Merlie Rice Trading | Dashboard</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>Dashboard</h1>
        <p>Manage your inventory, sales, and more from this dashboard.</p>
        <div class="dashboard-overview">
            <div class="overview-box">
                <h2><?php echo $total_items; ?></h2>
                <p>Total Items</p>
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
                <h2><?php echo $available_categories; ?></h2>
                <p>Available Categories</p>
            </div>
            <div class="overview-box">
                <h2><?php echo $total_users; ?></h2>
                <p>Users</p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
