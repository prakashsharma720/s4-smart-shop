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
