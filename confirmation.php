<?php
include('config.php');
session_start();

// ✅ Prevent direct access
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
if ($product_id) {
    $ps = $conn->prepare("SELECT name, feature_img FROM products WHERE id = ? LIMIT 1");
    $ps->bind_param("i", $product_id);
    $ps->execute();
    $prod_result = $ps->get_result();
    $product = $prod_result->fetch_assoc();
    $ps->close();
}

$shipping = $order['shipping'] ?? 0;
$total_with_shipping = ($order['total'] ?? 0) + $shipping;

// ✅ Clear session after use
unset($_SESSION['last_order_id']);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order Confirmation - S4 Smart Shop</title>
<?php include('head.php'); ?>
</head>
<body>
<?php include('navbar.php'); ?>

<div id="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li>Order Confirmation</li>
        </ul>
    </div>
</div>

<main class="thankyou-wrapper container my-4">
    <div class="row g-4">
        <!-- LEFT SIDE -->
        <div class="col-lg-7 col-md-12">
            <div class="thankyou-left">
                <h1 class="text-success mb-3">🧾 Order Confirmation</h1>
                <p>Your order has been placed successfully. We will process it within 24 hours and send shipping details to your email.</p>

                <hr>

                <!-- ✅ BILLING BOX -->
                <div class="billing-info text-start p-3 rounded shadow-sm" style="background:#f8f9fa; border:1px solid #dee2e6;">
                    <h5 class="mb-3 text-primary"><i class="bi bi-person-lines-fill me-2"></i>Billing Details</h5>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Name:</strong><br>
                            <?= htmlspecialchars($order['name'] ?? 'Not Provided') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <?= htmlspecialchars($order['email'] ?? 'Not Provided') ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Phone:</strong><br>
                            <?= htmlspecialchars($order['phone'] ?? 'Not Provided') ?>
                        </div>
                        <div class="col-md-6">
                            <?php if(!empty($order['referral_code'])): ?>
                                <strong>Referral Code:</strong><br>
                                <?= htmlspecialchars($order['referral_code']) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(!empty($order['referal_remark'])): ?>
                    <div class="row mb-2">
                        <div class="col-12">
                            <strong>Referral Remark:</strong><br>
                            <?= htmlspecialchars($order['referal_remark']) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Address Line 1:</strong><br>
                            <?= htmlspecialchars($order['address1'] ?? 'Not Provided') ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Address Line 2:</strong><br>
                            <?= htmlspecialchars($order['address2'] ?? 'Not Provided') ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>City / Village:</strong><br>
                            <?= htmlspecialchars($order['city'] ?? 'Not Provided') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>State:</strong><br>
                            <?= htmlspecialchars($order['state'] ?? 'Not Provided') ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Pincode:</strong><br>
                            <?= htmlspecialchars($order['pincode'] ?? 'Not Provided') ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="contact-options">
                    <p><strong>Need help with your order?</strong></p>
                    <p><strong>Contact:</strong> 
                       <a href="mailto:help@s4smartshop.com">help@s4smartshop.com</a> | 
                       <a href="tel:8107875712">8107875712</a>
                    </p>
                </div>

                <button class="btn btn-primary mt-3" id="downloadReceiptBtn">
                    <i class="bi bi-printer me-2"></i>Download Receipt
                </button>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-lg-5 col-md-12">
            <div class="order-summary p-3 rounded shadow-sm" style="background:#f8f9fa; border:1px solid #dee2e6;">
                <h4 class="mb-3 text-primary"><i class="bi bi-receipt me-2"></i>Order Summary</h4>

                <div class="order-top d-flex justify-content-between flex-wrap mb-3">
                    <div><strong>Date</strong><br><?= date('d M Y', strtotime($order['created_at'])) ?></div>
                    <div><strong>Order Number</strong><br>
                        <?php
                            $orderNumber = 'S4-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
                            echo $orderNumber;
                        ?>
                    </div>
                    <div><strong>Payment Method</strong><br><?= htmlspecialchars($order['payment_method'] ?? 'Cash on Delivery') ?></div>
                </div>

                <div class="order-products mb-3">
                    <div class="order-item d-flex align-items-center gap-3 border-bottom pb-2">
                        <img src="<?= $base_url ?>back/uploads/<?= htmlspecialchars($product['feature_img'] ?? 'default.png') ?>" 
                             alt="<?= htmlspecialchars($product['name'] ?? '') ?>" 
                             class="rounded" style="width:80px; height:80px; object-fit:cover;">
                        <div class="flex-grow-1">
                            <strong><?= htmlspecialchars($product['name'] ?? 'Product') ?></strong><br>
                            <?php if(!empty($order['size'])): ?>
                                <small>Size: <?= htmlspecialchars($order['size']) ?></small><br>
                            <?php endif; ?>
                            <small>Qty: <?= htmlspecialchars($order['quantity'] ?? 1) ?></small>
                        </div>
                        <div class="item-price fw-bold text-end">₹<?= number_format($order['total'],2) ?></div>
                    </div>
                </div>

                <div class="order-pricing">
                    <div class="d-flex justify-content-between"><span>Sub Total</span><span>₹<?= $order['total']; ?></span></div>
                    <div class="d-flex justify-content-between"><span>Shipping</span><span>₹<?= number_format($shipping,2) ?></span></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold"><span>Order Total</span><span>₹<?= number_format($total_with_shipping,2) ?></span></div>
                </div>

                <hr>
                <p><strong>Make Payment Here</strong></p>
                <img src="<?= $base_url; ?>img/payment-qr.jpg" alt="Pay Here QR" class="img-fluid rounded shadow mb-2" style="width:200px; height:auto;">
                <p><strong>Merchant:</strong> S4 SMART SHOP</p>

                <a href="https://wa.me/8529257675?text=Hello!%20I%20have%20completed%20my%20payment%20for%20Order%20<?= urlencode($orderNumber) ?>.%20Please%20confirm."
                   target="_blank" 
                   class="btn btn-success mt-2 d-flex align-items-center justify-content-center gap-2">
                   <img src="<?= $base_url; ?>img/whatsapp-icon.png" alt="WhatsApp" style="width:24px;height:24px;">
                   Send Payment Screenshot via WhatsApp
                </a>
            </div>
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
