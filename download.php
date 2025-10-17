<?php
require('fpdf/fpdf.php'); // path: C:/xampp/htdocs/Taste-of-namkeen/fpdf/fpdf.php

$conn = new mysqli("localhost", "root", "", "s4shopdb");
if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

$order_id = $_GET['order_id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Invalid Order ID");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, "Taste of Namkeen - Receipt", 0, 1, 'C');
$pdf->Ln(10);

// Order info
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, "Order ID: " . $order['id'], 0, 1);
$pdf->Cell(0, 8, "Date: " . date('d M Y', strtotime($order['created_at'] ?? date('Y-m-d'))), 0, 1);
$pdf->Cell(0, 8, "Payment Method: " . ($order['payment_method'] ?? 'Cash on Delivery'), 0, 1);
$pdf->Ln(5);

// Customer info
$pdf->Cell(0, 8, "Customer: " . $order['name'], 0, 1);
$pdf->Cell(0, 8, "Email: " . $order['email'], 0, 1);
$pdf->Cell(0, 8, "Phone: " . $order['phone'], 0, 1);
$pdf->MultiCell(0, 8, "Address: " . $order['address']);
$pdf->Ln(5);

// Order details table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 8, "Product", 1);
$pdf->Cell(30, 8, "Weight", 1);
$pdf->Cell(30, 8, "Qty", 1);
$pdf->Cell(50, 8, "Price", 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(70, 8, $order['product'], 1);
$pdf->Cell(30, 8, $order['weight'], 1);
$pdf->Cell(30, 8, $order['quantity'], 1);
$pdf->Cell(50, 8, "Rs. " . number_format($order['total'], 2), 1);
$pdf->Ln(10);

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, "Total Amount: Rs. " . number_format($order['total'], 2), 0, 1);

$pdf->Output("D", "Receipt_Order_" . $order['id'] . ".pdf");
?>
