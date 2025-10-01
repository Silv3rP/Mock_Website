<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Ensure correct path to User.php
require_once __DIR__ . '/classes/User.php';

$conn = new mysqli("localhost", "root", "", "los_boyos_hermanos", 3307);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user = new App\User($conn);
$message = "";

// Handle Login
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    // Custom validation
    if (!App\User::validateEmail($email)) {
        echo '<script>alert("Invalid email format.");</script>';
        $message = "Invalid email format.";
    } elseif (!App\User::validatePassword($password)) {
        echo '<script>alert("Password must be at least 8 characters, include upper and lower case letters, a number, and a special character.");</script>';
        $message = "Password must be at least 8 characters, include upper and lower case letters, a number, and a special character.";
    } else {
        $userData = $user->login($email, $password);
        if ($userData) {
            $_SESSION["user_id"] = $userData["id"];
            $_SESSION["role"] = $userData["role"];
            if ($userData["role"] === "admin") {
                echo '<script>alert("You have successfully logged in as (ADMIN)");window.location.href = "index.php";</script>';
            } else {
                echo '<script>alert("You have successfully logged in as (USER)");window.location.href = "index.php";</script>';
            }
            exit();
        } else {
            echo '<script>alert("Invalid credentials!");</script>';
            $message = "Invalid credentials!";
        }
    }
}

// Handle Signup
if (isset($_POST["signup"])) {
    $email = $_POST["email"];
    $passwordRaw = $_POST["password"];
    $role = $_POST["role"];
    // Custom validation
    if (!App\User::validateEmail($email)) {
        echo '<script>alert("Invalid email format.");</script>';
        $message = "Invalid email format.";
    } elseif (!App\User::validatePassword($passwordRaw)) {
        echo '<script>alert("Password must be at least 8 characters, include upper and lower case letters, a number, and a special character.");</script>';
        $message = "Password must be at least 8 characters, include upper and lower case letters, a number, and a special character.";
    } elseif (!App\User::validateRole($role)) {
        echo '<script>alert("Invalid role selected.");</script>';
        $message = "Invalid role selected.";
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo '<script>alert("Email already exists.");</script>';
            $message = "Email already exists.";
        } else {
            $password = password_hash($passwordRaw, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $password, $role);
            if ($stmt->execute()) {
                echo '<script>alert("Signup successful! Please log in.");</script>';
                $message = "Signup successful! Please log in.";
            } else {
                echo '<script>alert("Error: ' . addslashes($stmt->error) . '");</script>';
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle Logout
 if (isset($_GET["logout"])) {
    session_destroy();
    echo '<script>
        alert("You have been logged out.");
        window.location.href = "auth.php";
    </script>';
    
    exit();
}

$conn->close();
?>


<?php include 'templates/header.php'; ?>
<h1>Authentication</h1>
<p><?php echo $message; ?></p>




<div id="loginForm">
    <form method="POST">
    
    
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
        <button onclick="showSignup()">Signup</button><br><br>
    </form>
</div>

<div id="signupForm" style="display: none;">
    <form method="POST">
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>


        <label><input type="radio" name="role" value="user" checked> User</label>
        <label><input type="radio" name="role" value="admin"> Admin</label><br><br>

        <button type="submit" name="signup">Sign Up</button><br><br>
    </form>
    
</div>
</h4>

<?php if (isset($_SESSION["user_id"])): ?>
    
<?php endif; ?>

<script>
    function showLogin() {
        document.getElementById("loginForm").style.display = "block";
        document.getElementById("signupForm").style.display = "none";
    }

    function showSignup() {
        document.getElementById("signupForm").style.display = "block";
        document.getElementById("loginForm").style.display = "none";
    }
</script>



<?php include 'templates/footer.php'; ?>
