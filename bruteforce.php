<?php

$query = "SELECT * FROM users WHERE username='$uname' AND password='$upass'";
$result = mysqli_query($conn, $query);

session_start();
include('firewall.php'); // Include Firewall
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['uname'];
    $upass = $_POST['upass'];

    // Brute-force prevention
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    if ($_SESSION['login_attempts'] >= 5) {
        die("Too many failed login attempts. Try again in 5 minutes.");
    }

    $query = "SELECT * FROM users WHERE username='$uname' AND password='$upass'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['login_attempts'] = 0; // Reset login attempts on success
        $_SESSION['authenticated'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['login_attempts']++; // Increase failed login count
        echo "Incorrect username or password.";
    }
}
?>
