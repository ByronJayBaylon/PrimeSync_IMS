<?php
session_start();
include '../db_connector.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode($_POST['cart'], true);
    $totalAmount = floatval($_POST['totalAmount']);
    $payment = floatval($_POST['payment']);
    
    if ($payment >= $totalAmount) {
        // Update inventory
        foreach ($cart as $item) {
            $stmt = $conn->prepare("UPDATE items SET StockQuantity = StockQuantity - ? WHERE item_id = ?");
            $stmt->bind_param('ii', $item['quantity'], $item['id']);
            $stmt->execute();
        }
        
        // Notify user about successful transaction
        echo "<script>alert('Transaction successful!'); window.location.href = 'POS.php';</script>";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>Billing</title>
    <style>
        .billing-container {
            padding: 20px;
        }
        .billing-details {
            margin-top: 20px;
        }
        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .btn-cancel {
            margin-left: 10px;
            background-color: red;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="billing-container usermodal-content">
        <h1>Billing</h1>
        
        <div class="billing-details">
            <h2>Total: ₱<span id="totalAmount"></span></h2>
            <input type="number" id="paymentInput" placeholder="Enter payment amount" oninput="calculateChange()" min="0">
            <p><strong>Change:</strong> ₱<span id="changeAmount">0.00</span></p>
            <form id="billingForm" method="POST">
                <input type="hidden" name="cart" id="cartInput">
                <input type="hidden" name="totalAmount" id="totalAmountInput">
                <input type="hidden" name="payment" id="paymentInputHidden">
                <button type="submit" id="payButton" class="disabled" disabled>Pay Amount</button>
                <button type="button" class="btn-cancel modal-btn confirm-red" onclick="cancelTransaction()">Cancel</button>
            </form>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <script>
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalAmount = cart.reduce((sum, item) => sum + item.subtotal, 0);
        document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
        document.getElementById('totalAmountInput').value = totalAmount.toFixed(2);
        document.getElementById('cartInput').value = JSON.stringify(cart);

        function calculateChange() {
            const payment = parseFloat(document.getElementById('paymentInput').value);
            const change = payment - totalAmount;
            document.getElementById('changeAmount').textContent = change >= 0 ? change.toFixed(2) : '0.00';
            document.getElementById('paymentInputHidden').value = payment.toFixed(2);

            const payButton = document.getElementById('payButton');
            if (payment >= totalAmount) {
                payButton.classList.remove('disabled');
                payButton.removeAttribute('disabled');
            } else {
                payButton.classList.add('disabled');
                payButton.setAttribute('disabled', 'disabled');
            }
        }

        function cancelTransaction() {
            if (confirm("Are you sure you want to cancel the transaction?")) {
                window.location.href = 'POS.php';
            }
        }
    </script>
</body>
</html>
