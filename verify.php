<?php
require('razorpay/razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
include('razorpay_config.php');
include('config.php');

if (!empty($_POST['razorpay_payment_id']) && !empty($_POST['razorpay_order_id']) && !empty($_POST['razorpay_signature'])) {
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

    try {
        // ✅ Verify the payment signature
        $attributes = [
            'razorpay_order_id'   => $_POST['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature'  => $_POST['razorpay_signature']
        ];
        $api->utility->verifyPaymentSignature($attributes);

        // ✅ Optional: fetch the payment details
        $payment = $api->payment->fetch($_POST['razorpay_payment_id']);

        // ✅ Update order in database
        $stmt = $conn->prepare("UPDATE orders SET payment_status='Paid', payment_id=?, payment_method='Razorpay' WHERE id=?");
        $stmt->bind_param("si", $_POST['razorpay_payment_id'], $_POST['order_id']);
        $stmt->execute();
        $stmt->close();

        echo "✅ Payment verified successfully! Payment ID: " . $_POST['razorpay_payment_id'];
    } catch (Exception $e) {
        echo "❌ Payment verification failed: " . $e->getMessage();
    }
} else {
    echo "❌ Invalid Payment Request!";
}
?>
