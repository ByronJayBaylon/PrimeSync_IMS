<?php
include 'db_connector.php'; // Include database connection
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // Get user role from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merlie Rice Trading IMS</title>
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/PrimeSync_IMS/assets/JavaScript/IMS_script.js?v=<?php echo time(); ?>"></script>
</head>
<body>
<header class="header">
    <?php
    date_default_timezone_set('Asia/Manila'); // Set to your desired timezone
    $cur_date = date('l, F j, Y h:i:s A'); // Include time in the format h:i:s A
    $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
    ?>
    <p style="float: right; padding-right: 15px; margin-top: 15px;" id="date-time"><?php echo $cur_date ?> | <?php echo $username ?> | <?php echo $role?></p>
    <div style="padding-left: 20px; padding-top: 5px; display: flex; align-items: center;">
        <img src="/PrimeSync_IMS/assets/images/MRT_Logo - Dark.png" alt="Inventory System Logo" style="width: 60px; height: 60px; margin-right: 15px; margin-bottom: 50px;"> <!-- Add your logo here -->
        <h4 style="margin-top: -25px; font-size: 1.5rem;">Merlie Rice Trading</h4>
    </div>
</header>

<script>
     /* ------------------Header Script --------------------------*/
     function updateTime() {
        var dateTimeElement = document.getElementById('date-time');
        var now = new Date();
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        dateTimeElement.textContent = now.toLocaleDateString('en-US', options) + ' | <?php echo $username ?>' + ' (<?php echo $role ?>)';
    }

    setInterval(updateTime, 1000); // Update every second
    updateTime(); // Initial call to set the time immediately
    /* ------------------End of Header Script ---------------------------*/
</script>
