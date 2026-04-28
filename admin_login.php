<?php
session_start();
require 'config.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $admin = $result->fetch_assoc();

        // Verify hashed password
        if(password_verify($password, $admin['password'])){
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_orders.php");
            exit();
        } else {
            $error = "Wrong Password!";
        }
    } else {
        $error = "Admin Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{font-family:Arial;text-align:center;padding-top:100px;background:#f4f4f4;}
form{display:inline-block;padding:20px;background:white;border-radius:10px;}
input{display:block;margin:10px auto;padding:8px;width:200px;}
button{padding:8px 20px;background:#4CAF50;color:white;border:none;}
.forgot-btn{
    display:inline-block;
    margin-top:15px;
    color:#4CAF50;
    text-decoration:none;
    font-weight:bold;
}

.forgot-btn:hover{
    text-decoration:underline;
}
</style>
</head>
<body>

<h2>Owner Login</h2>

<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
<br><br>
<a href="forgot_password.php" class="forgot-btn">Forgot Password?</a></form>

<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

</body>
</html>
