<?php
$conn = new mysqli("localhost","root","","restaurant_db");

$message_status = "";

if(isset($_POST['submit'])){

    $order_id = $_POST['order_id'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    // Check if order exists with matching email
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id=? AND email=?");
    $stmt->bind_param("is", $order_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {

        // Insert feedback
        $stmt2 = $conn->prepare("INSERT INTO feedback (order_id, name, email, rating, message) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issss", $order_id, $name, $email, $rating, $message);

        if($stmt2->execute()){
            $message_status = "<p style='color:green; font-weight:bold;'>Feedback submitted successfully!</p>";
        } else {
            $message_status = "<p style='color:red; font-weight:bold;'>Something went wrong. Please try again.</p>";
        }

    } else {
        $message_status = "<p style='color:red; font-weight:bold;'>Invalid Order ID or Email does not match our records.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Feedback</title>

<style>
body{
    font-family:Arial;
    background:#eef2f3;
    text-align:center;
    padding-top:50px;
}

form{
    border:2px solid #4CAF50;
    padding:20px;
    width:350px;
    margin:auto;
    border-radius:10px;
    background:white;
}

input, textarea, select{
    width:90%;
    padding:8px;
    margin:10px 0;
}

textarea{
    height:120px;
    resize:none;
}

button{
    background:#4CAF50;
    color:white;
    padding:10px;
    width:100%;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#45a049;
}
</style>

</head>
<body>

<h2>Customer Feedback</h2>

<?php if(!empty($message_status)) echo $message_status; ?>

<form method="POST" action="">

    <input type="text" name="name" placeholder="Your Name" required>

    <input type="email" name="email" placeholder="Email Address" required>

    <input type="number" name="order_id" placeholder="Enter Order ID" required>

    <select name="rating" required>
        <option value="">--Select Rating--</option>
        <option value="Excellent">⭐⭐⭐⭐⭐ Excellent</option>
        <option value="Good">⭐⭐⭐⭐ Good</option>
        <option value="Average">⭐⭐⭐ Average</option>
        <option value="Poor">⭐⭐ Poor</option>
    </select>

    <textarea name="message" placeholder="Your Feedback" required></textarea>

    <button type="submit" name="submit">Submit Feedback</button>

</form>

</body>
</html>