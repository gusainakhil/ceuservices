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
    <title>Subscription Invoice - CEU Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/Favicon.png" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
        }
        .invoice-header {
            background: #1ab69d;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        .invoice-header h1 {
            font-size: 26px;
            margin-bottom: 5px;
        }
        .invoice-header p {
            font-size: 14px;
            opacity: 0.95;
        }
        .invoice-body {
            padding: 30px;
        }
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .section-title {
            color: #1ab69d;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #1ab69d;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            font-size: 13px;
            padding: 8px 0;
        }
        .info-item strong {
            color: #333;
            display: inline-block;
            min-width: 100px;
        }
        .info-item span {
            color: #666;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .invoice-table tr {
            border-bottom: 1px solid #eee;
        }
        .invoice-table td {
            padding: 10px 5px;
            font-size: 13px;
        }
        .invoice-table td:first-child {
            font-weight: 600;
            color: #555;
            width: 50%;
        }
        .invoice-table td:last-child {
            text-align: right;
            color: #333;
        }
        .invoice-total {
            background: #f8f9fa;
            padding: 12px 5px;
            font-size: 16px;
            font-weight: 700;
            color: #1ab69d;
        }
        .action-buttons {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 25px;
            margin: 0 5px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-print {
            background: #1ab69d;
            color: white;
        }
        .btn-print:hover {
            background: #0f7c6f;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .invoice-footer {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                border: none;
                max-width: 100%;
            }
            .invoice-header {
                background: #1ab69d !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .action-buttons, .success-message {
                display: none;
            }
            .invoice-body {
                padding: 20px;
            }
            .section-title {
                font-size: 14px;
                margin: 15px 0 8px 0;
            }
            .info-item {
                font-size: 11px;
                padding: 5px 0;
            }
            .invoice-table td {
                padding: 8px 5px;
                font-size: 11px;
            }
            .invoice-total {
                font-size: 14px;
                padding: 10px 5px;
            }
            .invoice-footer {
                font-size: 10px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>✓ Subscription Activated!</h1>
            <p>Thank you for subscribing to CEU Services</p>
        </div>
        
        <!-- Body -->
        <div class="invoice-body">
            <?php if ($email_sent): ?>
            <div class="success-message">
                ✉ A confirmation email has been sent to <strong><?= htmlspecialchars($customer_email) ?></strong>
            </div>
            <?php endif; ?>
            
            <h2 class="section-title">Customer Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Name:</strong> <span><?= htmlspecialchars($customer_name) ?></span>
                </div>
                <div class="info-item">
                    <strong>Email:</strong> <span><?= htmlspecialchars($order['email']) ?></span>
                </div>
                <div class="info-item">
                    <strong>Phone:</strong> <span><?= htmlspecialchars($order['phone']) ?></span>
                </div>
                <div class="info-item">
                    <strong>Company:</strong> <span><?= htmlspecialchars($order['company_name']) ?></span>
                </div>
            </div>
            
            <h2 class="section-title">Billing Address</h2>
            <div class="info-item">
                <span><?= htmlspecialchars($order['address1']) ?><?= !empty($order['address2']) ? ', ' . htmlspecialchars($order['address2']) : '' ?></span>
            </div>
            <div class="info-item">
                <span><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['zip_code']) ?>, <?= htmlspecialchars($order['country']) ?></span>
            </div>
            
            <h2 class="section-title">Subscription Details</h2>
            <table class="invoice-table">
                <tr>
                    <td>Order ID</td>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                </tr>
                <tr>
                    <td>Plan</td>
                    <td><?= htmlspecialchars($plan_name) ?></td>
                </tr>
                <tr>
                    <td>Subscription Period</td>
                    <td>12 Months</td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td><?= $subscription_start ?></td>
                </tr>
                <tr>
                    <td>End Date</td>
                    <td><?= $subscription_end ?></td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>PayPal</td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td><?= htmlspecialchars($order['txn_id']) ?></td>
                </tr>
                <tr>
                    <td>Payment Date</td>
                    <td><?= date('F d, Y', strtotime($order['payment_date'])) ?></td>
                </tr>
                <tr class="invoice-total">
                    <td>Total Amount Paid</td>
                    <td>$<?= number_format($order['price'], 2) ?> <?= htmlspecialchars($order['payment_currency']) ?></td>
                </tr>
            </table>
            
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-print">🖨 Print Invoice</button>
                <a href="dashboard.php" class="btn btn-secondary">📊 Dashboard</a>
                <a href="index.php" class="btn btn-secondary">🏠 Home</a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            © <?= date('Y') ?> CEU Services. All rights reserved. | Contact: info@ceuservices.com | Phone: (+1)-432-755-5553
        </div>
    </div>
    
    <script>
        // Auto focus for better UX
        window.onload = function() {
            document.body.style.opacity = '1';
        };
    </script>
</body>
</html>
