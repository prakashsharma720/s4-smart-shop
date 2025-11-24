<?php

$apiKey = "626a8b3cdf06d42c98ef6a280b8efa21fa71010d";
$baseUrl = "https://staging-express.delhivery.com/api/cmu/invoice/charge/";

// Build Query Parameters
$params = [
    "md"     => "E",            // Express
    "ss"     => "Delivered",    // Status
    "d_pin"  => "313001",       // Destination Pincode
    "o_pin"  => "312203",       // Origin Pincode
    "cgm"    => "800",           // Weight in grams
    "pt"     => "Pre-paid"      // Payment type
];

$url = "https://track.delhivery.com/api/kinko/v1/invoice/charges/.json?" . http_build_query($params);

// HTTP Request Context
$options = [
    "http" => [
        "method"  => "GET",
        "header"  =>
            "Authorization: Token $apiKey\r\n" .
            "Content-Type: application/json\r\n",
        "timeout" => 30
    ]
];

$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

// Response Handling
if ($response === false) {
    echo "<h3>Request Failed</h3>";
    echo "<pre>";
    print_r(error_get_last());
    echo "</pre>";
} else {
    echo "<h3>Delhivery Response</h3>";
    echo "<pre>";
    $data = json_decode($response, true);
    $totalAmount = $data[0]['total_amount'];
    echo "Total Charge: " . $totalAmount;

    echo "</pre>";
}

?>