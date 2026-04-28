<?php
// ===== SESSION CHECK =====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// ===== DATABASE CONNECTION =====
$conn = new mysqli("localhost", "root", "", "restaurant_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ===== UPDATE ORDER STATUS =====
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

// ===== FETCH ORDERS =====
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body{
    font-family: 'Segoe UI', sans-serif;
    background:#f4f6f9;
    margin:0;
    padding:30px;
}

.dashboard-title{
    font-size:28px;
    font-weight:600;
    margin-bottom:20px;
}

.topnav{
    margin-bottom:20px;
}

.topnav a{
    text-decoration:none;
    background:#4CAF50;
    color:white;
    padding:8px 15px;
    border-radius:5px;
    margin-right:10px;
    font-size:14px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#4CAF50;
    color:white;
    padding:12px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9f9f9;
}

select{
    padding:5px;
    border-radius:5px;
}

.update-btn{
    background:#4CAF50;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:5px;
    cursor:pointer;
}

.update-btn:hover{
    background:#45a049;
}
</style>
</head>

<body>

<div class="dashboard-title">Admin Dashboard</div>

<div class="topnav">
    <a href="admin_orders.php">Orders</a>
    <a href="admin_feedback.php">Feedback</a>
    <a href="admin_logout.php">Logout</a>
    <a href="admin_offers.php">Manage Offers</a>
    <a href="admin_stock.php">Manage Stock</a>
</div>

<div class="card">

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Items</th>
    <th>Total</th>
    <th>Status</th>
    <th>Update</th>
</tr>

<?php if ($result && $result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['customer_name']; ?></td>
    <td><?php echo $row['items']; ?></td>
    <td>₹<?php echo $row['total_amount']; ?></td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <select name="status">
                <option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
                <option value="Preparing" <?php if($row['status']=="Preparing") echo "selected"; ?>>Preparing</option>
                <option value="Out for Delivery" <?php if($row['status']=="Out for Delivery") echo "selected"; ?>>Out for Delivery</option>
                <option value="Delivered" <?php if($row['status']=="Delivered") echo "selected"; ?>>Delivered</option>
            </select>
    </td>
    <td>
            <button class="update-btn" name="update">Update</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6">No orders found</td>
</tr>
<?php endif; ?>

</table>

</div>

</body>
</html>
