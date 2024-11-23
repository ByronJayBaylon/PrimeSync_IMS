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
        <p>Manage the flow of your stocks with your suppliers from this page.</p>

        <!-- Supplier Table Container -->
        <div class="table-container">
            <table border="1" cellspacing="0" cellpadding="10" class="supplier-table" id="supplierTable">
                <caption style="text-align: left;">Suppliers List</caption>
                <thead>
                    <tr>
                        <th>Supplier ID</th>
                        <th>Supplier Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $index => $supplier): ?>
                        <tr id="supplier-<?php echo htmlspecialchars($supplier['supplier_id']); ?>">
                            <td><?php echo htmlspecialchars($supplier['supplier_id']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                            <td>
                                <button class="request-btn modal-btn edit" data-id="<?php echo htmlspecialchars($supplier['supplier_id']); ?>" data-name="<?php echo htmlspecialchars($supplier['supplier_name']); ?>">Request Restock</button>
                                <button class="delete-supplier-btn modal-btn confirm-red" data-id="<?php echo htmlspecialchars($supplier['supplier_id']); ?>" data-name="<?php echo htmlspecialchars($supplier['supplier_name']); ?>">Delete Supplier</button>
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
                    <button type="submit" class="modal-btn add">Add New Supplier</button>
                </form>
            </div>
        </div>

        <!-- Request Stock Modal Structure -->
        <div id="requestStockModal" class="usermodal">
            <div class="usermodal-content">
                <span class="close" id="closeRequestStockModal">&times;</span>
                <h2>Request Restock</h2>
                <form id="requestStockForm" method="post" action="request_stock.php">
                    <input type="hidden" id="supplier_id" name="supplier_id">
                    <label for="item_name">Item Name</label>
                    <input type="text" id="item_name" name="item_name" placeholder="Type item name here" required>
                    <br>
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required>
                    <br>
                    <button type="submit" class="modal-btn edit">Send Request</button>
                </form>
            </div>
        </div>

        <!-- Delete Supplier Modal Structure -->
        <div id="deleteSupplierModal" class="usermodal">
            <div class="usermodal-content">
                <span class="close" id="closeDeleteSupplierModal">&times;</span>
                <h2>Delete Supplier</h2>
                <form id="deleteSupplierForm" method="post">
                    <p>Are you sure you want to delete this supplier?<br>Please enter your password to confirm.</p>
                    <input type="hidden" id="delete_supplier_id" name="supplier_id">
                    <?php if ($logged_in_user_type === 'Admin'): ?>
                        <label for="admin_password" style="text-align: center;">Administrator Password:</label>
                    <?php else: ?>
                        <label for="admin_password" style="text-align: center;">Owner Password:</label>
                    <?php endif; ?>
                    <div class="password-container">
                        <input type="password" id="admin_password" name="admin_password" required>
                        <button type="button" id="togglePassword" class="toggle-password toggle-on-delete-modal">Show</button>
                    </div>
                    <button type="submit" class="modal-btn confirm-red">Delete</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Additional JavaScript for managing modals and form submissions -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addSupplierModal = document.getElementById('addSupplierModal');
            const openAddSupplierModal = document.getElementById('openAddSupplierModal');
            const closeAddSupplierModal = document.getElementById('closeAddSupplierModal');
            const requestStockModal = document.getElementById('requestStockModal');
            const closeRequestStockModal = document.getElementById('closeRequestStockModal');
            const deleteSupplierModal = document.getElementById('deleteSupplierModal');
            const closeDeleteSupplierModal = document.getElementById('closeDeleteSupplierModal');
            const requestBtns = document.querySelectorAll('.request-btn');
            const deleteSupplierBtns = document.querySelectorAll('.delete-supplier-btn');

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

            deleteSupplierBtns.forEach(button => {
                button.addEventListener('click', () => {
                    const supplierId = button.getAttribute('data-id');
                    document.getElementById('delete_supplier_id').value = supplierId;
                    deleteSupplierModal.style.display = 'block';
                });
            });

            closeDeleteSupplierModal.addEventListener('click', () => {
                deleteSupplierModal.style.display = 'none';
            });

            // Close delete supplier modal when clicking outside of it
            window.addEventListener('click', (event) => {
                if (event.target === deleteSupplierModal) {
                    deleteSupplierModal.style.display = 'none';
                }
            });

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('admin_password');
            togglePassword.addEventListener('click', () => {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    togglePassword.textContent = 'Hide';
                } else {
                    passwordField.type = 'password';
                    togglePassword.textContent = 'Show';
                }
            });

            // Handle delete supplier form submission with admin password confirmation
            document.getElementById('deleteSupplierForm').onsubmit = function(event) {
                event.preventDefault();
                const supplierId = document.getElementById('delete_supplier_id').value;
                const adminPassword = document.getElementById('admin_password').value;

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_supplier.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('supplier-' + supplierId).remove();
                            deleteSupplierModal.style.display = 'none';
                            alert('Supplier successfully deleted.');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('Error deleting supplier. Please try again.');
                    }
                };
                xhr.send("supplier_id=" + supplierId + "&admin_password=" + encodeURIComponent(adminPassword));
            };
        });
    </script>
</body>
</html>
