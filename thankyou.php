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

         <!-- ✅ BILLING BOX -->
        <div class="billing-info text-start rounded shadow-sm" >
            <h5 class="mb-3 p-2" style="background:#f8f9fa; border:1px solid #dee2e6;"><i class="bi bi-truck me-2"></i>Shipping/Billing Details</h5>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Name:</strong>
                    <?= htmlspecialchars($order['name']) ?>
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong>
                    <?= htmlspecialchars($order['email']) ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Address Line 1:</strong>
                    <?= htmlspecialchars($order['address1'] ?? 'Not Provided') ?>
                </div>
                <div class="col-md-6">
                    <strong>Address Line 2:</strong>
                    <?= htmlspecialchars($order['address2'] ?? 'Not Provided') ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>City / Village:</strong>
                    <?= htmlspecialchars($order['city'] ?? 'Not Provided') ?>
                </div>
                <div class="col-md-6">
                    <strong>State:</strong>
                    <?= htmlspecialchars($order['state'] ?? 'Not Provided') ?>
                </div>
                <div class="col-md-6">
                    <strong>Pin Code:</strong>
                    <?= htmlspecialchars($order['pincode'] ?? 'Not Provided') ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Phone:</strong><br>
                    <?= htmlspecialchars($order['phone']) ?>
                </div>
            </div>
        </div>
        <!-- ✅ END BILLING BOX -->
        <div class="row mb-2 mt-3">
            <div class="col-md-6">
                <label> Download/ Print Order Receipt </label>
                    <button class="btn btn-primary mb-2" id="downloadReceiptBtn">
                    <i class="bi bi-printer me-2"></i>Download Receipt
                </button>  
            </div>
            <div class="col-md-6">
                <label> Share Your Order Receipt on Whatsapp.</label>
                <a href="https://wa.me/8529257675?text=Hello!%20I%20have%20completed%20my%20payment%20for%20Order%20<?= urlencode($orderNumber) ?>.%20Please%20confirm." target="_blank"  class="btn btn-success d-flex align-items-center justify-content-center gap-2">
                    <img src="<?= $base_url; ?>img/WhatsApp.webp" alt="WhatsApp" style="width:24px;height:24px;"> WhatsApp </a>
            </div>
        </div><br>
        <div class="contact-options">
                <p><strong>Need help with your order?</strong><br>
                <strong>Contact:</strong> 
                <a href="mailto:help@s4smartshop.com">help@s4smartshop.com</a> | 
                <a href="tel:8107875712">8107875712</a>
                </p>
        </div>
        <hr>
    </div>
    <div class="order-summary">
       <div class="justify-content-end mb-3">
            <h5 class="p-2" style="background:#f8f9fa; border:1px solid #dee2e6;"><i class="bi bi-book me-2"></i> Order Summary</h5>
        </div>  
        <div class="order-top d-flex justify-content-between flex-wrap mb-1">
            <div><strong>Date</strong>
            <?= date('d M Y', strtotime($order['created_at'])) ?></div>
            <div><strong>Order Number</strong>
                <?php
                    $orderNumber = 'S4-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
                    echo $orderNumber;
                ?>
            </div>
<?php
$payment_status = strtolower($order['payment_status'] ?? 'pending');

$class = 'status-pending';
if ($payment_status === 'paid' || $payment_status === 'success') {
    $class = 'status-paid';
} elseif ($payment_status === 'failed' || $payment_status === 'failure') {
    $class = 'status-failed';
}
?>

<div>
    <strong>Payment Status:</strong>
    <span class="status-badge <?= $class ?>">
        <?= htmlspecialchars($payment_status) ?>
    </span>
</div>
        </div>
         <div class="order-top d-flex justify-content-between flex-wrap mb- ">
            <div><strong>Payment ID</strong>
            <?= htmlspecialchars($order['payment_id'] ?? 'NA') ?></div>
            <div>
                <?php if(!empty($order['referral_code'])): ?>
                <strong>Referral Code</strong>
                <?= htmlspecialchars($order['referral_code']) ?>
            <?php endif; ?>
            </div>
            <div><strong>Remark</strong><?= htmlspecialchars($order['referal_remark'] ?? 'N/A') ?></div>
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
    </div>

</main>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>

<script>
document.getElementById('downloadReceiptBtn').addEventListener('click', () => {
    window.print();
});

// 🎉 Confetti Celebration (4 seconds)
const script = document.createElement("script");
script.src = "https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js";
document.body.appendChild(script);
script.onload = function() {
    const duration = 4 * 1000;
    const end = Date.now() + duration;

    (function frame() {
        confetti({
            particleCount: 5,
            angle: 60,
            spread: 55,
            origin: { x: 0 }
        });
        confetti({
            particleCount: 5,
            angle: 120,
            spread: 55,
            origin: { x: 1 }
        });
        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
    })();
};
</script>
</body>
</html>
