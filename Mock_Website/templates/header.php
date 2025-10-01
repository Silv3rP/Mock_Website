<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Los Boyos Hermanos </title>
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    
    <li><a href="index.php"><h1>Los Boyos Hermanos</h1></a>
    <nav>
        <ul>
            
        <img src="http://localhost/Project_Website/images/home.svg" alt="HomePage" class="home">
            <li><a href="index.php">Home</a></li>
            <img src="http://localhost/Project_Website/images/search.svg" alt="search" class="search">
            <li><a href="search.php">Search</a></li>
            <img src="http://localhost/Project_Website/images/info-sign.svg" alt="about" class="about">
            <li><a href="about.php">About Us</a></li>
            <img src="http://localhost/Project_Website/images/calls.svg" alt="contact" class="contact">
            <li><a href="contact.php">Contact</a></li>
            <img src="http://localhost/Project_Website/images/user.svg" alt="User Icon" class="user-icon">
            <li><a href="auth.php">Login / Signup</a></li>           

            <img src="http://localhost/Project_Website/images/logout.svg" alt="logout" class="logout">
            <li><a href="auth.php?logout=true">Logout</a></li>




            
        </ul>
    </nav>
    <br>

    <button onclick="toggleDarkMode()">🌙 Dark Mode</button>

<script>
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
}
</script>
     
</header>
