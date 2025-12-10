<?php
// Include PHPMailer classes
require_once __DIR__ . '/phpmailer/src/Exception.php';
require_once __DIR__ . '/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/phpmailer/src/SMTP.php';

include 'connect.php';  
require 'config.php';

// Get order ID from URL
$orderId = $_GET['order_id'] ?? '';

if (empty($orderId)) {
    header("Location: subscription.php");
    exit();
}

// Get PayPal data from URL
$paymentStatus = $_GET['payment_status'] ?? '';
$txnId = $_GET['txn_id'] ?? '';
$amount = $_GET['mc_gross'] ?? '';
$currency = $_GET['mc_currency'] ?? '';
$paymentDate = $_GET['payment_date'] ?? date('Y-m-d H:i:s');
$payer_id = $_GET['PayerID'] ?? '';
$payer_email = $_GET['payer_email'] ?? '';

// Update subscription order payment status
if ($txnId) {
    $stmt = $con->prepare("SELECT * FROM subscription_order WHERE txn_id = ?");
    $stmt->bind_param("s", $txnId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Update by order_id
        $stmt->close();
        $isActive = 1;
        $update = $con->prepare("UPDATE subscription_order SET 
            payment_status = 'Completed', 
            txn_id = ?, 
            payer_email = ?, 
            payment_amount = ?, 
            payment_currency = ?, 
            payment_date = ?,
            is_active = ?,
            updated_at = NOW()
            WHERE order_id = ?");
        
        $update->bind_param("sssssds", $txnId, $payer_email, $amount, $currency, $paymentDate, $isActive, $orderId);
        $update->execute();
        $update->close();
    } else {
        $stmt->close();
    }
}

// Fetch subscription order details
$stmt = $con->prepare("SELECT * FROM subscription_order WHERE order_id = ?");
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Order not found!";
    exit();
}

$order = $result->fetch_assoc();
$stmt->close();

// Prepare email content
$customer_name = $order['first_name'] . ' ' . $order['last_name'];
$customer_email = $order['email'];
$plan_name = "Plan " . $order['plan_type'] . " - " . $order['plan_name'];
$subscription_start = date('F d, Y', strtotime($order['start_date']));
$subscription_end = date('F d, Y', strtotime($order['end_date']));

$email_subject = "Subscription Activated - CEU Services";

