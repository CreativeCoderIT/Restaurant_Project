<?php
require 'config.php';

$username = "admin";
$email = "yourgmail@gmail.com";

// Hash the password
$password = password_hash("12345", PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username,email,password)
        VALUES ('$username','$email','$password')";

$conn->query($sql);

echo "Admin Created!";
?>