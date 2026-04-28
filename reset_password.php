<?php
require 'config.php';

if(isset($_GET['token'])){

    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        if(isset($_POST['update'])){

            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE admin 
                                      SET password=?, reset_token=NULL, token_expiry=NULL 
                                      WHERE reset_token=?");
            $update->bind_param("ss", $new_password, $token);
            $update->execute();

            $success = "Password updated successfully! Redirecting to login...";

            echo "<script>
                setTimeout(function(){
                    window.location='admin_login.php';
                }, 2000);
            </script>";
        }

    } else {
        $error = "⚠ Invalid or expired token!";
    }

} else {
    $error = "⚠ Invalid reset request!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

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
    margin-bottom:10px;
    border:1px solid #ccc;
    border-radius:5px;
}

.box small{
    display:block;
    margin-bottom:15px;
    color:gray;
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
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    font-weight:bold;
    text-align:center;
    border-left:5px solid #c62828;
}
</style>
</head>

<body>

<div class="box">
    <h2>Reset Password</h2>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>

    <?php if(!isset($success)) { ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Enter New Password" required>
        <small>Minimum 6 characters recommended</small>
        <button name="update">Update Password</button>
    </form>
    <?php } ?>
</div>

</body>
</html>