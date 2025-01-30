<?php
session_start();

// Block common attack patterns
function is_malicious_request($input) {
    $patterns = [
        "/<script>/i",  // Prevent XSS
        "/SELECT .* FROM/i", // SQL Injection
        "/DROP TABLE/i",
        "/UNION SELECT/i",
        "/INSERT INTO/i",
        "/DELETE FROM/i",
        "/<\?php/i",  // Prevent PHP code injection
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            return true;
        }
    }
    return false;
}

// Check all GET, POST, and COOKIE data
foreach ($_GET as $key => $value) {
    if (is_malicious_request($value)) {
        die("Access Denied");
    }
}

foreach ($_POST as $key => $value) {
    if (is_malicious_request($value)) {
        die("Access Denied");
    }
}

foreach ($_COOKIE as $key => $value) {
    if (is_malicious_request($value)) {
        die("Access Denied");
    }
}

// Prevent brute-force attacks by limiting login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= 5) {
    die("Too many login attempts");
}

// Prevent IP-based attacks by blocking known malicious IPs
$blocked_ips = ['192.168.1.100', '123.456.789.000']; // Add more IPs here
if (in_array($_SERVER['REMOTE_ADDR'], $blocked_ips)) {
    die("Access Denied");
}

// Log all IP addresses accessing the website (for monitoring)
$file = fopen("logs/traffic.log", "a");
fwrite($file, date("Y-m-d H:i:s") . " - " . $_SERVER['REMOTE_ADDR'] . "\n");
fclose($file);
?>
