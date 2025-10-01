<?php
session_start();
include 'templates/header.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to access this page.");
}

$userID = $_SESSION['user_id'];

// add-to-cart form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $_SESSION['cart'][] = [
        'name' => $_POST['item_name'],
        'price' => (float)$_POST['item_price'],
        'quantity' => (int)$_POST['quantity']
    ];
    header("Location: search.php"); // Redirect to avoid resubmission
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        h2 { text-align: center; }
        form { text-align: center; }
        input { padding: 15px; }
        .search-results { text-align: center; margin-top: 20px; }
        .hidden { display: none; }
        .cart-item { margin: 5px 0; }
        .quantity-input { width: 50px; }
        div img { width: 200px; height: 200px; vertical-align: middle; display: inline-block; text-align: center; padding: 15px; }
        label { padding-right: 125px; text-align: right; }
        span { font-style: italic; color: red; }
    </style>
</head>
<body>

<h2>Search Our Menu</h2>

<!-- Display the cart -->
<?php if (!empty($_SESSION['cart'])): ?>
    <div class="search-results">
        <h3>Your Cart:</h3>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <p class="cart-item">
                <?= htmlspecialchars($item['name']) ?> -
                <?= $item['quantity'] ?> x $<?= number_format($item['price'], 2) ?>
            </p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Search bar -->
<form action="search.php" method="GET">
    <input type="text" name="query" placeholder="Enter a food item..." required>
    <button type="submit" class="search-button">
        <i class="fas fa-search"></i> Search
    </button>
</form>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fastfood_db", 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search query
if (isset($_GET['query'])) {
    $search = $conn->real_escape_string($_GET['query']);
    echo "<p class='search-results'>Showing results for: <strong>" . htmlspecialchars($search) . "</strong></p>";

    $sql = "SELECT * FROM fast_food WHERE item_name LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='search-results'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ddd;'>
                    <h3>" . htmlspecialchars($row['item_name']) . "</h3>
                    <p>$" . number_format($row['price'], 2) . "</p>
                    <form method='POST'>
                        <input type='number' name='quantity' value='1' min='1' class='quantity-input'>
                        <input type='hidden' name='item_name' value='" . htmlspecialchars($row['item_name']) . "'>
                        <input type='hidden' name='item_price' value='" . $row['price'] . "'>
                        <button type='submit' name='add_to_cart' class='search-button'>Add to Cart</button>
                    </form>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<p class='search-results'>No results found for '" . htmlspecialchars($search) . "'</p>";
    }
}

$conn->close();
?>

<!-- Shipping Button -->
<?php if (!empty($_SESSION['cart'])): ?>
    <div style="text-align:center; margin-top:20px;">
        <a href="shipping.php" class="search-button">Proceed to Shipping</a>
    </div>
<?php endif; ?>

<br>
<h2>Our Top 5 Rated Meals!</h2>
<div class="image-gallery" style="text-align:center;">
    <img src="images/bucket.png" alt="Chicken Bucket">
    <img src="images/wings.jpg" alt="Wings">
    <img src="images/cb.jpg" alt="Chicken Burger">
    <img src="images/beef.jpg" alt="Beef Burger">
    <img src="images/wings2.jpg" alt="Spicy Wings">
    <span class="warning-label">SpicyWings!</span>
</div>

<br>

<?php include 'templates/footer.php'; ?>
</body>
</html>
