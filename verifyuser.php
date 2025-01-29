<?php
include 'config.php';

function decryptData($data, $key) {
    $cipher = "aes-256-cbc";
    list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}

$key = "supersecretkey"; // Same key used for encryption

if (isset($_POST['uname']) && isset($_POST['upass']) && !empty($_POST['uname']) && !empty($_POST['upass'])) {
    $username = $_POST['uname'];
    $password = $_POST['upass'];

    try {
        $dbcon = new PDO("mysql:host=$dbserver:$dbport;dbname=$db;", "$dbuser", "$dbpass");
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM usertable WHERE username = :username";
        $stmt = $dbcon->prepare($query);
        $stmt->execute([':username' => $username]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch();

            // Verify the password
            if (password_verify($password, $user['user_password'])) {
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['user_type'];
                $_SESSION['user_id'] = $user['user_id'];

                // Redirect based on user role
                if ($user['user_type'] == 'admin') {
                    header('Location: admin/');
                } elseif ($user['user_type'] == 'manager') {
                    header('Location: manager/');
                } elseif ($user['user_type'] == 'customer') {
                    header('Location: customer/');
                }
                exit;
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Invalid username!";
        }
    } catch (PDOException $ex) {
        echo "Database error: " . $ex->getMessage();
    }
} else {
    echo "Please provide username and password!";
}
?>
