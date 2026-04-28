<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

$foods = $conn->query("SELECT * FROM food_items WHERE stock > 0 ORDER BY category, name");

$foodOptionsHTML = '<option value="">--Select Food--</option>';
$currentCategory = "";

while($row = $foods->fetch_assoc()) {

    if($currentCategory != $row['category']) {
        if($currentCategory != "") $foodOptionsHTML .= '</optgroup>';
        $foodOptionsHTML .= '<optgroup label="🍽 '.$row['category'].'">';
        $currentCategory = $row['category'];
    }

    $foodOptionsHTML .= '<option value="'.$row['name'].'" data-price="'.$row['price'].'">';
    $foodOptionsHTML .= $row['name'].' - ₹'.$row['price'];
    $foodOptionsHTML .= '</option>';
}

if($currentCategory != "") $foodOptionsHTML .= '</optgroup>';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Place Order</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #eef2f3;
    padding-top: 40px;
    text-align: center;
}

form {
    border: 2px solid #4CAF50;
    padding: 25px;
    width: 500px;
    margin: auto;
    border-radius: 12px;
    background: #ffffff;
}

label {
    display: block;
    text-align: left;
    font-weight: 600;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="tel"],
textarea,
select {
    width: 100%;
    height: 42px;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

textarea {
    height: 90px;
    resize: none;
}


button {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #45a049;
}

#billPreview {
    text-align: left;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.payment-section {
    text-align: left;
    margin-top: 10px;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.hidden {
    display: none;
}
/* ===== PERFECT SELECT ITEM ALIGNMENT FIX ===== */

.item {
    display: flex;
    align-items: stretch;      /* forces same height */
    gap: 10px;
}

.item select,
.item input[type="number"] {
    flex: 1;
    height: 48px;
    padding: 0 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    font-size: 15px;
}

.item select {
    flex: 2;   /* dropdown wider */
}

.item button {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    border: none;
    background: #ff3b3b;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;
}</style>

</head>
<body>

<h2>Place Your Order</h2>

<form action="order.php" method="POST" enctype="multipart/form-data">

<label>Name</label>
<input type="text" name="customerName" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Phone</label>
<input type="tel" name="phoneNumber" required>

<label>Address</label>
<textarea name="address" required></textarea>

<h3>🛒 Select Items</h3>

<div id="orderItems">
<div class="item">
<select name="foodItem[]" onchange="updateTotal()" required>
<?php echo $foodOptionsHTML; ?>
</select>

<input type="number" name="quantity[]" min="1" value="1" onchange="updateTotal()" required>
<button type="button" onclick="removeItem(this)">X</button>
</div>
</div>

<button type="button" onclick="addItem()">+ Add More Item</button>

<h3>🧾 Bill Preview</h3>
<div id="billPreview"></div>

<h3>Total: ₹<span id="totalAmount">0</span></h3>
<input type="hidden" name="totalAmount" id="totalInput">

<div class="payment-section">
    <label>Payment Method</label>

    <div class="payment-option">
        <input type="radio" name="paymentMethod" value="Cash" required>
        <span>Cash</span>
    </div>

    <div class="payment-option">
        <input type="radio" name="paymentMethod" value="Online" required>
        <span>Online</span>
    </div>
</div>

<div id="onlineSection" class="hidden">
    <p><b>Scan & Pay:</b></p>
    <img src="qr.jpg" style="width:200px;">
    <br><br>
    <label>Upload Screenshot</label>
    <input type="file" name="transactionScreenshot" accept="image/*">
</div>

<br>
<button type="submit">Submit Order</button>

</form>

<script>

const foodOptions = `<?php echo $foodOptionsHTML; ?>`;

function updateTotal(){
    let items=document.querySelectorAll(".item");
    let total=0;
    let preview="";

    items.forEach(item=>{
        let select=item.querySelector("select");
        let qty=item.querySelector("input").value;
        let price=select.options[select.selectedIndex]?.getAttribute("data-price");

        if(price && qty){
            let itemTotal=price*qty;
            total+=itemTotal;
            preview+=`<p>${select.value} × ${qty} = ₹${itemTotal}</p>`;
        }
    });

    document.getElementById("billPreview").innerHTML=preview;
    document.getElementById("totalAmount").innerText=total;
    document.getElementById("totalInput").value=total;
}

function addItem(){
    let div=document.createElement("div");
    div.classList.add("item");

    div.innerHTML=`
        <select name="foodItem[]" onchange="updateTotal()" required>
            ${foodOptions}
        </select>
        <input type="number" name="quantity[]" min="1" value="1" onchange="updateTotal()" required>
        <button type="button" onclick="removeItem(this)">X</button>
    `;

    document.getElementById("orderItems").appendChild(div);
}

function removeItem(btn){
    btn.parentElement.remove();
    updateTotal();
}

document.querySelectorAll('input[name="paymentMethod"]').forEach(radio=>{
    radio.addEventListener('change',function(){
        if(this.value==="Online"){
            document.getElementById("onlineSection").classList.remove("hidden");
        } else {
            document.getElementById("onlineSection").classList.add("hidden");
        }
    });
});

</script>

</body>
</html>