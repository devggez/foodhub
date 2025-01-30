01 Data Masking: Hide sensitive data in logs and non-essential views.

<?php
// Function to mask sensitive data
function maskSensitiveData($data, $maskType = 'default') {
    if ($maskType === 'email') {
        // Mask email addresses
        return preg_replace('/^(.)(.*)(.@.*)$/', '$1****$3', $data);
    } elseif ($maskType === 'phone') {
        // Mask phone numbers (e.g., keep last 4 digits visible)
        return preg_replace('/^(\d{3})(\d{2})(\d{2})(\d{4})$/', '$1-****-$4', $data);
    } elseif ($maskType === 'credit_card') {
        // Mask credit card numbers (show last 4 digits)
        return preg_replace('/^(.{0,12})(\d{4})$/', '**** **** **** $2', $data);
    } else {
        // Generic mask (e.g., replace all but first and last characters with *)
        return substr($data, 0, 1) . str_repeat('*', strlen($data) - 2) . substr($data, -1);
    }
}

// Example sensitive data
$userData = [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'phone' => '1234567890',
    'credit_card' => '4111111111111111',
];

// Mask sensitive data
$maskedData = [
    'name' => $userData['name'], // Keep name unmasked
    'email' => maskSensitiveData($userData['email'], 'email'),
    'phone' => maskSensitiveData($userData['phone'], 'phone'),
    'credit_card' => maskSensitiveData($userData['credit_card'], 'credit_card'),
];

// Log or display the masked data
echo "Masked Data:\n";
print_r($maskedData);

// Log Example (using file_put_contents for demonstration)
$logMessage = json_encode($maskedData) . PHP_EOL;
file_put_contents('masked_logs.txt', $logMessage, FILE_APPEND);

echo "Logs written to 'masked_logs.txt'.";
?>



02 Encrypted Data Storage: Prevent unauthorized aggregation of sensitive data.

<?php
session_start();

// Function to log suspicious activity
function logSuspiciousActivity($userId, $reason) {
    $logMessage = sprintf(
        "[%s] Suspicious activity detected for User ID: %s - Reason: %s\n",
        date('Y-m-d H:i:s'),
        $userId,
        $reason
    );
    file_put_contents('suspicious_logs.txt', $logMessage, FILE_APPEND);
}

// Function to isolate a session
function isolateSession($sessionId) {
    // Block access to further actions
    $_SESSION['isolated'] = true;

    // Notify admin or security team (example: send an email)
    mail(
        "admin@example.com",
        "Suspicious Session Isolated",
        "Session ID $sessionId has been isolated due to suspicious activity."
    );
}

// Example: Monitor user activity
function monitorUserActivity() {
    $userId = $_SESSION['user_id'] ?? 'anonymous';
    $sessionId = session_id();

    // Example criteria for suspicious activity
    $maxRequestsPerMinute = 60;
    $requestTime = time();

    // Track requests in session
    if (!isset($_SESSION['request_count'])) {
        $_SESSION['request_count'] = 0;
        $_SESSION['first_request_time'] = $requestTime;
    }

    $_SESSION['request_count']++;

    // Check request rate
    $elapsedTime = $requestTime - $_SESSION['first_request_time'];
    if ($elapsedTime <= 60 && $_SESSION['request_count'] > $maxRequestsPerMinute) {
        logSuspiciousActivity($userId, "Excessive requests detected.");
        isolateSession($sessionId);

        // Show a warning page or terminate the session
        die("Your session has been isolated due to suspicious activity. Please contact support.");
    }

    // Reset request count after 1 minute
    if ($elapsedTime > 60) {
        $_SESSION['request_count'] = 1;
        $_SESSION['first_request_time'] = $requestTime;
    }
}

// Call the monitoring function at the start of each request
monitorUserActivity();

echo "User activity is being monitored.";
?>



03ta Minimization Policies: Only collect and retain necessary data.


<?php
session_start();

// Function to log anomalies
function logAnomaly($userId, $reason) {
    $logMessage = sprintf(
        "[%s] Anomaly detected for User ID: %s - Reason: %s\n",
        date('Y-m-d H:i:s'),
        $userId,
        $reason
    );
    file_put_contents('anomaly_logs.txt', $logMessage, FILE_APPEND);
}

