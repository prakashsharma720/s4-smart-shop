<?php 
$base_url = 'http://localhost/ci/s4-smart-shop/';

$conn = new mysqli("localhost", "root", "", "s4shopdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

?>