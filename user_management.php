<?php
session_start();
include 'db_connector.php'; // Include the database connection script

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$logged_in_username = $_SESSION['username'];
$logged_in_user_type = '';

// Fetch the account type of the logged-in user
$sql = "SELECT account_type FROM accounts WHERE username = '$logged_in_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logged_in_user_type = $row['account_type'];
}

$accounts = [];
$sql = "SELECT id, username, account_type, date_created FROM accounts WHERE username != '$logged_in_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $accounts[] = $row;
    }
}

$conn->close();

// Check for alert messages
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    unset($_SESSION['message']); // Clear the message after use
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <script src="/PrimeSync_IMS/assets/JavaScript/IMS_script.js?v=<?php echo time(); ?>"></script>
    <title>Merlie Rice Trading | User Management</title>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>User Management</h1>

        <!-- User Table Container -->
        <div class="table-container">
            <table border="1" cellspacing="0" cellpadding="10" class="user-table" id="userTable">
                <thead>
                    <tr>
                        <th>Account ID</th>
                        <th>User Name</th>
                        <th>Account Type</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounts as $index => $account): ?>
                        <tr data-index="<?php echo $index; ?>" <?php if ($index >= 5) echo 'style="display: none;"'; ?>>
                            <td><?php echo htmlspecialchars($account['id']); ?></td>
                            <td><?php echo htmlspecialchars($account['username']); ?></td>
                            <td><?php echo htmlspecialchars($account['account_type']); ?></td>
                            <td><?php echo htmlspecialchars($account['date_created']); ?></td>
                            <td>
                                <?php if (
                                    $logged_in_user_type === 'Admin' || 
                                    ($logged_in_user_type === 'Owner' && $account['account_type'] !== 'Owner' && $account['account_type'] !== 'Admin')
                                ): ?>
                                    <button class="edit-btn modal-btn edit" data-id="<?php echo htmlspecialchars($account['id']); ?>" data-username="<?php echo htmlspecialchars($account['username']); ?>" data-account-type="<?php echo htmlspecialchars($account['account_type']); ?>">Edit</button>
                                    <button class="delete-btn modal-btn confirm-red" data-id="<?php echo htmlspecialchars($account['id']); ?>">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Entries Controller and Info -->
        <div class="entries-controller">
            Show 
            <select id="entriesSelect">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
            </select>
            entries
        </div>
        <div class="entries-info" id="entriesInfo">
            Showing 1 to <?php echo min(count($accounts), 5); ?> of <?php echo count($accounts); ?> entries
        </div>

        <!-- Page Controller -->
        <div class="page-controller" id="pageController">
            <button id="prevPage" disabled>Previous</button>
            <div id="pageNumbers" style="cursor: arrow;"></div>
            <button id="nextPage" disabled>Next</button>
        </div>

        <button id="openAddUserModal" class="modal-btn add"><b style="font-size: 15px;">+ </b>Add New Account</button>
    </div>

    <!-- Add User Modal Structure -->
    <div id="addUserModal" class="usermodal">
        <div class="usermodal-content">
            <span class="close" id="closeAddUserModal">&times;</span>
            <h2>Add Account</h2>
            <form id="addUserForm" method="post" action="add_account.php">
                <label for="username">User Name</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password')">Show</button>
                </div>
                <br>
                <label for="repeat_password">Repeat Password</label>
                <div class="password-container">
                    <input type="password" id="repeat_password" name="repeat_password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('repeat_password')">Show</button>
                </div>
                <br>
                <label for="account_type">Account Type</label>
                <select id="account_type" name="account_type" required>
                <?php if ($logged_in_user_type === 'Admin'): ?>
                    <option value="Admin">Admin</option>
                    <option value="Owner">Owner</option>
                <?php endif; ?>
                    <option value="Clerk">Clerk</option>
                    <option value="Cashier">Cashier</option>
                </select>
                <br>
                <button type="submit">Add New Account</button>
            </form>
        </div>
    </div>

    <!-- Edit User Modal Structure -->
    <div id="editUserModal" class="usermodal">
        <div class="usermodal-content">
            <span class="close" id="closeEditUserModal">&times;</span>
            <h2>Edit Account</h2>
            <form id="editUserForm" method="post" action="edit_account.php">
                <input type="hidden" id="edit_user_id" name="id">
                <label for="edit_username">User Name</label>
                <input type="text" id="edit_username" name="username" required>
                <br>
                <label for="edit_account_type">Account Type</label>
                <select id="edit_account_type" name="account_type" required>
                <?php if ($logged_in_user_type === 'Admin'): ?>
                    <option value="Admin">Admin</option>
                    <option value="Owner">Owner</option>
                <?php endif; ?>
                    <option value="Clerk">Clerk</option>
                    <option value="Cashier">Cashier</option>
                </select>
                <br>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Delete Modal Structure -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal"></span>
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this account? Please enter your password to confirm.</p>
            <form id="deleteForm" method="post" action="delete_account.php">
                <input type="hidden" name="id" id="deleteId">
                <?php if ($logged_in_user_type === 'Admin'): ?>
                    <label for="admin_password">Admin Password:</label>
                <?php else: ?>
                    <label for="admin_password">Owner Password:</label>
                <?php endif; ?>
                <div class="password-container">
                    <input style="padding: 5px; border-radius: 5px; width: 80%; margin: 15px;" type="password" id="admin_password" name="admin_password" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('admin_password')">Show</button>
                </div>
                <button type="button" class="modal-btn cancel" id="cancelBtn">Cancel</button>
                <button type="submit" class="modal-btn confirm-red" id="confirmDelete">Delete</button>
            </form>
        </div>
    </div>
</body>
</html>
