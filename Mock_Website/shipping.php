<?php
session_start();
include('templates/header.php');

// Initialize cart if empty
$_SESSION['cart'] = $_SESSION['cart'] ?? [];

// Database connection
global $conn;
if (!isset($conn)) {
    $conn = new mysqli('localhost', 'root', '', 'fastfood_db', 3307);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = (int)($_POST['item_id'] ?? 0);
    
    if (isset($_POST['increase'])) {
        $_SESSION['cart'][$item_id]['quantity']++;
    }
    elseif (isset($_POST['decrease'])) {
        if (--$_SESSION['cart'][$item_id]['quantity'] < 1) {
            unset($_SESSION['cart'][$item_id]);
        }
    }
    elseif (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$item_id]);
    }
    elseif (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'] ?? 0; // Default to 0 if not logged in
        
        foreach ($_SESSION['cart'] as $item_id => $item) {
            // Insert into orders table (id will auto-increment)
            $stmt = $conn->prepare("INSERT INTO orders (user_id, item_id, quantity, total_price) 
                                  VALUES (?, ?, ?, ?)");
            $total_price = $item['price'] * $item['quantity'];
            $stmt->bind_param('iiid', $user_id, $item_id, $item['quantity'], $total_price);
            $stmt->execute();
            $stmt->close();
        }
        
        unset($_SESSION['cart']);
        echo '<p class="success">Order placed successfully!</p>';
    }
}
?>

<div class="cart">
    <h2>Your Cart</h2>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php $total = 0; ?>
        
        <?php foreach ($_SESSION['cart'] as $item_id => $item): ?>
            <?php 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            ?>
            
            <div class="item">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p>Price: $<?= number_format($item['price'], 2) ?></p>
                
                <form method="post">
                    <input type="hidden" name="item_id" value="<?= $item_id ?>">
                    <button type="submit" name="decrease">-</button>
                    <span><?= $item['quantity'] ?></span>
                    <button type="submit" name="increase">+</button>
                    <button type="submit" name="remove">Remove</button>
                </form>
                
                <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
            </div>
        <?php endforeach; ?>
        
        <div class="total">
            <h3>Total: $<?= number_format($total, 2) ?></h3>
            <form method="post">
                <button type="submit" name="checkout">Checkout</button>
            </form>
        </div>
        
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

<style>
.cart {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
}

.item {
    padding: 10px;
    margin-bottom: 10px;
    border-bottom: 1px solid #eee;
}

button {
    padding: 5px 10px;
    margin: 0 5px;
}

.success {
    color: green;
    padding: 10px;
    background: #e8f5e9;
}
</style>

<?php include('templates/footer.php'); ?>