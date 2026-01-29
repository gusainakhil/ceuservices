<?php
include 'config.php';
include "connect.php";
include "functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: subscription.php");
    exit();
}

// Get form data
$plan_type = mysqli_real_escape_string($con, $_POST['plan_type']);
$plan_name = mysqli_real_escape_string($con, $_POST['plan_name']);
$price = floatval($_POST['price']);

$fname = mysqli_real_escape_string($con, $_POST['fname']);
$lname = mysqli_real_escape_string($con, $_POST['lname']);
$company_name = mysqli_real_escape_string($con, $_POST['company_name']);
$job_profile = mysqli_real_escape_string($con, $_POST['job_profile']);
$number = mysqli_real_escape_string($con, $_POST['number']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$country = mysqli_real_escape_string($con, $_POST['country']);
$city = mysqli_real_escape_string($con, $_POST['city']);
$address1 = mysqli_real_escape_string($con, $_POST['address1']);
$address2 = mysqli_real_escape_string($con, $_POST['address2']);
$state = mysqli_real_escape_string($con, $_POST['state']);
$pin_code = mysqli_real_escape_string($con, $_POST['pin_code']);

// Generate user ID
$user_id = "";
if (!empty($_SESSION['hash_id'])) {
    $user_id = $_SESSION['hash_id'];
} else {
    if (empty($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = 'guest_' . uniqid() . '_' . time();
    }
    $user_id = $_SESSION['guest_id'];
}

// Generate unique order ID
$order_id = 'SUB_' . strtoupper(uniqid()) . '_' . time();
$datetime = date("Y-m-d H:i:s");

// Calculate subscription dates
$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime('+1 year'));

// Insert subscription order into subscription_order table (all customer data stored here)
$insert_query = "INSERT INTO subscription_order (
    user_id, order_id, plan_type, plan_name, price,
    first_name, last_name, email, phone, company_name, job_title,
    country, city, state, address1, address2, zip_code,
    payment_status, payment_method, payment_currency,
    start_date, end_date, is_active, created_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$ins_order = $con->prepare($insert_query);
$payment_status = 'pending';
$payment_method = 'paypal';
$currency = PAYPAL_CURRENCY;
$is_active = 0; // Will be activated after payment

$ins_order->bind_param(
    "ssssdsssssssssssssssssss",
    $user_id, $order_id, $plan_type, $plan_name, $price,
    $fname, $lname, $email, $number, $company_name, $job_profile,
    $country, $city, $state, $address1, $address2, $pin_code,
    $payment_status, $payment_method, $currency,
    $start_date, $end_date, $is_active, $datetime
);

if ($ins_order->execute()) {
    $ins_order->close();
    
    // Store order_id in session for success page
    $_SESSION['subscription_order_id'] = $order_id;
    
    // Prepare PayPal redirect
    $paypal_url = PAYPAL_URL;
    $item_name = "Subscription Plan " . $plan_type . " - " . $plan_name . " (12 Months)";
    
    $params = [
        'cmd'           => '_xclick',
        'business'      => PAYPAL_ID,
        'item_name'     => $item_name,
        'item_number'   => $order_id,
        'amount'        => number_format($price, 2, '.', ''),
        'currency_code' => PAYPAL_CURRENCY,
        'return'        => str_replace('success.php', 'success2.php', PAYPAL_RETURN_URL) . '?order_id=' . $order_id,
        'cancel_return' => PAYPAL_CANCEL_URL . '?type=subscription',
        'notify_url'    => PAYPAL_NOTIFY_URL . '?type=subscription',
        'custom'        => $order_id, // Pass order ID for IPN
    ];
    
    header("Location: {$paypal_url}?" . http_build_query($params));
    exit;
    
} else {
    // Error handling
    error_log("Subscription order error: " . mysqli_error($con));
    $_SESSION['error'] = "Order creation failed. Please try again.";
    header("Location: checkout2.php?plan=" . $plan_type . "&price=" . $price);
    exit();
}
?>
