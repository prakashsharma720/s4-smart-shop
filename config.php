<?php 
// $base_url = 'https://s4smartshop.com/';
$base_url = 'http://localhost/s4-smart-shop/';

$conn = new mysqli("localhost", "root", "", "s4shopdb");

// $conn = new mysqli("localhost", "u976275941_s4shop", "#S4SmartShop@2025#", "u976275941_s4shop");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

?>