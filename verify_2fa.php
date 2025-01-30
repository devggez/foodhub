<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];

    if ($_SESSION['otp'] == $user_otp && time() < $_SESSION['otp_expiry']) {
        $_SESSION['authenticated'] = true; // Mark as authenticated
        unset($_SESSION['otp']); // Remove OTP from session
        header("Location: dashboard.php"); // Redirect to the main dashboard
        exit();
    } else {
        echo "Invalid or expired OTP. Please try again.";
    }
}
?>