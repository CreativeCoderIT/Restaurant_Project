
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$conn = new mysqli("localhost", "root", "", "restaurant_db");

/* Delete Feedback */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM feedback WHERE id=$id");
}

$result = $conn->query("SELECT * FROM feedback ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Feedback Panel</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    padding:30px;
}

h2{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th,td{
    padding:12px;
    text-align:center;
    border:1px solid #ddd;
}

th{
    background:#4CAF50;
    color:white;
}

tr:nth-child(even){
    background:#f9f9f9;
}

.delete{
    background:red;
    color:white;
    padding:6px 12px;
    text-decoration:none;
    border-radius:5px;
}

.delete:hover{
    background:darkred;
}
</style>
</head>

<body>

<h2>Customer Feedback</h2>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Order ID</th>
<th>Message</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id']; ?></td>
<td><?= $row['name']; ?></td>
<td><?= $row['email']; ?></td>
<td><?= $row['order_id']; ?></td>
<td><?= $row['message']; ?></td>
<td><?= $row['created_at'] ?? '—'; ?></td>
<td>
<a class="delete" 
   href="?delete=<?= $row['id']; ?>" 
   onclick="return confirm('Delete this feedback?')">
Delete
</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>