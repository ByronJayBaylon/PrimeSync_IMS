<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PrimeSync_IMS/assets/Css/index.css?v=<?php echo time(); ?>">
    <title>POS System - Cart view</title>
    <style>
        .POS-container {
            padding: 20px;
        }
        .cart-table, .cart-table th, .cart-table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            text-align: left;
        }
        .item-details {
            margin-top: 20px;
        }
        .logout-btn {
            width: 10%; 
            padding: 10px; 
            background: white; 
            color: red; 
            border: none; 
            border-radius: 5px; 
            text-align: center; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }
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
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 10px;
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
        .modal-button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px;
        }
        .yes-btn {
            background-color: green;
            color: white;
        }
        .no-btn {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="POS-container usermodal-content POS-view">
        <h1>POS System - Cart view</h1>
        <div class="item-details">
            <h2>Add Item to Cart</h2>
            <input type="text" id="itemNameInput" placeholder="Item Name" oninput="searchItem()">
            <input type="number" id="quantityInput" placeholder="Quantity" min="1">
            <button onclick="addToCart()" class="modal-btn edit">Add to Cart</button>
            <button onclick="checkout()" class="modal-btn add">Checkout</button>

            <div id="itemInfo">
                <p><strong>Item Name:</strong> <span id="itemNameDetail"></span></p>
                <p><strong>Price:</strong> <span id="itemPriceDetail"></span></p>
                <p><strong>Current Stock Quantity:</strong> <span id="itemStockDetail"></span></p>
            </div>
        </div>

        <div class="total-container">
            <h2>Total: ₱<span id="totalAmount">0.00</span></h2>
                    <!-- Logout Button -->
        <button class="logout-btn modal-btn confirm-red" onclick="showLogoutModal()">Logout</button>

        </div>
        <th>
        <table class="cart-table" id="cartTable">
            <caption style="text-align: left;">Added to Cart Items</caption>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <!-- Cart items will be dynamically inserted here -->
            </tbody>
        </table>

        <!-- Logout Modal -->
        <div id="logoutModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeLogoutModal()">&times;</span>
                <p>Are you sure you want to log out?</p>
                <button class="modal-button yes-btn" onclick="confirmLogout()">Yes</button>
                <button class="modal-button no-btn" onclick="closeLogoutModal()">No</button>
            </div>
        </div>
    </div>

    <?php include '../footer.php'; ?>

    <script>
        let cart = [];
        let selectedItem = null;

        // Perform live search for items
        function searchItem() {
            const itemName = document.getElementById('itemNameInput').value;
            if (itemName.length > 0) {
                fetch(`fetch_inventory.php?query=${itemName}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const item = data[0];
                            selectedItem = item;
                            document.getElementById('itemNameDetail').textContent = item.name;
                            document.getElementById('itemPriceDetail').textContent = parseFloat(item.price).toFixed(2);
                            document.getElementById('itemStockDetail').textContent = item.stock_quantity;
                        } else {
                            selectedItem = null;
                            document.getElementById('itemNameDetail').textContent = 'N/A';
                            document.getElementById('itemPriceDetail').textContent = 'N/A';
                            document.getElementById('itemStockDetail').textContent = 'N/A';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching item:', error);
                    });
            } else {
                selectedItem = null;
                document.getElementById('itemNameDetail').textContent = '';
                document.getElementById('itemPriceDetail').textContent = '';
                document.getElementById('itemStockDetail').textContent = '';
            }
        }

        // Add item to cart
        function addToCart() {
            const quantity = parseInt(document.getElementById('quantityInput').value);

            if (selectedItem && quantity > 0) {
                const existingCartItem = cart.find(c => c.id === selectedItem.id);
                if (existingCartItem) {
                    existingCartItem.quantity += quantity;
                    existingCartItem.subtotal += selectedItem.price * quantity;
                } else {
                    cart.push({
                        id: selectedItem.id,
                        name: selectedItem.name,
                        quantity: quantity,
                        price: parseFloat(selectedItem.price),
                        subtotal: selectedItem.price * quantity
                    });
                }
                renderCart();
                updateTotal();
                clearInputs();
            } else {
                alert("Please select a valid item and quantity.");
            }
        }

        // Clear input fields after adding to cart
        function clearInputs() {
            document.getElementById('itemNameInput').value = '';
            document.getElementById('quantityInput').value = '';
            document.getElementById('itemNameDetail').textContent = '';
            document.getElementById('itemPriceDetail').textContent = '';
            document.getElementById('itemStockDetail').textContent = '';
            selectedItem = null;
        }

        // Render cart table
        function renderCart() {
            const tbody = document.querySelector('#cartTable tbody');
            tbody.innerHTML = '';
            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${item.price.toFixed(2)}</td>
                    <td>${item.subtotal.toFixed(2)}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Update total amount
        function updateTotal() {
            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        // Proceed to checkout
        function checkout() {
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.href = 'billing.php';
        }

        // Show logout modal
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        // Close logout modal
        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        // Confirm logout
        function confirmLogout() {
            window.location.href = '../logout.php';
        }
    </script>
</body>
</html>
