<?php
session_start();

include 'templates/header.php';


if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to access this page.");
}

$userID = $_SESSION['user_id'];


// Connect to DB
$mysqli = new mysqli("localhost", "root", "", "fastfood_db", 3307);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submit
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['userID'];
    $full_name = $mysqli->real_escape_string($_POST['full_name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $country = $mysqli->real_escape_string($_POST['country']);

    $query = "INSERT INTO contacts (userID, full_name, email, country) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("isss", $userID, $full_name, $email, $country);
    
    if ($stmt->execute()) {
        $message = "Contact information submitted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Form</title>
</head>
<body>
    <h1>Contact Form</h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="full_name">Full Name:</label><br>
        <input type="text" name="full_name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="country">Country:</label><br>
        <input type="text" name="country" required><br><br>

        <input type="submit" value="Submit">
        <br><br>
    </form>
</body>
</html>

<?php include 'templates/footer.php'; ?>