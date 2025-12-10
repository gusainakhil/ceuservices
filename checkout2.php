<!DOCTYPE html>
<html>
<?php
include "connect.php";
include_once "config.php";
include "functions.php";

// Get subscription plan from URL
if (empty($_GET['plan']) || empty($_GET['price'])) {
    header("Location: subscription.php");
    exit();
}

$plan_type = $_GET['plan'];
$price = $_GET['price'];

// Define subscription plans
$plans = [
    'A' => [
        'name' => '2025 WEBINAR ACCESS',
        'price' => 299,
        'features' => ['All Webinars of 2025', 'Downloadable to Your PC', 'All Handouts + Study Materials', 'Share with Your Team & Friends']
    ],
    'B' => [
        'name' => '2025 COMPLETE LIBRARY ACCESS',
        'price' => 355,
        'badge' => 'POPULAR',
        'features' => ['All Webinars of 2025', 'Downloadable to Your PC', 'All Handouts + Study Materials', 'Share with Your Team & Friends', 'Downloadable e-Transcripts of All Webinars']
    ],
    'C' => [
        'name' => '2025-2026 ULTIMATE ACCESS',
        'price' => 399,
        'features' => ['All in Plan B', 'Access to Live Webinars of January 2026', 'Recordings of Webinars of January 2026', 'e-Transcripts of Webinars of January 2026']
    ]
];

// Validate plan
if (!isset($plans[$plan_type])) {
    header("Location: subscription.php");
    exit();
}

$current_plan = $plans[$plan_type];

// Get user info if logged in
if (!empty($_SESSION['email']) && !empty($_SESSION['password'])) {
    $update_sql = mysqli_query($con, "SELECT * FROM user_info WHERE email='" . $_SESSION['email'] . "' ");
    $update_row = mysqli_fetch_assoc($update_sql);
    $full_name = explode(" ", $update_row['name']);
}

