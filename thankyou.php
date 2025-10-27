<?php
include('config.php');
session_start();

// ✅ Check: prevent direct access
if (!isset($_SESSION['last_order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = $_SESSION['last_order_id'];

// ================== FETCH ORDER ==================
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: index.php");
    exit;
}

// ================== FETCH PRODUCT ==================
$product_id = $order['product_id'] ?? 0;
$product = null;
$product_images = ['default.png'];
if ($product_id) {
    $ps = $conn->prepare("SELECT name, feature_img FROM products WHERE id = ? LIMIT 1");
    $ps->bind_param("i", $product_id);
    $ps->execute();
    $prod_result = $ps->get_result();
    if ($prod_result->num_rows > 0) {
        $product = $prod_result->fetch_assoc();
        $product_images = !empty($product['feature_img'])
            ? array_map('trim', explode(',', $product['feature_img']))
            : ['default.png'];
    }
    $ps->close();
}

$shipping = $order['shipping'] ?? 0;
$total_with_shipping = ($order['total'] ?? 0) + $shipping;

// ✅ Once displayed, clear session so page won’t reopen on refresh
unset($_SESSION['last_order_id']);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Thank You - S4 Smart Shop</title>
<?php include('head.php'); ?>
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
        <p>Your order has been received. We will process it within 24 hours and send the shipping details to your email.</p>

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
            <p><strong>Contact Details:</strong> 
               <a href="mailto:help@s4smartshop.com">help@s4smartshop.com</a>, 
               <a href="tel:8107875712">8107875712</a>
            </p>
        </div>

        <button class="download-btn" id="downloadReceiptBtn">Download Receipt</button>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="order-top">
            <div><strong>Date</strong><br><?= date('d M Y', strtotime($order['created_at'])) ?></div>
            <div><strong>Order Number</strong><br>
                <?php
                    $orderNumber = 'S4-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
                    echo $orderNumber;
                ?>
            </div>
            <div><strong>Payment Method</strong><br><?= htmlspecialchars($order['payment_method'] ?? 'Cash on Delivery') ?></div>
        </div>

        <div class="order-products">
            <div class="order-item">
                <img src="<?= $base_url ?>back/uploads/<?= htmlspecialchars($product['feature_img']) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>">
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
            <div class="price-row"><span>Sub Total</span><span>₹<?= $order['total']; ?></span></div>
            <div class="price-row"><span>Shipping</span><span>₹<?= number_format($shipping,2) ?></span></div>
            <div class="price-total"><span>Order Total</span><span>₹<?= number_format($total_with_shipping,2) ?></span></div>
        </div>
        <br>

        <p><strong>Make Payment Here</strong></p>
        <img src="<?= $base_url; ?>img/payment-qr.jpg" alt="Pay Here QR" class="img-fluid rounded shadow mb-2" style="width:200px; height:auto;">
        <p><strong>Merchant:</strong> S4 SMART SHOP</p>

        <!-- WhatsApp Share Button right below QR/payment -->
        <a href="https://wa.me/8529257675?text=Hello!%20I%20have%20completed%20my%20payment%20for%20Order%20<?= urlencode($orderNumber) ?>.%20Please%20confirm."
           target="_blank" 
           class="btn btn-success mt-2 d-flex align-items-center gap-2">
           <img src="<?= $base_url; ?>img/whatsapp-icon.png" alt="WhatsApp" style="width:24px;height:24px;">
           Send Payment Screenshot via WhatsApp
        </a>

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
