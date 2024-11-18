<?php
$role = $_SESSION['role']; // Get user role from session
?>

<aside id="sidebar" >
    <nav>
        <ul style="list-style-type: none; padding: 0; margin-top: 70px;">
            <?php if ($role !== 'Clerk'): ?>
                <li style="margin-bottom: 15px;">
                    <a href="dashboard.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                        <i class="fas fa-tachometer-alt"></i> <!-- Dashboard Icon -->
                        <div>Dashboard</div>
                    </a>
                </li>
            <?php endif; ?>
            <li style="margin-bottom: 15px;">
                <a href="inventory.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                    <i class="fas fa-boxes"></i> <!-- Inventory Icon -->
                    <div>Inventory</div>
                </a>
            </li>
            <li style="margin-bottom: 15px;">
                <a href="add_items.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                    <i class="fas fa-plus-square"></i> <!-- Add Items Icon -->
                    <div>Add Items</div>
                </a>
            </li>
            <li style="margin-bottom: 15px;">
                <a href="sales_report.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                    <i class="fas fa-chart-line"></i> <!-- Sales Report Icon -->
                    <div>Sales Report</div>
                </a>
            </li>
            <li style="margin-bottom: 15px;">
                <a href="categories.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                    <i class="fas fa-tags"></i> <!-- Categories Icon -->
                    <div>Categories</div>
                </a>
            </li>
            <li style="margin-bottom: 15px;">
                <a href="supplier_management.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                    <i class="fas fa-truck"></i> <!-- Supplier Icon -->
                    <div>Supplier Management</div>
                </a>
            </li>
            <?php if ($role !== 'Clerk'): ?>
                <li style="margin-bottom: 15px;">
                    <a href="user_management.php" class="sidebar-btn-green" style="text-decoration: none; display: block; padding: 15px; background: #272727; color: #fff; border-radius: 5px; text-align: center; transition: background-color 0.3s;">
                        <i class="fas fa-users"></i> <!-- User Management Icon -->
                        <div>User Management</div>
                    </a>
                </li>
            <?php endif; ?>
            <li style="margin-bottom: 15px;">
                <button type="button" class="sidebar-btn-red" onclick="showLogoutModal()" style="width: 100%; padding: 15px; background: #272727; color: #FF0000; font-size: 15px; border: none; border-radius: 5px; text-align: center; cursor: pointer; transition: background-color 0.3s;">
                    <i class="fas fa-sign-out-alt"></i> <!-- Logout Icon -->
                    <div>Log Out</div>
                </button>
            </li>
            
        </ul>
    </nav>
</aside>

<button id="sidebar-toggle" title="Toggle Sidebar">
    <i class="fas fa-bars"></i> <!-- Toggle Icon -->
</button>

<!-- Logout Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h2>Log Out</h2>
        <p>Are you sure you want to exit? Any unsaved changes will not be saved.</p>
        <button id="confirmLogout" class="modal-btn confirm-red">Yes</button>
        <button id="cancelLogout" class="modal-btn cancel">No</button>
    </div>
</div>


<script>
    /* ------------------------Start of  Sidebar Script---------------------------------------- */
// Function to show the logout modal
function showLogoutModal() {
    const modal = document.getElementById("logoutModal");
    if (!modal) return; // Check if modal exists

    const closeBtn = modal.querySelector(".close");
    const cancelBtn = document.getElementById("cancelLogout");
    
    modal.style.display = "block";

    if (closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }
    }

    if (cancelBtn) {
        cancelBtn.onclick = function() {
            modal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    const confirmLogout = document.getElementById('confirmLogout');
    if (confirmLogout) {
        confirmLogout.addEventListener('click', function() {
            window.location.href = 'logout.php'; // Redirect to logout page
        });
    }
}

// Ensure the sidebar toggle button has an event listener
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.transform === 'translateX(-240px)') {
                sidebar.style.transform = 'translateX(0)';
                this.style.left = '10px';
            } else {
                sidebar.style.transform = 'translateX(-240px)';
                this.style.left = '10px';
            }
        });
    }

    // Add active class handling
    document.querySelectorAll('#sidebar a').forEach(function(button) {
        button.addEventListener('click', function() {
            document.querySelectorAll('#sidebar a').forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Maintain active state on reload based on URL
    const currentPath = window.location.pathname.split('/').pop();
    document.querySelectorAll('#sidebar a').forEach(function(button) {
        const buttonPath = button.getAttribute('href');
        if (currentPath === buttonPath) {
            button.classList.add('active');
        }
    });

    // Attach the logout modal functionality
    const logoutButton = document.querySelector('.sidebar-btn-red');
    if (logoutButton) {
        logoutButton.addEventListener('click', showLogoutModal);
    }
});

/* ------------------------ End of Sidebar Script---------------------------------------- */
</script>