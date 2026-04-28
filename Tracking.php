<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

$order = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orderId = $_POST['orderId'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        $error = "Order not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Tracking</title>

<style>
body{
    font-family:Arial;
    background:#eef2f3;
    padding-top:50px;
    text-align:center;
}

form{
    background:white;
    width:350px;
    margin:auto;
    padding:20px;
    border-radius:10px;
    border:2px solid #4CAF50;
}

input{
    width:100%;
    padding:8px;
    margin:10px 0;
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

.details{
    background:white;
    width:520px;
    margin:20px auto;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

/* Progress */
.progress{
    width:100%;
    background:#ddd;
    border-radius:20px;
    margin-top:15px;
}

.progress-bar{
    height:20px;
    background:#4CAF50;
    border-radius:20px;
    transition: width 0.5s ease;
}

/* ===== DELIVERY ANIMATION (UNCHANGED) ===== */

.delivery-container {
    width: 100%;
    height: 220px;
    margin-top: 30px;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(to top, #2c2c2c 45%, #6ec1e4 45%);
}

/* Road stripes */
.delivery-container::after {
    content:"";
    position:absolute;
    bottom:40px;
    width:200%;
    height:6px;
    background:repeating-linear-gradient(
        to right,
        white 0px,
        white 20px,
        transparent 20px,
        transparent 40px
    );
    animation:moveRoad 1s linear infinite;
}

@keyframes moveRoad{
    0%{left:0;}
    100%{left:-40px;}
}

/* Clouds */
.cloud{
    position:absolute;
    top:30px;
    font-size:26px;
    animation:moveCloud 40s linear infinite;
}

.cloud2{
    top:70px;
    animation-duration:55s;
}

@keyframes moveCloud{
    0%{left:-50px;}
    100%{left:100%;}
}

/* Bike */
.delivery-bike{
    position:absolute;
    bottom:55px;
    left:-150px;
    animation:moveBike 8s linear infinite, bounce 0.4s ease-in-out infinite alternate;
}

.delivery-bike img{
    width:110px;
    filter:drop-shadow(0px 8px 5px rgba(0,0,0,0.4));
}

@keyframes moveBike{
    0%{left:-150px;}
    100%{left:75%;}
}

@keyframes bounce{
    from{transform:translateY(0);}
    to{transform:translateY(-4px);}
}

/* House */
.house{
    position:absolute;
    bottom:55px;
    right:25px;
    font-size:42px;
}

/* ===== GIF STATUS ===== */

.status-gif{
    margin-top:25px;
    text-align:center;
}

.status-gif img{
    border-radius:15px;
}

.status-text{
    font-weight:bold;
    margin-top:10px;
    font-size:16px;
}

/* Delivered box fallback */
.delivered-box{
    margin-top:20px;
    background:#d4edda;
    color:#155724;
    padding:20px;
    border-radius:15px;
}
</style>
</head>
<body>

<h2>Track Your Order</h2>

<form method="POST">
  <input type="number" name="orderId" placeholder="Order ID" required>
  <button type="submit">Track Order</button>
</form>

<?php if($order):

$status = $order['status'];

if($status == "Pending") $progress = 25;
elseif($status == "Preparing") $progress = 50;
elseif($status == "Out for Delivery") $progress = 75;
elseif($status == "Delivered") $progress = 100;
else $progress = 0;
?>

<div class="details">
<h3>Order Details</h3>
<p><b>Name:</b> <?php echo $order['customer_name']; ?></p>
<p><b>Items:</b> <?php echo $order['items']; ?></p>
<p><b>Total:</b> ₹<?php echo $order['total_amount']; ?></p>
<p><b>Status:</b> <?php echo $status; ?></p>

<div class="progress">
<div class="progress-bar" style="width:<?php echo $progress; ?>%;"></div>
</div>

<!-- 🍳 Preparing -->
<?php if($status == "Preparing"): ?>
<div class="status-gif">
    <img src="cooking.gif" width="220">
    <p class="status-text">
        🍳 Preparing Your Food<br>
        Our chef is cooking with love ❤️
    </p>
</div>
<?php endif; ?>

<!-- 🚚 Out for Delivery (UNCHANGED) -->
<?php if($status == "Out for Delivery"): ?>
<div class="delivery-container">
<div class="cloud">☁️</div>
<div class="cloud cloud2">☁️</div>

<div class="delivery-bike">
<img src="delivery1.png" alt="Delivery Bike">
</div>

<div class="house">🏠</div>
</div>
<?php endif; ?>

<!-- 🎉 Delivered -->
<?php if($status == "Delivered"): ?>
<div class="status-gif">
    <p class="status-text">
        🎉 Delivered Successfully!<br>
        Enjoy your meal 😋
    </p>
</div>
<?php endif; ?>

</div>

<?php endif; ?>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
