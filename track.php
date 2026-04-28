<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

$status = "";
$order = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $orderID = $_POST['orderID'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $status = $order['status'];
    } else {
        $error = "Order not found!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Track Order</title>
<style>
body{font-family:Arial;background:#eef2f3;text-align:center;padding-top:50px;}
form{background:white;width:350px;margin:auto;padding:20px;border-radius:10px;border:2px solid #4CAF50;}
input{width:100%;padding:8px;margin:10px 0;}
button{background:#4CAF50;color:white;padding:10px;width:100%;border:none;border-radius:5px;}

.progress{
    width:80%;
    margin:20px auto;
    background:#ddd;
    border-radius:20px;
}
.progress-bar{
    height:20px;
    background:#4CAF50;
    border-radius:20px;
    transition:width 0.5s;
}
.details{
    background:white;
    width:400px;
    margin:20px auto;
    padding:20px;
    border-radius:10px;
}
</style>
</head>
<body>

<h2>Track Your Order</h2>

<form method="POST">
    <input type="number" name="orderID" placeholder="Order ID" required>
    <button type="submit">Track Order</button>
</form>

<?php if($order): 

    if($status == "Pending") $progress = 33;
    elseif($status == "Preparing") $progress = 66;
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
        <div class="progress-bar" style="width: <?php echo $progress; ?>%;"></div>
    </div>

    <p>Auto-refreshing every 5 seconds...</p>
</div>

<script>
setTimeout(function(){
    location.reload();
}, 5000);
</script>

<?php endif; ?>

<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

</body>
</html>
