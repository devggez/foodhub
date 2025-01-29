<?php
include 'config.php';

function encryptData($data, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($encrypted . "::" . $iv);
}

$key = "supersecretkey"; // Use a secure key

if (isset($_POST['uname'], $_POST['uemail'], $_POST['uphone'], $_POST['upass'], $_POST['upos'])) {
    $uname = $_POST['uname'];
    $uemail = encryptData($_POST['uemail'], $key);
    $uphone = encryptData($_POST['uphone'], $key);
    $upass = password_hash($_POST['upass'], PASSWORD_DEFAULT); // Store hashed passwords securely
    $upos = $_POST['upos'];
    $uaddress = encryptData($_POST['uaddress'], $key);

    try {
        $dbcon = new PDO("mysql:host=$dbserver:$dbport;dbname=$db;", "$dbuser", "$dbpass");
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO usertable (username, user_email, user_phone, user_password, user_type, user_address) 
                  VALUES (:uname, :uemail, :uphone, :upass, :upos, :uaddress)";

        $stmt = $dbcon->prepare($query);
        $stmt->execute([
            ':uname' => $uname,
            ':uemail' => $uemail,
            ':uphone' => $uphone,
            ':upass' => $upass,
            ':upos' => $upos,
            ':uaddress' => $uaddress
        ]);

        echo "User added successfully.";
    } catch (PDOException $ex) {
        echo "Error: " . $ex->getMessage();
    }
}
?>