$email_body = "
<html>
<head>
    <title>Subscription Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 700px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 30px; border: 2px solid #667eea; border-top: none; border-radius: 0 0 10px 10px; background: #f8f9fa; }
        .invoice-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .invoice-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee; }
        .invoice-row:last-child { border-bottom: none; font-weight: bold; font-size: 1.2em; color: #667eea; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 0.9em; color: #777; padding: 20px; }
        .btn { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .success-icon { font-size: 50px; color: #28a745; }
    </style>
</head>
<body>
    <div class='header'>
        <div class='success-icon'>✓</div>
        <h1>Subscription Activated!</h1>
    </div>
    <div class='content'>
        <p>Dear <strong>{$customer_name}</strong>,</p>
        <p>Thank you for subscribing to CEU Services! Your annual subscription has been successfully activated.</p>
        
        <div class='invoice-box'>
            <h3 style='color: #667eea; margin-top: 0;'>Subscription Details</h3>
            <div class='invoice-row'>
                <span class='label'>Order ID:</span>
                <span class='value'>{$order['order_id']}</span>
            </div>
            <div class='invoice-row'>
                <span class='label'>Plan:</span>
                <span class='value'>{$plan_name}</span>
            </div>
            <div class='invoice-row'>
                <span class='label'>Subscription Period:</span>
                <span class='value'>12 Months</span>
            </div>
            <div class='invoice-row'>
                <span class='label'>Start Date:</span>
                <span class='value'>{$subscription_start}</span>
            </div>
            <div class='invoice-row'>
                <span class='label'>End Date:</span>
                <span class='value'>{$subscription_end}</span>
            </div>
            <div class='invoice-row'>
                <span class='label'>Transaction ID:</span>
                <span class='value'>{$order['txn_id']}</span>
            </div>
            <div class='invoice-row'>
                <span class='label'>Amount Paid:</span>
                <span class='value'>\${$order['price']} {$order['payment_currency']}</span>
            </div>
        </div>
        
        <div class='invoice-box'>
            <h3 style='color: #667eea; margin-top: 0;'>Billing Information</h3>
            <p><strong>Name:</strong> {$customer_name}</p>
            <p><strong>Email:</strong> {$customer_email}</p>
            <p><strong>Phone:</strong> {$order['phone']}</p>
            <p><strong>Company:</strong> {$order['company_name']}</p>
            <p><strong>Address:</strong> {$order['address1']}, {$order['city']}, {$order['state']} {$order['zip_code']}, {$order['country']}</p>
        </div>
        
        <p style='margin-top: 30px;'>You can now access all premium features and webinars. Login to your dashboard to get started!</p>
        
        <center>
            <a href='https://ceuservices.com/dashboard.php' class='btn'>Go to Dashboard</a>
        </center>
        
        <p style='margin-top: 30px;'>If you have any questions, please don't hesitate to contact our support team.</p>
        <p>Best regards,<br><strong>CEU Services Team</strong></p>
    </div>
    <div class='footer'>
        <p>© " . date('Y') . " CEU Services. All rights reserved.</p>
        <p style='font-size: 0.85em;'>This is an automated email. Please do not reply directly to this message.</p>
    </div>
</body>
</html>";

// Send email using PHPMailer
require 'vendor/autoload.php';
$mail = new PHPMailer\PHPMailer\PHPMailer(true);
$email_sent = false;

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = "mail.ceuservices.com";
    $mail->SMTPAuth = true;
    $mail->Username = "info@ceuservices.com";
    $mail->Password = "PWz=ox#}HO0W";
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('info@ceuservices.com', 'CEU Services');
    $mail->addAddress($customer_email, $customer_name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $email_subject;
    $mail->Body = $email_body;
    $mail->AltBody = strip_tags(str_replace(['<div>', '</div>', '<p>', '</p>'], ["\n", "\n", "\n", "\n"], $email_body));

    $mail->send();
    $email_sent = true;
} catch (Exception $e) {
    error_log("Email sending failed: {$mail->ErrorInfo}");
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Activated - CEU Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .success-card {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            animation: scaleIn 0.5s ease-in-out;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .card-body-custom {
            padding: 40px;
        }
        .invoice-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
            border: 2px solid #667eea;
        }
        .invoice-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .invoice-row:last-child {
            border-bottom: none;
            font-size: 1.3em;
            font-weight: bold;
            color: #667eea;
            margin-top: 10px;
        }
        .btn-print {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1.1em;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-print:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-home {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1.1em;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .email-notice {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        @media print {
            body {
                background: white;
            }
            .no-print {
                display: none;
            }
            .success-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card">
            <div class="card-header-custom">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="mt-3">Subscription Activated!</h1>
                <p class="mb-0">Thank you for subscribing to CEU Services</p>
            </div>
            
            <div class="card-body-custom">
                <?php if ($email_sent): ?>
                <div class="email-notice">
                    <i class="fas fa-envelope"></i> A confirmation email has been sent to <strong><?= htmlspecialchars($customer_email) ?></strong>
                </div>
                <?php endif; ?>
                
                <div class="invoice-section">
                    <h3 class="text-center mb-4" style="color: #667eea;">
                        <i class="fas fa-file-invoice"></i> Subscription Invoice
                    </h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($customer_name) ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                            <p class="mb-1"><strong>Company:</strong> <?= htmlspecialchars($order['company_name']) ?></p>
                            <p class="mb-1"><strong>Job Title:</strong> <?= htmlspecialchars($order['job_title']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Billing Address</h5>
                            <p class="mb-1"><?= htmlspecialchars($order['address1']) ?></p>
                            <?php if (!empty($order['address2'])): ?>
                            <p class="mb-1"><?= htmlspecialchars($order['address2']) ?></p>
                            <?php endif; ?>
                            <p class="mb-1"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['zip_code']) ?></p>
                            <p class="mb-1"><?= htmlspecialchars($order['country']) ?></p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="invoice-row">
                        <span><strong>Order ID:</strong></span>
                        <span><?= htmlspecialchars($order['order_id']) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Plan:</strong></span>
                        <span><?= htmlspecialchars($plan_name) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Subscription Period:</strong></span>
                        <span>12 Months</span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Start Date:</strong></span>
                        <span><?= $subscription_start ?></span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>End Date:</strong></span>
                        <span><?= $subscription_end ?></span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Payment Method:</strong></span>
                        <span>PayPal</span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Transaction ID:</strong></span>
                        <span><?= htmlspecialchars($order['txn_id']) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Payment Date:</strong></span>
                        <span><?= date('F d, Y', strtotime($order['payment_date'])) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span><strong>Total Amount Paid:</strong></span>
                        <span>$<?= number_format($order['price'], 2) ?> <?= htmlspecialchars($order['payment_currency']) ?></span>
                    </div>
                </div>
                
                <div class="text-center mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-print me-3">
                        <i class="fas fa-print"></i> Print Invoice
                    </button>
                    <a href="dashboard.php" class="btn btn-home me-3">
                        <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                    </a>
                    <a href="index.php" class="btn btn-home">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
