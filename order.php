<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ===== GET FORM DATA =====
    $name       = htmlspecialchars($_POST['customerName']);
    $email      = htmlspecialchars($_POST['email']);
    $mobile     = htmlspecialchars($_POST['phoneNumber']);
    $address    = htmlspecialchars($_POST['address']);
    $foodItems  = $_POST['foodItem'];
    $quantities = $_POST['quantity'];
    $payment    = htmlspecialchars($_POST['paymentMethod']);
    $totalAmount = $_POST['totalAmount'];

    // ===== DATABASE CONNECTION =====
    $conn = new mysqli("localhost", "root", "", "restaurant_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ===== CONVERT ITEMS TO STRING =====
    $itemList = "";
    for ($i = 0; $i < count($foodItems); $i++) {
        $itemList .= $foodItems[$i] . " (Qty: " . $quantities[$i] . "), ";
    }

    // ===== INSERT INTO DATABASE =====
    $stmt = $conn->prepare("INSERT INTO orders 
        (customer_name, email, phone, address, items, total_amount, payment_method) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssis", $name, $email, $mobile, $address, $itemList, $totalAmount, $payment);
    $stmt->execute();
    $orderID = $stmt->insert_id;
    $stmt->close();

    // ===== SEND EMAILS =====
    sendOrderMail($orderID, $name, $email, $mobile, $address, $foodItems, $quantities, $payment, $totalAmount);

} else {
    echo "Invalid request method.";
}



function sendOrderMail($orderID,$name, $email, $mobile, $address, $foodItems, $quantities, $payment, $totalAmount)
{
    try {
        $date = date("d-m-Y H:i");

        // ===== BUILD ORDER DETAILS =====
        $orderDetails = "";
        for ($i = 0; $i < count($foodItems); $i++) {
            $item = htmlspecialchars($foodItems[$i]);
            $qty  = htmlspecialchars($quantities[$i]);
            $orderDetails .= "<p><b>Item:</b> $item | <b>Qty:</b> $qty</p>";
        }

        // ================= OWNER EMAIL =================
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->CharSet    = 'UTF-8';
        $mail->Username   = 'shrivastavauttam699@gmail.com';
        $mail->Password   = '**** **** **** ****'; // 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('shrivastavauttam699@gmail.com', 'Restaurant Orders');
        $mail->addAddress('shrivastavauttam699@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = '🍴 New Order Received';

        $mail->Body = "
        <div style='font-family:Arial; max-width:600px; margin:auto; border:1px solid #ddd; padding:20px; background:#f9f9f9;'>
            <h2 style='color:#e67e22;'>🍴 New Order Received</h2>
            <hr>
            <p><b>Order ID:</b> #$orderID</p>
            <p><b>Date:</b> $date</p>

            <hr>
            <h3>Customer Details</h3>
            <p><b>Name:</b> $name</p>
            <p><b>Email:</b> $email</p>
            <p><b>Phone:</b> $mobile</p>

            <hr>
            <h3>Order Details</h3>
            $orderDetails

            <hr>
            <p style='font-size:18px;'><b>Total Amount:</b> 
            <span style='color:green;'>₹$totalAmount</span></p>
            <p><b>Payment Method:</b> $payment</p>

            <hr>
            <h3>Delivery Address</h3>
            <p>$address</p>

            <hr>
            <p style='color:red; font-weight:bold;'>⚠ Please prepare this order immediately.</p>
        </div>
        ";

        $mail->send();


        // ================= CUSTOMER EMAIL =================
        $mail2 = new PHPMailer(true);
        $mail2->isSMTP();
        $mail2->Host       = 'smtp.gmail.com';
        $mail2->SMTPAuth   = true;
        $mail2->CharSet    = 'UTF-8';
        $mail2->Username   = 'shrivastavauttam699@gmail.com';
        $mail2->Password   = '**** **** ****'; // 
        $mail2->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail2->Port       = 587;

        $mail2->setFrom('shrivastavauttam699@gmail.com', 'Restaurant');
        $mail2->addAddress($email);

        $mail2->isHTML(true);
        $mail2->Subject = '✅ Order Confirmation';

        $mail2->Body = "
        <div style='font-family:Arial; max-width:600px; margin:auto; border:1px solid #ddd; padding:20px;'>
            <h2 style='color:#2ecc71;'>✅ Order Confirmation</h2>
            <hr>
            <p>Hi <b>$name</b>,</p>
            <p>Thank you for ordering with us! 🍽️</p>

            <hr>
            <p><b>Order ID:</b> #$orderID</p>
            <p><b>Date:</b> $date</p>

            <hr>
            $orderDetails

            <hr>
            <p><b>Total Amount:</b> ₹$totalAmount</p>
            <p><b>Payment Method:</b> $payment</p>

            <hr>
            <p><b>Delivery Address:</b><br>$address</p>

            <hr>
            <p style='color:gray;'>Your order is being prepared.</p>
            <h3 style='color:#e67e22;'>Thank You ❤️</h3>
        </div>
        ";

        $mail2->send();


        // ================= WHATSAPP REDIRECT =================
        $ownerNumber = "917525962700"; // 🔴 PUT FULL NUMBER (NO +, NO SPACE)

        $message = "New Order\n\n";
        $message .= "Order ID: $orderID\n";
        $message .= "Customer: $name\n";
        $message .= "Phone: $mobile\n\n";

        for ($i = 0; $i < count($foodItems); $i++) {
            $message .= $foodItems[$i] . " x " . $quantities[$i] . "\n";
        }

        $message .= "\nTotal: Rs $totalAmount\n";
        $message .= "Payment: $payment\n";
        $message .= "Address: $address";

        $encodedMessage = urlencode($message);

       header("Location: order_success.php?order_id=" . $orderID);
       exit();


    } catch (Exception $e) {
        echo "Mailer Error: {$e->getMessage()}";
    }
}
?>
