<?php
include('config.php');

if ($conn->connect_error) die("<h2>DB Error: " . $conn->connect_error . "</h2>");

$order_id = $_GET['order_id'] ?? 0;

// ================== FETCH ORDER ==================
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) die("<h2>❌ Invalid Order</h2>");

// ================== FETCH PRODUCT ==================
$product_id = $order['product_id'] ?? 0;
$product = null;
$product_images = ['default.png'];
if ($product_id) {
    $ps = $conn->prepare("SELECT name, image FROM products WHERE id = ? LIMIT 1");
    $ps->bind_param("i", $product_id);
    $ps->execute();
    $prod_result = $ps->get_result();
    if ($prod_result->num_rows > 0) {
        $product = $prod_result->fetch_assoc();
        $product_images = !empty($product['image']) ? array_map('trim', explode(',', $product['image'])) : ['default.png'];
    }
    $ps->close();
}

$shipping = $order['shipping'] ?? 20;
$total_with_shipping = ($order['total'] ?? 0) + $shipping;
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Thank You - S4 Smart Shop</title>
<?php include('head.php'); ?>
<link rel="stylesheet" href="thankyou.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div id="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li>Thank You</li>
        </ul>
    </div>
</div>

<main class="thankyou-wrapper">
    <div class="thankyou-left">
        <h1>✅ Thank you for your order!</h1>
        <p>Your  order has been received. We will process it within 24 hours and send the shipping details to your email.</p>

        <hr>

        <div class="billing-info text-start">
            <h5>Billing address</h5>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
            <?php if(!empty($order['referral_code'])): ?>
                <p><strong>Referral Code:</strong> <?= htmlspecialchars($order['referral_code']) ?></p>
            <?php endif; ?>
        </div>

        <hr>

        <div class="contact-options">
            <p><strong>Have a question or need assistance?</strong></p>
            <p><strong>Contact Details:</strong> <a href="mailto:help@s4smartshop.com">help@s4smartshop.com</a>, <a href="tel:8107875712" >8107875712</a></p>
        </div>

        <button class="download-btn" id="downloadReceiptBtn">Download Receipt</button>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="order-top">
            <div><strong>Date</strong><br><?= date('d M Y', strtotime($order['created_at'])) ?></div>
            <div><strong>Order Number</strong><br><?= $order['id'] ?></div>
            <div><strong>Payment Method</strong><br><?= htmlspecialchars($order['payment_method'] ?? 'Cash on Delivery') ?></div>
        </div>

        <div class="order-products">
            <div class="order-item">
                <img src="<?php echo $base_url?>img/<?= htmlspecialchars($product_images[0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="item-details">
                    <div><strong><?= htmlspecialchars($product['name']) ?></strong></div>
                    <?php if(!empty($order['size'])): ?>
                        <div>Size: <?= htmlspecialchars($order['size']) ?></div>
                    <?php endif; ?>
                    <div>Qty: <?= htmlspecialchars($order['quantity'] ?? 1) ?></div>
                </div>
                <div class="item-price">₹<?= number_format($order['total'],2) ?></div>
            </div>
        </div>

        <div class="order-pricing">
            <div class="price-row"><span>Sub Total</span><span>₹<?= number_format($order['total'],2) ?></span></div>
            <div class="price-row"><span>Shipping</span><span>₹<?= number_format($shipping,2) ?></span></div>
            <div class="price-total"><span>Order Total</span><span>₹<?= number_format($total_with_shipping,2) ?></span></div>
        </div>
    </div>
</main>

<script>
document.getElementById('downloadReceiptBtn').addEventListener('click', () => {
    window.print();
});
</script>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
