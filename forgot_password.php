<?php
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if(isset($_POST['send'])){

    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("UPDATE admin SET reset_token=?, token_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        $mail = new PHPMailer(true);

        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shrivastavauttam699@gmail.com';
            $mail->Password = 'jevc ztge nmde ylpp';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yourgmail@gmail.com', 'Restaurant Admin');
            $mail->addAddress($email);

            $mail->Subject = 'Password Reset Link';
            $mail->Body = "Click link to reset password:
http://localhost/Restaurant_mail/reset_password.php?token=$token";

            $mail->send();

            $success = "Reset link sent to your email!";

        } catch (Exception $e) {
            $error = "Email sending failed.";
        }

    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<style>
body{
    font-family: Arial;
    background:#f4f4f4;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px;
    width:350px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.box h2{
    margin-bottom:20px;
}

.box input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:5px;
}

.box button{
    width:100%;
    padding:10px;
    background:#4CAF50;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-weight:bold;
}

.box button:hover{
    background:#45a049;
}

.back-link{
    display:block;
    margin-top:15px;
    color:#4CAF50;
    text-decoration:none;
}

.back-link:hover{
    text-decoration:underline;
}
.success{
    background:#e6f9e6;
    color:#2e7d32;
    padding:10px;
    margin-bottom:15px;
    border-radius:5px;
    font-weight:bold;
}

.error{
    background:#ffe6e6;
    color:#c62828;
    padding:10px;
    margin-bottom:15px;
    border-radius:5px;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="box">
    <h2>Reset Password</h2>
    <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button name="send">Send Reset Link</button>
    </form>

    <a href="admin_login.php" class="back-link">Back to Login</a>
</div>

</body>
</html>