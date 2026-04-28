<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");
$result = $conn->query("SELECT * FROM food_items WHERE stock > 0");?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>

<h2 style="text-align:center;">Our Menu</h2>

<div class="menu-container">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="food-card">
        <img src="<?php echo $row['image']; ?>" width="200">
        <h3><?php echo $row['name']; ?></h3>
        <p>₹<?php echo $row['price']; ?></p>

        <?php if($row['stock'] > 0): ?>
            <button class="order-btn">Available</button>
        <?php else: ?>
            <button class="out-btn" disabled>Out of Stock</button>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>