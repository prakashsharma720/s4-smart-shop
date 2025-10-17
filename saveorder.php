<?php
$conn = new mysqli("localhost","root","","s4shopdb");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Get POST data
$name     = $_POST['customerName']   ?? '';
$address  = $_POST['customerAddress'] ?? '';
$email    = $_POST['customerEmail']  ?? '';
$phone    = $_POST['customerPhone']  ?? '';
$product_id  = (int)($_POST['product_id'] ?? 0);
$weight   = $_POST['weight'] ?? '';
$quantity = (int)($_POST['quantity'] ?? 1);
$total    = (float)($_POST['total'] ?? 0);

// Insert into orders
$stmt = $conn->prepare("INSERT INTO orders (name,address,email,phone,product_id,weight,quantity,total) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("sssssiid", $name, $address, $email, $phone, $product_id, $weight, $quantity, $total);

if($stmt->execute()){
    echo "Order saved successfully. Order ID: ".$stmt->insert_id;
}else{
    echo "Error: ".$stmt->error;
}

$stmt->close();
$conn->close();
?>
