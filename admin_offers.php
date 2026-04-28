<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

/* Add Offer */
if(isset($_POST['add'])){
    $text = $_POST['offer_text'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $stmt = $conn->prepare("INSERT INTO offers (offer_text,start_date,end_date,status) VALUES (?,?,?,'Active')");
    $stmt->bind_param("sss",$text,$start,$end);
    $stmt->execute();

    // Redirect to prevent duplicate insert on refresh
    header("Location: admin_offers.php");
    exit();
}

/* Toggle Status */
if(isset($_GET['toggle'])){
    $id = $_GET['toggle'];
    $conn->query("UPDATE offers 
                  SET status = IF(status='Active','Inactive','Active') 
                  WHERE id=$id");
}

/* Delete */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM offers WHERE id=$id");
}

$result = $conn->query("SELECT * FROM offers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Offer Management</title>
<style>
body{font-family:Arial;background:#f4f6f9;padding:30px;}
form input{padding:8px;margin:5px;}
button{padding:6px 12px;background:#4CAF50;color:white;border:none;}
table{width:100%;margin-top:20px;border-collapse:collapse;}
th,td{padding:10px;text-align:center;border:1px solid #ddd;}
th{background:#4CAF50;color:white;}
a{padding:5px 10px;border-radius:5px;text-decoration:none;}
.toggle{background:orange;color:white;}
.delete{background:red;color:white;}
</style>
</head>
<body>

<h2>Offer Management</h2>

<form method="POST">
    Offer Text: <input type="text" name="offer_text" required>
    Start Date: <input type="date" name="start" required>
    End Date: <input type="date" name="end" required>
    <button name="add">Add Offer</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Offer</th>
<th>Start</th>
<th>End</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['offer_text']; ?></td>
<td><?php echo $row['start_date']; ?></td>
<td><?php echo $row['end_date']; ?></td>
<td><?php echo $row['status']; ?></td>
<td>
<a class="toggle" href="?toggle=<?php echo $row['id']; ?>">Toggle</a>
<a class="delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete offer?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>