<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$logged_in_username = $_SESSION['username'];

// Fetch the account type of the logged-in user
$sql = "SELECT account_type FROM accounts WHERE username = '$logged_in_username'";
$result = $conn->query($sql);
$logged_in_user_type = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logged_in_user_type = $row['account_type'];
}

$suppliers = [];
$sql = "SELECT supplier_id, supplier_name, contact_number, email FROM suppliers";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = $row;
        }
    }
} else {
    // Print SQL error message
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
    <script src="/PrimeSync_IMS/assets/JavaScript/IMS_script.js?v=<?php echo time(); ?>"></script>
    <title>Merlie Rice Trading | Supplier Management</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>Supplier Management</h1>

        <!-- Supplier Table Container -->
        <div class="table-container">
            <table border="1" cellspacing="0" cellpadding="10" class="supplier-table" id="supplierTable">
                <caption style="text-align: left;">
                    All Suppliers
                </caption>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $index => $supplier): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($supplier['supplier_id']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                            <td>
                                <button class="request-btn modal-btn edit" data-id="<?php echo htmlspecialchars($supplier['supplier_id']); ?>" data-name="<?php echo htmlspecialchars($supplier['supplier_name']); ?>">Request Stock</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        
        <!-- Add Supplier Modal Structure -->
        <button id="openAddSupplierModal" class="modal-btn add"><b style="font-size: 15px;">+ </b>Add New Supplier</button>

        <div id="addSupplierModal" class="usermodal">
            <div class="usermodal-content">
                <span class="close" id="closeAddSupplierModal">&times;</span>
                <h2>Add Supplier</h2>
                <form id="addSupplierForm" method="post" action="add_supplier.php">
                    <label for="supplier_name">Supplier Name</label>
                    <input type="text" id="supplier_name" name="name" required>
                    <br>
                    <label for="supplier_contact">Contact Number</label>
                    <input type="text" id="supplier_contact" name="contact_number" required>
                    <br>
                    <label for="supplier_email">Email</label>
                    <input type="email" id="supplier_email" name="email" required>
                    <br>
                    <label for="supplier_address">Address</label>
                    <textarea id="supplier_address" name="address"></textarea>
                    <br>
                    <button type="submit">Add New Supplier</button>
                </form>
            </div>
        </div>

        <!-- Request Stock Modal Structure -->
        <div id="requestStockModal" class="usermodal">
            <div class="usermodal-content">
                <span class="close" id="closeRequestStockModal">&times;</span>
                <h2>Request Stock</h2>
                <form id="requestStockForm" method="post" action="request_stock.php">
                    <input type="hidden" id="supplier_id" name="supplier_id">
                    <label for="item_name">Item Name</label>
                    <input type="text" id="item_name" name="item_name" placeholder="Type item name here" required>
                    <br>
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required>
                    <br>
                    <button type="submit">Send Request</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Additional JavaScript for managing modals and form submissions
        document.addEventListener('DOMContentLoaded', () => {
    const addSupplierModal = document.getElementById('addSupplierModal');
    const openAddSupplierModal = document.getElementById('openAddSupplierModal');
    const closeAddSupplierModal = document.getElementById('closeAddSupplierModal');
    const requestStockModal = document.getElementById('requestStockModal');
    const closeRequestStockModal = document.getElementById('closeRequestStockModal');
    const requestBtns = document.querySelectorAll('.request-btn');

    if (openAddSupplierModal && addSupplierModal && closeAddSupplierModal) {
        // Open add supplier modal when button is clicked
        openAddSupplierModal.addEventListener('click', () => {
            addSupplierModal.style.display = 'block';
        });

        // Close add supplier modal when button is clicked
        closeAddSupplierModal.addEventListener('click', () => {
            addSupplierModal.style.display = 'none';
        });

        // Close add supplier modal when clicking outside of it
        window.addEventListener('click', (event) => {
            if (event.target === addSupplierModal) {
                addSupplierModal.style.display = 'none';
            }
        });
    }

    requestBtns.forEach(button => {
        button.addEventListener('click', () => {
            const supplierId = button.getAttribute('data-id');
            document.getElementById('supplier_id').value = supplierId;
            document.getElementById('item_name').value = ''; // Empty the item name field
            requestStockModal.style.display = 'block';
        });
    });

    closeRequestStockModal.addEventListener('click', () => {
        requestStockModal.style.display = 'none';
    });

    // Close request stock modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === requestStockModal) {
            requestStockModal.style.display = 'none';
        }
    });
});

    </script>
</body>
</html>
