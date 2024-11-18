<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Merlie Rice Trading | POS System</title>
</head>
<body>
    <?php include '../header.php'; ?> <!-- Include the header -->

    <div class="POS-container">
        <h1>Point of Sale</h1>
        <p>This is for the Point of Sale System Page<br>
        The POS system will be fully usable when the other modules are done in the making.</p>
        <br>
        <br>
        <form action="../logout.php" method="post" onsubmit="return confirmLogout();" style="margin: 0;">
                    <button type="submit" class="sidebar-btn-red" style="width: 10%; padding: 10px; background: white; color: red; border: none; border-radius: 5px; text-align: center; cursor: pointer; transition: background-color 0.3s;">
                        <i class="fas fa-sign-out-alt"></i> <!-- Logout Icon -->
                        <div>Log Out</div>
                    </button>
                </form>
    </div>

    <?php include '../footer.php'; ?> <!-- Include the footer -->
</body>
</html>
