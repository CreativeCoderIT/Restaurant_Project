<?php
if (!isset($_GET['order_id'])) {
    header("Location: index.html");
    exit();
}

$orderID = $_GET['order_id'];

$conn = new mysqli("localhost", "root", "", "restaurant_db");

$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// ===== WHATSAPP SETUP =====
$ownerNumber = "123456789"; // 🔥 REPLACE WITH YOUR REAL NUMBER (no +)

$message = "New Order #$orderID\n";
$message .= "Name: " . $order['customer_name'] . "\n";
$message .= "Items: " . $order['items'] . "\n";
$message .= "Total: ₹" . $order['total_amount'] . "\n";
$message .= "Status: " . $order['status'];

$encodedMessage = urlencode($message);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Success</title>
<style>
body{
    font-family:'Segoe UI', sans-serif;
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.card{
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    width:450px;
    text-align:center;
}

.success-icon{
    font-size:60px;
    color:#4CAF50;
}

.order-id{
    font-size:22px;
    margin:15px 0;
    font-weight:600;
}

.details{
    text-align:left;
    margin-top:20px;
    font-size:14px;
}

.btn{
    display:inline-block;
    margin-top:15px;
    padding:10px 20px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    background:#4CAF50;
}

.btn.secondary{
    background:#555;
}

.btn.whatsapp{
    background:#25D366;
}
</style>
</head>

<body>

<div class="card">

<div class="success-icon">✅</div>

<h2>Order Placed Successfully!</h2>

<div class="order-id">
Order ID: #<?php echo $orderID; ?>
</div>

<div class="details">
<p><b>Name:</b> <?php echo $order['customer_name']; ?></p>
<p><b>Items:</b> <?php echo $order['items']; ?></p>
<p><b>Total:</b> ₹<?php echo $order['total_amount']; ?></p>
<p><b>Status:</b> <?php echo $order['status']; ?></p>
</div>

<!-- WhatsApp Button -->
<a href="https://wa.me/<?php echo $ownerNumber; ?>?text=<?php echo $encodedMessage; ?>" 
   target="_blank" 
   class="btn whatsapp">
   Send WhatsApp Confirmation
</a>

<br>

<a href="tracking.php" class="btn">Track Order</a>
<a href="index.php" class="btn secondary">Go Home</a>

</div>

</body>
</html>
