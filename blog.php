<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Process || Shop, Earn & Grow with S4 Smart Shop</title>
    <?php include('head.php'); ?>
</head>
<body>
<div class="layer"></div>
<div id="preloader"><div data-loader="circle-side"></div></div>

<?php include('navbar.php'); ?>
<div class="container my-5">
    <div class="main_title text-center mb-5">
        <h2>Process</h2>
    </div>

    <div class="row process-grid align-items-center mb-5">
        <!-- Step 1: Image Left, Text Right -->
        <div class="col-lg-4 wow animate__fadeInleft">
            <img src="img/computer.jpg" class="img-fluid rounded shadow" alt="Register">
        </div>
           <div class="col-lg-8 wow animate__fadeInRight">
    <h4>Register</h4>
    <p>Start by creating your free account and joining S4 Smart Shop. Fill in your basic details, set up your profile to personalize your experience, and verify your email or mobile number for secure access. Once registered, you’re all set to explore products and begin your earning journey!</p>
</div>

    </div>

    <div class="row process-grid align-items-center mb-5">
        <!-- Step 2: Image Right, Text Left -->
        <div class="col-lg-4 order-lg-2 wow animate__fadeInRight">
            <img src="img/bag.jpg" class="img-fluid rounded shadow" alt="Shop Products">
        </div>
       <div class="col-lg-8 wow animate__fadeInRight">
    <h4>Shop Products</h4>
    <p>Explore a wide range of products and shop your favorites with ease. Browse through different categories, compare prices, and discover top deals. Add items to your cart effortlessly and enjoy a seamless shopping experience from anywhere, anytime.</p>
</div>

    </div>

    <div class="row process-grid align-items-center mb-5">
        <!-- Step 3: Image Left, Text Right -->
        <div class="col-lg-4 wow animate__fadeInLeft">
            <img src="img/referral.png" class="img-fluid rounded shadow" alt="Refer & Earn">
        </div>
       <div class="col-lg-8 wow animate__fadeInRight">
    <h4>Refer & Earn</h4>
    <p>Invite your friends and family to join S4 Smart Shop and earn referral rewards instantly. Share your unique referral link through social media, email, or WhatsApp, and track your rewards easily in your dashboard. The more people you refer, the more you earn, making it simple to boost your income while helping others discover great products.</p>
</div>

    </div>

    <div class="row process-grid align-items-center mb-5">
        <!-- Step 4: Image Right, Text Left -->
        <div class="col-lg-4 order-lg-2 wow animate__fadeInRight">
            <img src="img/investment.png" class="img-fluid rounded shadow" alt="Grow & Earn">
        </div>
       <div class="col-lg-8 order-lg-1 wow animate__fadeInLeft">
    <h4>Grow & Earn</h4>
    <p>Build your network, increase your level, and enjoy lifelong income benefits. Expand your reach by inviting more members, share product recommendations, and earn higher rewards as your network grows. Track your progress and commissions easily, and watch your earnings increase steadily over time while helping others succeed.</p>
</div>

    </div>
</div>

<!-- Animate.css & WOW.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
  new WOW().init();
</script>

<style>
.process-grid h4 { font-weight: 600; margin-bottom: 10px; }
.process-grid p { font-size: 16px; line-height: 1.6; }
.process-grid img { max-width: 100%; height: auto; }
@media(max-width:991px){
    .process-grid .order-lg-1, .process-grid .order-lg-2 { order: unset !important; }
}
.process-grid img {
    width: 200px;   /* fixed width */
    height: 200px;  /* fixed height */
    object-fit: contain; /* maintain aspect ratio */
}

</style>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>

</html>
