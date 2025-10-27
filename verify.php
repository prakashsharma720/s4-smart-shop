<?php
require('razorpay/razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

include('razorpay_config.php');

if (!empty($_POST['razorpay_payment_id'])) {

    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

    try {
        $payment = $api->payment->fetch($_POST['razorpay_payment_id']);

        // Capture the payment
        $response = $api->payment->fetch($payment['id'])->capture(array('amount'=>$payment['amount']));

        // Store payment info in your DB here if needed
        echo "Payment Successful! Payment ID: " . $_POST['razorpay_payment_id'];
    }
    catch(Exception $e) {
        echo "Payment Failed: " . $e->getMessage();
    }
} else {
    echo "Invalid Payment Request!";
}
?>
