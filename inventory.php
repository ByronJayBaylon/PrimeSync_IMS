<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include 'db_connector.php';

// Handle entries per page
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $entries_per_page;

// Handle search query
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT * FROM items WHERE item_id LIKE '%$search_query%' OR item_name LIKE '%$search_query%' OR category LIKE '%$search_query%' LIMIT $offset, $entries_per_page";
    $count_sql = "SELECT COUNT(*) AS total FROM items WHERE item_id LIKE '%$search_query%' OR item_name LIKE '%$search_query%' OR category LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM items LIMIT $offset, $entries_per_page";
    $count_sql = "SELECT COUNT(*) AS total FROM items";
}

$result = $conn->query($sql);
$count_result = $conn->query($count_sql);
$total_entries = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_entries / $entries_per_page);

$start_entry = ($offset + 1);
$end_entry = min(($offset + $entries_per_page), $total_entries);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Merlie Rice Trading | Inventory List</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>Inventory List</h1>
        
        <!-- Search bar -->
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by item ID, name, or category..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="modal-btn add">Search</button>
            </form>
        </div>

        <!-- Items table -->
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Stock Quantity(in kg)</th>
                    <th>Item Price(per kg)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['item_id']; ?></td>
                            <td><?php echo $row['item_name']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['StockQuantity']; ?></td>
                            <td><?php echo '₱'; echo $row['item_price']; ?></td>
                            <td class="action-buttons">
                                <a href="add_quantity.php?id=<?php echo $row['item_id']; ?>" class="modal-btn add">Add Stock</a>
                                <a href="edit_item.php?id=<?php echo $row['item_id']; ?>" class="edit-btn modal-btn edit">Edit</a>
                                <a href="delete_item.php?id=<?php echo $row['item_id']; ?>" class="delete-btn modal-btn confirm-red" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Entries per page -->
        <div class="entries-per-page">
            <form method="GET" action="">
                <label for="entries">Show:</label>
                <select name="entries" id="entries" onchange="this.form.submit()">
                    <option value="5" <?php if ($entries_per_page == 5) echo 'selected'; ?>>5</option>
                    <option value="10" <?php if ($entries_per_page == 10) echo 'selected'; ?>>10</option>
                    <option value="25" <?php if ($entries_per_page == 25) echo 'selected'; ?>>25</option>
                </select> entries
            </form>
        </div>
        <!-- Entry Counter -->
        <div class="entry-counter">
            <?php if ($total_entries > 0): ?>
                Showing <?php echo $start_entry; ?> to <?php echo $end_entry; ?> of <?php echo $total_entries; ?> entries
            <?php else: ?>
                Showing 0 entries
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&entries=<?php echo $entries_per_page; ?>&search=<?php echo htmlspecialchars($search_query); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&entries=<?php echo $entries_per_page; ?>&search=<?php echo htmlspecialchars($search_query); ?>" <?php if ($i == $page) echo 'style="font-weight: bold;"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&entries=<?php echo $entries_per_page; ?>&search=<?php echo htmlspecialchars($search_query); ?>">Next</a>
            <?php endif; ?>
        </div>
        <br>
        <br>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
