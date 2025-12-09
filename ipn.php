<?php
// ipn_listener.php

include 'connect.php';  // DB connection

// Read raw POST data from PayPal
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = [];

foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2) {
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
}

// Build the validation request to send back to PayPal
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}

// Post the data back to PayPal to validate
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');  // live URL
// For sandbox testing use:
// $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);

$res = curl_exec($ch);
curl_close($ch);

// Check PayPal response
if (strcmp($res, "VERIFIED") == 0) {
    // IPN message is verified

    // Extract required data safely
    $txnId = $myPost['txn_id'] ?? '';
    $paymentStatus = $myPost['payment_status'] ?? '';
    $payerName = trim(($myPost['first_name'] ?? '') . ' ' . ($myPost['last_name'] ?? ''));
    $payerEmail = $myPost['payer_email'] ?? '';
    $amount = $myPost['mc_gross'] ?? '';
    $currency = $myPost['mc_currency'] ?? '';
    $paymentDateRaw = $myPost['payment_date'] ?? '';
    $paymentDate = $paymentDateRaw ? date('Y-m-d H:i:s', strtotime($paymentDateRaw)) : date('Y-m-d H:i:s');
    $payerId = $myPost['payer_id'] ?? '';
    $itemNumber = $myPost['item_number'] ?? '';
    $paymentFee = $myPost['payment_fee'] ?? '';
    $paymentType = $myPost['payment_type'] ?? '';
    $handlingAmount = $myPost['handling_amount'] ?? '';
    $txnType = $myPost['txn_type'] ?? '';

    // 1. Prevent duplicate txn_id
    $stmt = $con->prepare("SELECT txn_id FROM order_details WHERE txn_id = ?");
    $stmt->bind_param("s", $txnId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // txn_id not found, update order_details by order_id = item_number
        $stmt->close();

        $update = $con->prepare("UPDATE order_details SET payment_status = ?, name = ?, txn_id = ?, payment_gross = ?, cc = ?, payment_date = ?, PayerID = ?, payer_email = ?, payment_fee = ?, payment_type = ?, handling_amount = ?, txn_type = ? WHERE order_id = ?");
        if (!$update) {
            error_log("Prepare failed: (" . $con->errno . ") " . $con->error);
            exit;
        }

        $update->bind_param("sssssssssssss", 
            $paymentStatus, $payerName, $txnId, $amount, $currency, $paymentDate, 
            $payerId, $payerEmail, $paymentFee, $paymentType, $handlingAmount, $txnType, $itemNumber
        );

        if (!$update->execute()) {
            error_log("Execute failed: (" . $update->errno . ") " . $update->error);
            exit;
        }

        $update->close();
    } else {
        // txn_id already processed - ignore duplicate IPN
        $stmt->close();
    }

    $con->close();

} else if (strcmp($res, "INVALID") == 0) {
    // IPN invalid, log for investigation
    error_log("Invalid IPN: $raw_post_data");
} else {
    // Unexpected response
    error_log("Unexpected IPN response: $res");
}
?>
