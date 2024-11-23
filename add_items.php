<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include 'db_connector.php';

// Fetch categories
$categories = [];
$category_sql = "SELECT * FROM category";
$category_result = $conn->query($category_sql);
if ($category_result) {
    if ($category_result->num_rows > 0) {
        while ($row = $category_result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
} else {
    echo "Error: " . $conn->error;
}

// Fetch suppliers
$suppliers = [];
$supplier_sql = "SELECT * FROM suppliers";
$supplier_result = $conn->query($supplier_sql);
if ($supplier_result) {
    if ($supplier_result->num_rows > 0) {
        while ($row = $supplier_result->fetch_assoc()) {
            $suppliers[] = $row;
        }
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Merlie Rice Trading | Add Items</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container add-item-div">
        <h1>Add Items</h1>
        <p>Add items in your inventory from this page.</p>
        <form id="addItemForm" method="post" action="add_new_item.php" class="usermodal-content add-item-form">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required>
            <br>
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['category']); ?>" data-id="<?php echo htmlspecialchars($category['cat_id']); ?>"><?php echo htmlspecialchars($category['category']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" id="category_id" name="category_id" required>
            <br>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" placeholder="12.00" required>
            <br>
            <label for="supplier">Supplier:</label>
            <select id="supplier" name="supplier" required>
                <option value="">Select Supplier</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo htmlspecialchars($supplier['supplier_name']); ?>" data-id="<?php echo htmlspecialchars($supplier['supplier_id']); ?>"><?php echo htmlspecialchars($supplier['supplier_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" id="supplier_id" name="supplier_id" required>
            <br>
            <button type="submit" class="modal-btn add">Add Item</button>
        </form>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->

    <script>
        // Set hidden category_id field based on selected option
        document.getElementById('category').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('category_id').value = selectedOption.getAttribute('data-id');
        });

        // Set hidden supplier_id field based on selected option
        document.getElementById('supplier').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('supplier_id').value = selectedOption.getAttribute('data-id');
        });
    </script>
</body>
</html>
