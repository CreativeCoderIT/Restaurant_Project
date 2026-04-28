<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

/* UPDATE */
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE food_items SET name=?, price=?, stock=? WHERE id=?");
    $stmt->bind_param("sdii", $name, $price, $stock, $id);
    $stmt->execute();

    header("Location: admin_stock.php");
    exit();
}

/* DELETE */
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM food_items WHERE id=$id");

    header("Location: admin_stock.php");
    exit();
}

/* ADD NEW FOOD */
if(isset($_POST['add_food'])) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO food_items (name, price, stock, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $stock, $category);
    $stmt->execute();

    header("Location: admin_stock.php");
    exit();
}
$result = $conn->query("SELECT * FROM food_items");
?>

<h2>Manage Food Items</h2>
<h3>Add New Food Item</h3>

<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="name" placeholder="Food Name" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" name="stock" placeholder="Stock" required>

    <select name="category" required>
        <option value="">Select Category</option>
        <option value="Starters">Starters</option>
        <option value="Main Course">Main Course</option>
        <option value="Breads">Breads</option>
        <option value="Beverages">Beverages</option>
        <option value="Desserts">Desserts</option>
    </select>

    <button type="submit" name="add_food">Add Food</button>
</form>
<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST" action="admin_stock.php">
    <td>
        <input type="text" name="name" value="<?= $row['name']; ?>">
    </td>
    <td>
        <input type="number" step="0.01" name="price" value="<?= $row['price']; ?>">
    </td>
    <td>
        <input type="number" name="stock" value="<?= $row['stock']; ?>">
    </td>
    <td>
        <input type="hidden" name="id" value="<?= $row['id']; ?>">
        <button type="submit" name="update">Update</button>
        <a href="admin_stock.php?delete=<?= $row['id']; ?>" 
           onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</form>
</tr>
<?php endwhile; ?>
</table>