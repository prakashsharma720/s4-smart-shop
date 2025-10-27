<?php 
// Base URL
$base_url = 'http://localhost/s4-smart-shop/';
// $base_url = 'https://s4smartshop.com/';

// Database connection
$conn = new mysqli("localhost", "root", "", "s4shopdb");
// For live server: $conn = new mysqli("localhost", "u976275941_s4shop", "#S4SmartShop@2025#", "u976275941_s4shop");

// Check connection
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
?>