// Function to detect anomalies
function detectAnomalies() {
    $userId = $_SESSION['user_id'] ?? 'anonymous';
    $currentIp = $_SERVER['REMOTE_ADDR'];
    $currentRequestTime = time();
    $sessionId = session_id();

    // Initialize session tracking variables
    if (!isset($_SESSION['anomaly_tracking'])) {
        $_SESSION['anomaly_tracking'] = [
            'ip' => $currentIp,
            'last_request_time' => $currentRequestTime,
            'request_count' => 0,
            'login_attempts' => 0,
        ];
    }

    $tracking = &$_SESSION['anomaly_tracking'];

    // Detect unusual IP changes
    if ($tracking['ip'] !== $currentIp) {
        logAnomaly($userId, "IP address changed from {$tracking['ip']} to $currentIp.");
        $tracking['ip'] = $currentIp;
    }

    // Detect excessive requests (e.g., more than 100 requests in 1 minute)
    $elapsedTime = $currentRequestTime - $tracking['last_request_time'];
    $tracking['request_count']++;
    if ($elapsedTime <= 60 && $tracking['request_count'] > 100) {
        logAnomaly($userId, "Excessive request rate detected (more than 100 requests per minute).");
        blockUserSession($sessionId);
    }

    // Reset request count after 1 minute
    if ($elapsedTime > 60) {
        $tracking['request_count'] = 1;
        $tracking['last_request_time'] = $currentRequestTime;
    }

    // Detect multiple failed login attempts
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $isLoginSuccessful = simulateLoginCheck($_POST['username'], $_POST['password']);
        if (!$isLoginSuccessful) {
            $tracking['login_attempts']++;
            logAnomaly($userId, "Failed login attempt #" . $tracking['login_attempts']);
        }
        if ($tracking['login_attempts'] > 5) {
            logAnomaly($userId, "Multiple failed login attempts detected (more than 5).");
            blockUserSession($sessionId);
        }
    }
}

// Function to simulate a login check (replace with actual authentication logic)
function simulateLoginCheck($username, $password) {
    $validUsers = [
        'user1' => 'password123',
        'user2' => 'password456',
    ];
    return isset($validUsers[$username]) && $validUsers[$username] === $password;
}

// Function to block a user session
function blockUserSession($sessionId) {
    $_SESSION['blocked'] = true;

    // Notify admin or security team
    mail(
        "admin@example.com",
        "Blocked Session",
        "Session ID $sessionId has been blocked due to anomalous behavior."
    );

    // Display a block message and terminate the session
    die("Your session has been blocked due to detected anomalous behavior. Please contact support.");
}

// Call the anomaly detection function
detectAnomalies();

echo "User behavior is being monitored.";
?>




05 Activity Monitoring and Alerts: Detect patterns of bulk data access or downloads.

<?php
session_start();

// Function to log activity
function logActivity($userId, $resource, $action) {
    $logMessage = sprintf(
        "[%s] User ID: %s accessed resource: %s, action: %s\n",
        date('Y-m-d H:i:s'),
        $userId,
        $resource,
        $action
    );
    file_put_contents('activity_logs.txt', $logMessage, FILE_APPEND);
}

// Function to detect bulk access patterns
function detectBulkAccess($userId) {
    $currentRequestTime = time();
    $thresholdRequests = 50; // Maximum allowed requests in the time frame
    $timeFrame = 300; // Time frame in seconds (e.g., 5 minutes)

    // Initialize session tracking
    if (!isset($_SESSION['activity_monitor'])) {
        $_SESSION['activity_monitor'] = [
            'requests' => [],
        ];
    }

    // Record the current request
    $_SESSION['activity_monitor']['requests'][] = $currentRequestTime;

    // Remove outdated requests
    $_SESSION['activity_monitor']['requests'] = array_filter(
        $_SESSION['activity_monitor']['requests'],
        fn($timestamp) => ($currentRequestTime - $timestamp) <= $timeFrame
    );

    // Count recent requests
    $recentRequests = count($_SESSION['activity_monitor']['requests']);

    // Trigger an alert if the request threshold is exceeded
    if ($recentRequests > $thresholdRequests) {
        triggerAlert($userId, $recentRequests, $timeFrame);
    }
}

// Function to trigger an alert
function triggerAlert($userId, $requestCount, $timeFrame) {
    $alertMessage = sprintf(
        "ALERT: User ID: %s made %d requests in %d seconds, indicating potential bulk data access.\n",
        $userId,
        $requestCount,
        $timeFrame
    );

    // Log the alert
    file_put_contents('alerts.txt', $alertMessage, FILE_APPEND);

    // Notify admin (example: send email)
    mail(
        "admin@example.com",
        "Bulk Data Access Detected",
        $alertMessage
    );

    // Optionally block or throttle the user session
    $_SESSION['blocked'] = true;
    die("Your session has been blocked due to excessive activity. Please contact support.");
}

// Example usage: Monitor resource access
$userId = $_SESSION['user_id'] ?? 'anonymous';
$resource = $_GET['resource'] ?? 'unknown';
$action = $_GET['action'] ?? 'view';

// Log the activity
logActivity($userId, $resource, $action);

// Monitor for bulk data access
detectBulkAccess($userId);

echo "Activity is being monitored.";
?>

