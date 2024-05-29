<?php
// Replace these placeholders with your actual sandbox credentials
$clientId = 'AX5BwSWZA34xHO9bSL0GPL8-hkhvr3Wo_mJi2lceKlrgU-RjuKO4ky-xffA5PQ_8b2C7DZ48wWsgT_M9';
$clientSecret = 'EMWcuIF-x0W_0mICZQhMxhPpTtAT0TtQTabKDjB4tA03MR1baEz3Nvg4C1XzOk5fcKOpHvLRiV6CpKj2';

// Set up the payment amount and currency
$amount = $_POST['amount']; // Amount in cents or smallest currency unit
$currency = 'USD';

// Set up your product details
$productName = $_POST['product_name'];
$productDescription = $_POST['product_description'];

// Set up PayPal API endpoints for sandbox
$apiEndpoint = 'https://sandbox.paypal.com';
$redirectUrl = 'THANK_YOU_PAGE_URL'; // Replace with your thank you page URL

// Set up PayPal API request headers
$headers = [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$clientId:$clientSecret")
];

// Set up PayPal API request body
$body = [
    'intent' => 'sale',
    'payer' => [
        'payment_method' => 'paypal'
    ],
    'transactions' => [
        [
            'amount' => [
                'total' => number_format($amount / 100, 2),
                'currency' => $currency
            ],
            'description' => $productDescription
        ]
    ],
    'redirect_urls' => [
        'return_url' => $redirectUrl . '?success=true',
        'cancel_url' => $redirectUrl . '?success=false'
    ]
];

// Make API call to PayPal
$ch = curl_init($apiEndpoint . '/v1/payments/payment');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Parse PayPal API response
$payment = json_decode($response, true);

// Redirect user to PayPal for payment authorization
if(isset($payment['id'])) {
    header('Location: ' . $payment['links'][1]['href']); // Redirect to PayPal for payment authorization
    exit;
}
 else {
    echo "Payment failed. Please try again later.";
}
?>
