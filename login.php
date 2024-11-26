<?php
session_start();
include 'db_connector.php'; // Include the database connection script

// Constants for the duration of lockout and maximum login attempts
define("LOCK_DURATION", 10); // in seconds
define("MAX_ATTEMPTS", 3);

if (!isset($_SESSION['failedAttempts'])) {
    $_SESSION['failedAttempts'] = 0;
}

$lockTime = $_SESSION['lockTime'] ?? 0;
$remainingLockTime = max(0, LOCK_DURATION - (time() - $lockTime));
$lockedOut = $remainingLockTime > 0;
$loginSuccess = false;
$errorMessage = "";

// Reset attempts after lockout period ends
if (!$lockedOut && isset($_SESSION['lockTime'])) {
    $_SESSION['failedAttempts'] = 0;
    unset($_SESSION['lockTime']);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$lockedOut) {
    if (isset($_POST['login']) && $_POST['login'] === 'true') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Query the database for the user
        $sql = "SELECT * FROM accounts WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $loginSuccess = true;
            $_SESSION['failedAttempts'] = 0;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['account_type']; // Store user role in session

            // Redirect based on user role
            if ($user['account_type'] === 'Admin' || $user['account_type'] === 'Owner') {
                header('Location: dashboard.php');
            } elseif ($user['account_type'] === 'Clerk') {
                header('Location: /PrimeSync_IMS/inventory.php');
            }else {
                header('Location: /PrimeSync_IMS/cashier_view_POS/POS.php');
            }
            exit();
        } else {
            $_SESSION['failedAttempts']++;
            if ($_SESSION['failedAttempts'] >= MAX_ATTEMPTS) {
                $_SESSION['lockTime'] = time();
                $lockedOut = true;
                $remainingLockTime = LOCK_DURATION;
                $_SESSION['failedAttempts'] = 0;
                $errorMessage = "Too many failed login attempts.";
            } else {
                $errorMessage = "Wrong username or password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,400;0,600;1,400&display=swap" />
    <title>Merlie Rice Trading IMS | Login</title>
</head>
<body class="login-body">
    <div class="log-in-page">
        <div class="log-in-display">Log-In.</div>
        
        <div class="form-container">
            <form method="post" id="loginForm">
                <input type="hidden" name="login" value="true"> <!-- Hidden input to indicate form submission -->
                <label for="username" class="form-label">User Name</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="Enter your username" 
                       <?php echo $lockedOut ? 'disabled' : ''; ?>> <!-- Disable input if locked out -->

                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password"
                           <?php echo $lockedOut ? 'disabled' : ''; ?>> <!-- Disable input if locked out -->
                    <button type="button" id="togglePassword" class="toggle-password">Show</button>
                </div>
                
                <button type="submit" class="btn-login" <?php echo $lockedOut ? 'disabled' : ''; ?>>LogIn</button> <!-- Disable button if locked out -->
            </form>
        </div>

        <div class="image-backdrop"></div>
        <img class="primesync-logo-main" alt="" src="/PrimeSync_IMS/assets/images/PrimeSync Logo - Main.png">
        <img class="primesync-logo-backdrop" alt="" src="/PrimeSync_IMS/assets/images/PrimeSync Logo - Backdrop.png">
        
        <div class="ims-display">
            <div class="merlies-rice-trading">Merlie Rice Trading<br><p style="font-size: 24px; margin: 0; margin-top: -10px;">Inventory Management System</p></div>
            <div class="mrt">MRT</div>
        </div>

        <div class="copyright-text">Copyright © 2024, PrimeSync Solutions. All Rights Reserved.</div>

        <?php if ($lockedOut || $errorMessage): ?>
            <i class="attempt-warning" id="warning-message">
                <p class="too-many-failed"><?php echo htmlspecialchars($errorMessage); ?>
                <?php if ($lockedOut): ?><br>Try again after <span id="timer"><?php echo $remainingLockTime; ?></span> seconds.<?php endif; ?>
                </p>
            </i>
        <?php endif; ?>
    </div>

    <script>
// JavaScript countdown for lock timer
<?php if ($lockedOut): ?> // Check if the user is locked out
        let remainingTime = <?php echo $remainingLockTime; ?>; // Get the remaining lock time in seconds from the server-side variable
        const timer = document.getElementById("timer"); // Get the HTML element with the ID "timer" to display the countdown

        const countdown = setInterval(() => { // Start a countdown function that runs every second
            remainingTime--; // Decrease the remaining time by one second
            timer.textContent = remainingTime; // Update the timer display in the HTML element

            if (remainingTime <= 0) { // Check if the remaining time is zero or less
                clearInterval(countdown); // Stop the countdown function
                location.reload(); // Reload the page to reset the lock status
            }
        }, 1000); // Set the interval to run every 1000 milliseconds (1 second)
        // Prevent form submission if lockout period has ended
        document.getElementById('loginForm').addEventListener('submit', function (e) { // Add an event listener to the form's submit event
            if (remainingTime <= 0) { // Check if the remaining time is zero or less
                e.preventDefault(); // Prevent the form from being submitted
                <?php $_SESSION['failedAttempts'] = 0; ?> // Reset the failed login attempts on form reload
            }
        });
<?php endif; ?> // End the PHP if statement

// Toggle Password Visibility
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Show' : 'Hide';
});

    </script>
</body>
</html>
