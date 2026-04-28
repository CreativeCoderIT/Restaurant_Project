<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=orders.csv");

$output = fopen("php://output", "w");

// Column headings
fputcsv($output, array(
    "ID",
    "Customer Name",
    "Email",
    "Phone",
    "Address",
    "Items",
    "Total Amount",
    "Payment Method",
    "Order Date"
));

$result = $conn->query("SELECT * FROM orders");

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
exit;
?>