?>

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Checkout - Subscription Plan <?php echo $plan_type; ?></title>
    <meta name="description" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/Favicon.png" />
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/icomoon.css" />
    <link rel="stylesheet" href="assets/css/vendor/remixicon.css" />
    <link rel="stylesheet" href="assets/css/vendor/magnifypopup.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/odometer.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/lightbox.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/animation.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/jquery-ui-min.css" />
    <link rel="stylesheet" href="assets/css/vendor/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/tipped.min.css" />
    <link rel="stylesheet" href="assets/css/app.css" />
    <style type="text/css">
        .subscription-badge {
            background: linear-gradient(135deg, #1AB69D 0%, #0f9b84 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .plan-features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .plan-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .plan-features li:last-child {
            border-bottom: none;
        }
        
        .plan-features i {
            color: #1AB69D;
            margin-right: 10px;
        }
        
        .order-summery h4 {
            color: #1AB69D;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div id="main-wrapper" class="main-wrapper">
        <?php include "header.php" ?>
        
        <div class="edu-breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-inner">
                    <div class="page-title">
                        <h1 class="title">Subscription Checkout</h1>
                    </div>
                    <ul class="edu-breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="separator"><i class="icon-angle-right"></i></li>
                        <li class="breadcrumb-item"><a href="subscription.php">Subscription</a></li>
                        <li class="separator"><i class="icon-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ul>
                </div>
            </div>
            <ul class="shape-group">
                <li class="shape-1"><span></span></li>
                <li class="shape-2 scene"><img data-depth="2" src="assets/images/about/shape-13.png" alt="shape" /></li>
                <li class="shape-4"><span></span></li>
                <li class="shape-5 scene"><img data-depth="2" src="assets/images/about/shape-07.png" alt="shape" /></li>
            </ul>
        </div>
        
        <section class="checkout-page-area section-gap-equal">
            <div class="container">
                <form id="checkout_form" method="POST" action="process_subscription_order.php">
                    <input type="hidden" name="plan_type" value="<?php echo $plan_type; ?>">
                    <input type="hidden" name="plan_name" value="<?php echo $current_plan['name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $price; ?>">
                    
                    <div class="row row--25">
                        <div class="col-lg-6">
                            <div class="checkout-billing">
                                <h3 class="title">Billing Details</h3>
                                
                                <div class="row g-lg-5">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>First Name*</label>
                                            <input type="text" id="fname" name="fname" value="<?php echo isset($full_name[0]) ? $full_name[0] : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Last Name*</label>
                                            <input type="text" id="lname" name="lname" value="<?php echo isset($full_name[1]) ? $full_name[1] : ''; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-lg-5">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Company Name*</label>
                                            <input type="text" id="company_name" name="company_name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Job Title*</label>
                                            <input type="text" id="job_profile" name="job_profile" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-lg-5">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Phone*</label>
                                            <input type="tel" id="number" name="number" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Email Address*</label>
                                            <input type="email" id="email" name="email" value="<?php echo isset($update_row['email']) ? $update_row['email'] : ''; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-lg-5">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Country*</label>
                                            <select id="country" name="country" required>
                                                <option>Select Option</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Canada">Canada</option>
                                                <option value="England">England</option>
                                                <option value="New Zealand">New Zealand</option>
                                                <option value="Switzerland">Switzerland</option>
                                                <option value="United Kingdom (UK)">United Kingdom (UK)</option>
                                                <option value="United States (USA)" selected>United States (USA)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>City*</label>
                                            <input type="text" id="city" name="city" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Address 1*</label>
                                    <input type="text" id="address1" name="address1" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Address 2</label>
                                    <input type="text" id="address2" name="address2">
                                </div>
                                
                                <div class="row g-lg-5">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>State*</label>
                                            <input type="text" id="state" name="state" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Zip Code*</label>
                                            <input type="text" id="pin_code" name='pin_code' required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="order-summery">
                                <h4 class="title">Your Subscription</h4>
                                
                                <div class="subscription-details" style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                                    <span class="subscription-badge">PLAN <?php echo $plan_type; ?></span>
                                    <?php if (isset($current_plan['badge'])) { ?>
                                        <span class="subscription-badge" style="background: #ffc107; color: #000;"><?php echo $current_plan['badge']; ?></span>
                                    <?php } ?>
                                    
                                    <h5 style="margin: 15px 0; color: #333;"><?php echo $current_plan['name']; ?></h5>
                                    
                                    <ul class="plan-features">
                                        <?php foreach ($current_plan['features'] as $feature) { ?>
                                            <li><i class="icon-20"></i> <?php echo $feature; ?></li>
                                        <?php } ?>
                                    </ul>
                                    
                                    <p style="color: #666; font-size: 14px; margin-top: 15px;">
                                        <i class="icon-calendar"></i> 12 months access from purchase date
                                    </p>
                                </div>
                                
                                <table class="table summery-table">
                                    <tbody>
                                        <tr class="order-subtotal">
                                            <td>Subscription Plan <?php echo $plan_type; ?></td>
                                            <td>$<?php echo number_format($price, 2); ?></td>
                                        </tr>
                                        <tr class="order-total">
                                            <td>Order Total</td>
                                            <td><strong style="color: #1AB69D;">$<?php echo number_format($price, 2); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div class="checkout-payment">
                                    <div class="payment-method">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_stripe" value="stripe" checked required>
                                            <label class="form-check-label" for="payment_stripe">
                                                Credit Card (Stripe)
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" id="submitBtn" class="edu-btn btn-medium w-100" style="margin-top: 20px;">
                                        Complete Payment <i class="icon-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        
        <?php include "footer.php" ?>
    </div>
    
    <div class="rn-progress-parent">
        <svg class="rn-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    
    <!-- JS -->
    <script src="assets/js/vendor/modernizr.min.js"></script>
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/vendor/sal.min.js"></script>
    <script src="assets/js/vendor/backtotop.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script>
        function disableBtn() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        }
        
        // Disable button on form submit
        document.getElementById('checkout_form').addEventListener('submit', function() {
            disableBtn();
        });
    </script>
</body>
</html>
