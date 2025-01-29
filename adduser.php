<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Add New User | Admin | FoodHub</title>
    <link href="../logo.jpg" rel="icon" />
    <script>
        function validateForm() {
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;

            // Phone number validation
            if (phone.length < 10 || isNaN(phone)) {
                alert("Phone number must be at least 10 digits and contain only numbers.");
                return false;
            }

            // Password validation
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                alert("Password must be at least 8 characters long and include a capital letter, lowercase letter, number, and special character.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    
    <br>
    <h3>FoodHub User Registration</h3>
    <form action="registeruser.php" method="POST" onsubmit="return validateForm();">
        User Name: <input type="text" name="uname" required><br><br>
        Email: <input type="email" name="uemail" required><br><br>
        Phone: <input type="text" id="phone" name="uphone" required><br><br>
        Password: <input type="password" id="password" name="upass" required><br><br>
        Address: <input type="text" name="uaddress"><br><br>
        Position:
        <input type="radio" id="admin" name="upos" value="admin" required>
        <label for="admin">Admin</label>
        <input type="radio" id="manager" name="upos" value="manager" required>
        <label for="manager">Manager</label>
        <input type="radio" id="customer" name="upos" value="customer" required>
        <label for="customer">Customer</label>
        <br><br>
        <input type="submit" value="Register">
    </form>
</body>

</html>
