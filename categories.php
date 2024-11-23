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

// Fetch data from the category table
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM category 
        WHERE category LIKE '%$search_query%'
        ORDER BY date_time DESC
        LIMIT $offset, $entries_per_page";
$count_sql = "SELECT COUNT(*) AS total FROM category WHERE category LIKE '%$search_query%'";

$result = $conn->query($sql);
$count_result = $conn->query($count_sql);
$total_entries = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_entries / $entries_per_page);

$start_entry = ($total_entries > 0) ? ($offset + 1) : 0;
$end_entry = min(($offset + $entries_per_page), $total_entries);
$logged_in_user_type = $_SESSION['role']; // User role from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Merlie Rice Trading | Categories</title>
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?> <!-- Include the header -->
    <?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

    <div class="dashboard-container">
        <h1>Categories</h1>
        <p>Manage your item categories from this page.</p>

        <!-- Add Category Modal -->
        <div id="addCategoryModal" class="modal">
            <div class="usermodal-content">
                <span class="close" id="closeAddCategoryModal">&times;</span>
                <h2>Add New Category</h2>
                <form id="addCategoryForm" method="post">
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" required>
                    <br>
                    <button type="submit" class="modal-btn add">Add Category</button>
                </form>
            </div>
        </div>

        <!-- Delete Category Modal -->
        <div id="deleteCategoryModal" class="modal">
            <div class="usermodal-content">
                <span class="close" id="closeDeleteCategoryModal">&times;</span>
                <h2>Delete Category</h2>
                <form id="deleteCategoryForm" method="post">
                    <p>Are you sure you want to delete this category?<br>Please enter <?php if ($logged_in_user_type !== 'Clerk'): ?>your<?php endif; ?>
                        <?php if ($logged_in_user_type == 'Clerk'): ?>Administrator/Owner<?php endif; ?> credentials to confirm.</p>
                    <input type="hidden" id="delete_category_id" name="category_id">
                    <?php if ($logged_in_user_type === 'Admin' || $logged_in_user_type === 'Owner'): ?>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <button type="button" id="togglePassword" class="toggle-password category-delete-toggle-ao">Show</button>
                    <?php else: ?>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                        <br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <button type="button" id="togglePassword" class="toggle-password category-delete-toggle">Show</button>
                    <?php endif; ?>
                    <button type="submit" class="modal-btn confirm-red">Delete</button>
                </form>
            </div>
        </div>

        <!-- Categories table -->
        <table class="category-table">
            <caption style="text-align: left;">Categories List</caption>
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr id="category-<?php echo $row['cat_id']; ?>">
                            <td><?php echo $row['cat_id']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['date_time']; ?></td>
                            <td>
                                <button class="delete-category-btn modal-btn confirm-red" data-id="<?php echo $row['cat_id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No categories found.</td>
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
                    <option value="10" <?php if ($entries_per_page == 10) echo 'selected';?>>10</option>
		    <option value="25" <?php if ($entries_per_page == 25) echo 'selected'; ?>>25</option>
                    <option value="50" <?php if ($entries_per_page == 50) echo 'selected'; ?>>50</option>
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
        <button id="openAddCategoryModal" class="modal-btn add"><b style="font-size: 15px;">+ </b>Add New Category</button>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->

    <script>
        // Get the modals
        var addCategoryModal = document.getElementById('addCategoryModal');
        var deleteCategoryModal = document.getElementById('deleteCategoryModal');

        // Get the buttons that open the modals
        var openAddCategoryModal = document.getElementById('openAddCategoryModal');
        var deleteCategoryBtns = document.querySelectorAll('.delete-category-btn');

        // Get the <span> elements that close the modals
        var closeAddCategoryModal = document.getElementById('closeAddCategoryModal');
        var closeDeleteCategoryModal = document.getElementById('closeDeleteCategoryModal');

        // When the user clicks the button, open the add category modal 
        openAddCategoryModal.onclick = function() {
            addCategoryModal.style.display = 'block';
        }

        // When the user clicks on <span> (x), close the add category modal
        closeAddCategoryModal.onclick = function() {
            addCategoryModal.style.display = 'none';
        }

        // When the user clicks on <span> (x), close the delete category modal
        closeDeleteCategoryModal.onclick = function() {
            deleteCategoryModal.style.display = 'none';
        }

        // When the user clicks anywhere outside of the modals, close them
        window.onclick = function(event) {
            if (event.target === addCategoryModal) {
                addCategoryModal.style.display = 'none';
            }
            if (event.target === deleteCategoryModal) {
                deleteCategoryModal.style.display = 'none';
            }
        }

        deleteCategoryBtns.forEach(button => {
            button.addEventListener('click', () => {
                const categoryId = button.getAttribute('data-id');
                document.getElementById('delete_category_id').value = categoryId;
                deleteCategoryModal.style.display = 'block';
            });
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                togglePassword.textContent = 'Show';
            }
        });

        // Handle add category form submission
        document.getElementById('addCategoryForm').onsubmit = function(event) {
            event.preventDefault();
            const categoryName = document.getElementById('category_name').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_category.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Reload the page to see the new category
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                } else {
                    alert('Error adding category. Please try again.');
                }
            };
            xhr.send("category_name=" + encodeURIComponent(categoryName));
        };

        // Handle delete category form submission with admin password confirmation
        document.getElementById('deleteCategoryForm').onsubmit = function(event) {
            event.preventDefault();
            const categoryId = document.getElementById('delete_category_id').value;
            const username = document.getElementById('username') ? document.getElementById('username').value : '';
            const password = document.getElementById('password').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_category.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('category-' + categoryId).remove();
                        deleteCategoryModal.style.display = 'none';
                        alert('Category deleted successfully.');
                    } else {
                        alert('Incorrect credentials. Please try again.');
                    }
                } else {
                    alert('Error deleting category. Please try again.');
                }
            };
            xhr.send("category_id=" + categoryId + "&username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password));
        };
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
