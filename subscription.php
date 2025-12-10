<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
include "connect.php";
include "functions.php";

// Generate user ID for cart
$user_id = "";
if (!empty($_SESSION['hash_id'])) {
    $user_id = $_SESSION['hash_id'];
} else {
    // Generate unique guest ID if not exists
    if (empty($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = 'guest_' . uniqid() . '_' . time();
    }
    $user_id = $_SESSION['guest_id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>subscription</title>
    <meta name="description" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/Favicon.png" />
    <link rel="canonical" href="https://ceuservices.com/webinar" />

    <!-- CSS
    ============================================ -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/icomoon.css" />
    <link rel="stylesheet" href="assets/css/vendor/remixicon.css" />
    <link rel="stylesheet" href="assets/css/vendor/magnifypopup.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/odometer.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/lightbox.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/animation.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/jqueru-ui-min.css" />
    <link rel="stylesheet" href="assets/css/vendor/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/css/vendor/tipped.min.css" />
    <!--<link href="assets/Calender/EventCalender.css" rel="stylesheet" type="text/css" />-->
    <!--<script src="assets/Calender/EventCalender.js" type="text/javascript"></script>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Site Stylesheet -->
    <link rel="stylesheet" href="assets/css/app.css" />

    <!-- User ID for JavaScript -->
    <script>
        var userSessionId = '<?php echo $user_id; ?>';
    </script>
    <style type="text/css">
        .parentSpace {
            width: 100%;
            display: block;
            color: white;
        }

        .left {
            float: left;
            width: 50%;
        }

        .right {
            float: right;
            width: 50%;
        }

        input {
            height: 50px;
            border-collapse: collapse;
            border-radius: 3px 3px 3px 3px;
            background-color: #fff;
            border-width: 0px;
        }

        /* Subscription Plans Styles */
        .subscription-plans-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            overflow: hidden;
        }

        .subscription-plans-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%231AB69D" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 2px solid transparent;
            overflow: hidden;
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1AB69D, #14967e);
        }

        .pricing-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(26, 182, 157, 0.25);
            border-color: #1AB69D;
        }

        .pricing-card.featured-plan {
            background: linear-gradient(135deg, #1AB69D 0%, #0f9b84 100%);
            color: white;
            transform: scale(1.08);
            border: none;
        }

        .pricing-card.featured-plan::before {
            background: linear-gradient(90deg, #fff, rgba(255, 255, 255, 0.5));
            height: 3px;
        }

        .pricing-card.featured-plan:hover {
            transform: translateY(-15px) scale(1.1);
            box-shadow: 0 30px 70px rgba(26, 182, 157, 0.4);
        }

        .pricing-card.featured-plan .price,
        .pricing-card.featured-plan .title,
        .pricing-card.featured-plan .subtitle,
        .pricing-card.featured-plan .features-list li {
            color: white !important;
        }

        .pricing-card.featured-plan .features-list i {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.2);
        }

        .pricing-card.featured-plan .edu-btn {
            background: white;
            color: #1AB69D;
            font-weight: 700;
        }

        .pricing-card.featured-plan .edu-btn:hover {
            background: #f0f0f0;
            transform: scale(1.05);
        }

        .badge-top {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            color: #1AB69D;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 1px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .plan-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .pricing-card.featured-plan .plan-header {
            border-bottom-color: rgba(255, 255, 255, 0.3);
        }

        .plan-header .title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #1AB69D;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .plan-header .subtitle {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
            line-height: 1.4;
            min-height: 50px;
        }

        .pricing-card.featured-plan .plan-header .subtitle {
            color: white;
        }

        .price-tag {
            margin: 25px 0;
        }

        .price {
            font-size: 56px;
            font-weight: 800;
            color: #1AB69D;
            position: relative;
            display: inline-block;
        }

        .price::before {
            content: '$';
            font-size: 28px;
            position: absolute;
            top: 8px;
            left: -20px;
            font-weight: 600;
        }

        .features-list {
            flex-grow: 1;
            margin: 25px 0;
        }

        .features-list h6 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .pricing-card.featured-plan .features-list h6 {
            color: rgba(255, 255, 255, 0.9);
        }

        .features-list ul {
            padding-left: 0;
            list-style: none;
        }

        .features-list li {
            padding: 12px 0;
            font-size: 14px;
            color: #555;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .features-list li:hover {
            padding-left: 10px;
            color: #1AB69D;
        }

        .features-list i {
            width: 24px;
            height: 24px;
            background: #e8f8f5;
            color: #1AB69D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .card-bottom {
            margin-top: auto;
            padding-top: 25px;
        }

        .card-bottom .edu-btn {
            width: 100%;
            padding: 15px 30px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: linear-gradient(90deg, #1AB69D, #14967e);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-bottom .edu-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .card-bottom .edu-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .card-bottom .edu-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(26, 182, 157, 0.4);
        }

        /* Comparison Table Styles */
        .comparison-table {
            margin-top: 30px;
            border: 1px solid #e0e0e0;
        }

        .comparison-table thead {
            background: linear-gradient(135deg, #1AB69D 0%, #0f9b84 100%);
            color: white;
        }

        .comparison-table thead th {
            padding: 20px;
            font-weight: 600;
            border: none;
        }

        .comparison-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .comparison-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        
        /* Section Styling */
        .section-gap-large {
            padding: 60px 0;
        }

        .bg-color-white {
            background-color: #ffffff;
        }

        /* Comparison Table Styles */
        .comparison-table {
            margin-top: 30px;
            border: 1px solid #e0e0e0;
        }

        .comparison-table thead {
            background: linear-gradient(135deg, #1AB69D 0%, #0f9b84 100%);
            color: white;
        }

        .comparison-table thead th {
            padding: 20px;
            font-weight: 600;
            border: none;
        }

        .comparison-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .comparison-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .comparison-table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
        }

        .comparison-table .icon-20 {
            font-size: 24px;
        }

        .comparison-table .color-secondary {
            color: #1AB69D;
        }

        .comparison-table .color-danger {
            color: #dc3545;
        }

        /* Why Subscribe Section */
        .feature-box-1 {
            display: flex;
            gap: 20px;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-box-1:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
        }

        .feature-box-1 .icon {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1AB69D 0%, #0f9b84 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }

        .feature-box-1 .content h5 {
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* FAQ Section */
        .edu-accordion-item {
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .edu-accordion-button {
            width: 100%;
            text-align: left;
            padding: 20px 25px;
            background: white;
            border: none;
            font-weight: 600;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .edu-accordion-button:hover {
            background-color: #f8f9fa;
            color: #1AB69D;
        }

        .edu-accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #1AB69D 0%, #0f9b84 100%);
            color: white;
        }

        .edu-accordion-body {
            padding: 20px 25px;
            background: #fafafa;
        }

        .edu-accordion-body ul {
            margin-top: 10px;
            padding-left: 20px;
        }

        .edu-accordion-body a {
            color: #1AB69D;
            text-decoration: none;
        }

        .edu-accordion-body a:hover {
            text-decoration: underline;
        }

        /* Section Styling */
        .section-gap-large {
            padding: 60px 0;
        }

        .bg-color-white {
            background-color: #ffffff;
        }

        .section-title {
            margin-bottom: 40px;
        }

        .section-title .title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .shape-line {
            display: inline-block;
            color: #1AB69D;
            font-size: 24px;
        }

        @media (max-width: 991px) {
            .pricing-card.featured-plan {
                transform: scale(1);
            }

            .comparison-table {
                font-size: 14px;
            }

            .feature-box-1 {
                flex-direction: column;
                text-align: center;
            }

            .feature-box-1 .icon {
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>



    <body>

        <div id="main-wrapper" class="main-wrapper">

            <?php include "header.php" ?>
            <div class="edu-breadcrumb-area">
                <div class="container">
                    <div class="breadcrumb-inner">
                        <div class="page-title">
                            <h1 class="title">CEU SUBSCRIPTION PLANS</h1>
                            <h3 class="title">Your One- <span class="color-secondary"> Stop Learning Access for
                                    2025–2026</h3>
                            <span class="shape-line" style="color:#1AB69D"><i class="icon-19"></i></span>
                        </div>

                    </div>
                </div>

                <ul class="shape-group">
                    <li class="shape-1">
                        <span></span>
                    </li>
                    <li class="shape-2 scene"><img data-depth="2" src="assets/images/about/shape-13.png" alt="shape" />
                    </li>
                    <li class="shape-3 scene"><img data-depth="-2" src="assets/images/about/shape-15.png" alt="shape" />
                    </li>
                    <li class="shape-4">
                        <span></span>
                    </li>
                    <li class="shape-5 scene"><img data-depth="2" src="assets/images/about/shape-07.png" alt="shape" />
                    </li>
                </ul>
            </div>
            <br><br>
            <div class="subscription-intro">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 ">
                            <h4 class="intro-text text-center">
                                Unlock the learning you need — when you need it. Our subscription plans give you full
                                access to all live and
                                recorded webinars,
                                transcripts, and upcoming 2026 events. Pick the plan that best fits your
                                professional needs and get ahead.
                            </h4>
                        </div>
                    </div>
                </div>
            </div>


            <!-- #region Subscription  Plans-->

            <!-- Subscription Plans Section -->
            <section class="subscription-plans-section section-gap-large">
                <div class="container">
                    <div class="row g-5">
                        <!-- Plan A -->
                        <div class="col-lg-4 col-md-6">
                            <div class="edu-card card-type-6 radius-small h-100">
                                <div class="inner d-flex flex-column h-100">
                                    <div class="content flex-grow-1">
                                        <div class="card-top">
                                            <h5 class="title">PLAN A</h5>
                                            <h3 class="subtitle">2025 WEBINAR ACCESS</h3>
                                            <div class="price-tag">
                                                <div class="price">$299</div>
                                            </div>
                                        </div>
                                        <div class="features-list">
                                            <h6 class="mb-3">You get:</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="icon-20"></i> All Webinars of 2025</li>
                                                <li><i class="icon-20"></i> Downloadable to Your PC</li>
                                                <li><i class="icon-20"></i> All Handouts + Study Materials</li>
                                                <li><i class="icon-20"></i> Share with Your Team & Friends</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-bottom mt-4">
                                        <a href="checkout2.php?plan=A&price=299"
                                            class="edu-btn btn-medium w-100">Checkout <i class="icon-4"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan B -->
                        <div class="col-lg-4 col-md-6">
                            <div class="edu-card card-type-6 radius-small featured-plan h-100">
                                <div class="badge-top">POPULAR</div>
                                <div class="inner d-flex flex-column h-100">
                                    <div class="content flex-grow-1">
                                        <div class="card-top">
                                            <h5 class="title">PLAN B</h5>
                                            <h3 class="subtitle">2025 COMPLETE LIBRARY ACCESS</h3>
                                            <div class="price-tag">
                                                <div class="price">$355</div>
                                            </div>
                                        </div>
                                        <div class="features-list">
                                            <h6 class="mb-3">You get:</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="icon-20"></i> All Webinars of 2025</li>
                                                <li><i class="icon-20"></i> Downloadable to Your PC</li>
                                                <li><i class="icon-20"></i> All Handouts + Study Materials</li>
                                                <li><i class="icon-20"></i> Share with Your Team & Friends</li>
                                                <li><i class="icon-20"></i> Downloadable e-Transcripts of All Webinars
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-bottom mt-4">
                                        <a href="checkout2.php?plan=B&price=355"
                                            class="edu-btn btn-medium w-100">Checkout <i class="icon-4"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan C -->
                        <div class="col-lg-4 col-md-6">
                            <div class="edu-card card-type-6 radius-small h-100">
                                <div class="inner d-flex flex-column h-100">
                                    <div class="content flex-grow-1">
                                        <div class="card-top">
                                            <h5 class="title">PLAN C</h5>
                                            <h3 class="subtitle">2025-2026 ULTIMATE ACCESS</h3>
                                            <div class="price-tag">
                                                <div class="price">$399</div>
                                            </div>
                                        </div>
                                        <div class="features-list">
                                            <h6 class="mb-3">You get:</h6>
                                            <ul class="list-unstyled">
                                                <li><i class="icon-20"></i> All in Plan B</li>
                                                <li><i class="icon-20"></i> Access to Live Webinars of January 2026</li>
                                                <li><i class="icon-20"></i> Recordings of Webinars of January 2026</li>
                                                <li><i class="icon-20"></i> e-Transcripts of Webinars of January 2026
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-bottom mt-4">
                                        <a href="checkout2.php?plan=C&price=399"
                                            class="edu-btn btn-medium w-100">Checkout <i class="icon-4"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Compare Plans Section -->
            <section class="compare-plans-section section-gap-large bg-color-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-center mb-5">
                                <h2 class="title">COMPARE SUBSCRIPTION PLANS</h2>
                                <span class="shape-line"><i class="icon-19"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table comparison-table">
                                    <thead>
                                        <tr>
                                            <th>Features/Benefits</th>
                                            <th class="text-center">PLAN A</th>
                                            <th class="text-center">PLAN B</th>
                                            <th class="text-center">PLAN C</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Price</strong></td>
                                            <td class="text-center"><strong>$299</strong></td>
                                            <td class="text-center"><strong>$355</strong></td>
                                            <td class="text-center"><strong>$399</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Access to All 2025 Webinars</td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Watch Anytime (On Demand)</td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Download Resources</td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Certificate of Completion (if applicable)</td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Downloadable e-Transcripts</td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Access to Live Webinars of Jan 2026</td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Access to Recordings of Jan 2026</td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Access to e-Transcripts of Jan 2026</td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-close-line color-danger"></i></td>
                                            <td class="text-center"><i class="icon-20 color-secondary"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Why Subscribe Section -->
            <section class="why-subscribe-section section-gap-large">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-center mb-5">
                                <h2 class="title">WHY SUBSCRIBE</h2>
                                <span class="shape-line"><i class="icon-19"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5">
                        <div class="col-lg-6 col-md-6">
                            <div class="feature-box-1">
                                <div class="icon">
                                    <i class="icon-9"></i>
                                </div>
                                <div class="content">
                                    <h5 class="title">All-in-one access</h5>
                                    <p>Instead of buying individual events one by one, get complete access to 2025 (and
                                        January 2026) webinars</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="feature-box-1">
                                <div class="icon">
                                    <i class="icon-13"></i>
                                </div>
                                <div class="content">
                                    <h5 class="title">Full documentation</h5>
                                    <p>With e-Transcripts (in Plan 2 & 3), you get downloadable, reference-ready
                                        materials</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="feature-box-1">
                                <div class="icon">
                                    <i class="icon-26"></i>
                                </div>
                                <div class="content">
                                    <h5 class="title">Save time & money</h5>
                                    <p>Subscribing is very cost-effective than purchasing each webinar separately.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="feature-box-1">
                                <div class="icon">
                                    <i class="icon-45"></i>
                                </div>
                                <div class="content">
                                    <h5 class="title">Flexibility & No Hidden Costs</h5>
                                    <p>Watch recordings on your own schedule, or attend live — wherever you are. And no
                                        surprise fees, no extra charges</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQs Section -->
            <section class="subscription-faq-section section-gap-large bg-color-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title text-center mb-5">
                                <h2 class="title">SUBSCRIPTION FAQs</h2>
                                <span class="shape-line"><i class="icon-19"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <div class="edu-accordion-02" id="subscriptionFaqAccordion">

                                <!-- FAQ 1 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading1">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false"
                                            aria-controls="collapse1">
                                            1. When will I get access after subscribing?
                                        </button>
                                    </div>
                                    <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>Access is granted instantly once your subscription is completed.</p>
                                            <ul>
                                                <li><strong>Plans 1 & 2:</strong> Immediate access to the full 2025
                                                    webinar library.</li>
                                                <li><strong>Plan 3:</strong> Immediate access to 2025 webinars, and
                                                    automatic access to all January 2026 live webinars when they go
                                                    live.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 2 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading2">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false"
                                            aria-controls="collapse2">
                                            2. How will I receive the e-Transcripts?
                                        </button>
                                    </div>
                                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>For Plans 2 & 3, all e-Transcripts will be available for immediate
                                                download inside your account. You can view them online or save them for
                                                future reference.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 3 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading3">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false"
                                            aria-controls="collapse3">
                                            3. Are live webinars recorded?
                                        </button>
                                    </div>
                                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>Yes. All live webinars are recorded and added to your dashboard within
                                                24–48 hours after the session.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 4 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading4">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false"
                                            aria-controls="collapse4">
                                            4. How long does my subscription last?
                                        </button>
                                    </div>
                                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>Your subscription gives access to all included webinars for 12 months
                                                from the date of purchase.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 5 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading5">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false"
                                            aria-controls="collapse5">
                                            5. Can I upgrade my plan later?
                                        </button>
                                    </div>
                                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>Yes. You can upgrade anytime by paying only the difference between your
                                                current plan and the new plan.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 6 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading6">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false"
                                            aria-controls="collapse6">
                                            6. Do you offer refunds?
                                        </button>
                                    </div>
                                    <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>We offer refunds within the first 7 days if you haven't accessed or
                                                downloaded any content. After that period, subscriptions become
                                                non-refundable due to digital access.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 7 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading7">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false"
                                            aria-controls="collapse7">
                                            7. Can I share my login with others?
                                        </button>
                                    </div>
                                    <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>No. For security and certification purposes, accounts are single-user
                                                only. Multiple logins may result in account suspension.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 8 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading8">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false"
                                            aria-controls="collapse8">
                                            8. What formats are the transcripts in?
                                        </button>
                                    </div>
                                    <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading8"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>Transcripts are provided in universally supported PDF format, easy to
                                                download, print, and reference for compliance.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 9 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading9">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false"
                                            aria-controls="collapse9">
                                            9. Do I need special software to watch the webinars?
                                        </button>
                                    </div>
                                    <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="heading9"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>No special software required. All webinars play directly through your
                                                browser on desktop, tablet, or mobile.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- FAQ 10 -->
                                <div class="edu-accordion-item">
                                    <div class="edu-accordion-header" id="heading10">
                                        <button class="edu-accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false"
                                            aria-controls="collapse10">
                                            10. Who can I contact if I need support?
                                        </button>
                                    </div>
                                    <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="heading10"
                                        data-bs-parent="#subscriptionFaqAccordion">
                                        <div class="edu-accordion-body">
                                            <p>You can reach our support team at <a
                                                    href="mailto:support@ceuservices.com">support@ceuservices.com</a> or
                                                through the Contact Us page.</p>
                                            <p>We're available Monday–Friday, 9 AM to 6 PM EST.</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php include "footer.php" ?>

        </div>
        <div class="rn-progress-parent">
            <svg class="rn-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
        <!-- JS
    ============================================ -->
        <!-- Modernizer JS -->
        <script src="assets/js/vendor/modernizr.min.js"></script>
        <!-- Jquery Js -->
        <script src="assets/js/vendor/jquery.min.js"></script>
        <script src="assets/js/vendor/bootstrap.min.js"></script>
        <script src="assets/js/vendor/sal.min.js"></script>
        <script src="assets/js/vendor/backtotop.min.js"></script>
        <script src="assets/js/vendor/magnifypopup.min.js"></script>
        <script src="assets/js/vendor/jquery.countdown.min.js"></script>
        <script src="assets/js/vendor/odometer.min.js"></script>
        <script src="assets/js/vendor/isotop.min.js"></script>
        <script src="assets/js/vendor/imageloaded.min.js"></script>
        <script src="assets/js/vendor/lightbox.min.js"></script>
        <script src="assets/js/vendor/paralax.min.js"></script>
        <script src="assets/js/vendor/paralax-scroll.min.js"></script>
        <script src="assets/js/vendor/jquery-ui.min.js"></script>
        <script src="assets/js/vendor/swiper-bundle.min.js"></script>
        <script src="assets/js/vendor/svg-inject.min.js"></script>
        <script src="assets/js/vendor/vivus.min.js"></script>
        <script src="assets/js/vendor/tipped.min.js"></script>
        <script src="assets/js/vendor/smooth-scroll.min.js"></script>
        <script src="assets/js/vendor/isInViewport.jquery.min.js"></script>
        <!--Calender Script -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
        <!-- Site Scripts -->
        <script src="assets/js/app.js"></script>

        <script>
            function myFunction() {
                var dots = document.getElementById("dots");
                var moreText = document.getElementById("more");
                var btnText = document.getElementById("myBtn");

                if (dots.style.display === "none") {
                    dots.style.display = "inline";
                    btnText.innerHTML = "Read more";
                    moreText.style.display = "none";
                } else {
                    dots.style.display = "none";
                    btnText.innerHTML = "Read less";
                    moreText.style.display = "inline";
                }
            }

            // Subscription cart functionality
            function addSubscriptionToCart(planType, planName, price, event) {
                // Prevent default action
                if (event) {
                    event.preventDefault();
                }

                // Get button element
                var button = event ? event.currentTarget : null;

                // Use global user ID
                var userId = userSessionId;

                // Show loading state
                if (button) {
                    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Adding...';
                    button.disabled = true;
                }

                // AJAX request to add subscription to cart
                $.ajax({
                    url: 'add_subscription_to_cart.php',
                    type: 'POST',
                    data: {
                        user_id: userId,
                        plan_type: planType,
                        plan_name: planName,
                        price: price
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            // Redirect to cart page immediately
                            window.location.href = 'cart.php';
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            if (button) {
                                button.innerHTML = 'Subscribe Now <i class="icon-4"></i>';
                                button.disabled = false;
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                        alert('An error occurred. Please try again.');
                        // Reset button
                        if (button) {
                            button.innerHTML = 'Subscribe Now <i class="icon-4"></i>';
                            button.disabled = false;
                        }
                    }
                });
            }
        </script>
    </body>


</html>