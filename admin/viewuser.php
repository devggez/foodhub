<?php
session_start();
    if(isset($_SESSION['username']) && isset($_SESSION['role']) && isset($_SESSION['user_id']) && !empty($_SESSION['username']) && !empty($_SESSION['role']) && !empty($_SESSION['user_id'])){
        if($_SESSION['role']=='admin'){
        	$var1=$_SESSION['user_id'];
            
			
				include '../config.php';

				function decryptData($data, $key) {
					$cipher = "aes-256-cbc";
					list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
					return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
				}

				$key = "supersecretkey";

				try {
					$dbcon = new PDO("mysql:host=$dbserver:$dbport;dbname=$db;", "$dbuser", "$dbpass");
					$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$query = "SELECT * FROM usertable";
					$stmt = $dbcon->query($query);
					$users = $stmt->fetchAll();

					echo "<h3>User Information</h3><table border='1'><tr><th>Username</th><th>Email</th><th>Phone</th><th>Address</th><th>Password</th></tr>";

					foreach ($users as $user) {
						$decrypted_email = decryptData($user['user_email'], $key);
						$decrypted_phone = decryptData($user['user_phone'], $key);
						$decrypted_address = decryptData($user['user_address'], $key);
						$decrypted_pass = decryptData($user['user_password'], $key);

						echo "<tr>
								<td>{$user['username']}</td>
								<td>{$decrypted_email}</td>
								<td>{$decrypted_phone}</td>
								<td>{$decrypted_address}</td>
								<td>{$decrypted_pass}</td>
							  </tr>";
					}

					echo "</table>";
				} catch (PDOException $ex) {
					echo "Error: " . $ex->getMessage();
				}

		}
            else{
        ?>
<script>
    window.location.assign('../login.php');

</script>
<?php
    }
        }
    else{
        ?>
<script>
    window.location.assign('../login.php');

</script>
<?php
    }
?>
