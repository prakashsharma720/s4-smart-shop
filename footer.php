<div class="wa-wrapper">
    <a href="https://wa.me/9116841049?text=Hi" class="wa-btn" target="_blank">
        <span class="wa-bg big"></span>
        <span class="wa-bg small"></span>
        <i class="bi bi-whatsapp"></i>
    </a>
   

<style>
.wa-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 60px;
    height: 60px;
    background: #25D366;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 32px;
    z-index: 10;
    box-shadow: 0 4px 18px rgba(37, 211, 102, 0.5);
    overflow: visible;
}

/* Big circle */
.wa-bg.big {
    position: absolute;
    width: 130px;
    height: 130px;
    background: rgba(37, 211, 102, 0.20);
    border-radius: 50%;
    top: -35px;
    left: -35px;
    z-index: -2;
    animation: zoomBig 2.5s infinite ease-in-out;
}

/* Small circle */
.wa-bg.small {
    position: absolute;
    width: 90px;
    height: 90px;
    background: rgba(37, 211, 102, 0.35);
    border-radius: 50%;
    top: -15px;
    left: -15px;
    z-index: -1;
    animation: zoomSmall 2.5s infinite ease-in-out;
}

@keyframes zoomBig {
    0%   { transform: scale(0.85); opacity: 0.6; }
    50%  { transform: scale(1.05); opacity: 0.3; }
    100% { transform: scale(0.85); opacity: 0.6; }
}

@keyframes zoomSmall {
    0%   { transform: scale(0.9); opacity: 0.7; }
    50%  { transform: scale(1.1); opacity: 0.4; }
    100% { transform: scale(0.9); opacity: 0.7; }
}

</style>

<footer>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <p>
                    <a href="index.php" title="Findoctor">
                        <img src="<?php echo $base_url; ?>img/s4smartshop.png" alt="" width="120" height="30" class="img-fluid">
                    </a>
                <div class="follow_us">
                    <h5>Follow us</h5>
                    <ul>
                        <li><a href="#0"><i class="social_facebook"></i></a></li>
                        <li><a href="#0"><i class="social_twitter"></i></a></li>
                        <li><a href="#0"><i class="social_linkedin"></i></a></li>
                        <li><a href="#0"><i class="social_instagram"></i></a></li>
                    </ul>
                </div>
                </p>
            </div>
            <div class="col-lg-3 col-md-4">
                <h5>About</h5>
                <ul class="links">
                    <li><a href="<?= $base_url ?>AboutUs.php">About us</a></li>
                    <li><a href="<?= $base_url ?>refund.php">Refund & Return/Exchange Policy</a></li>
                    <!-- <li><a href="FAQ.php">FAQ</a></li> -->
                    <li>
                        <a href="<?= $base_url ?>login.php">Login</a> / <a href="<?= $base_url ?>register.php">Register</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-4 text-center">
                <h5>Pay Here</h5>
                <img src="<?php echo $base_url;?>img/payment-qr.jpg" alt="Pay Here QR"
                    class="img-fluid rounded shadow mb-2" style="width:120px; height:auto;">
                <p><strong>Merchant:</strong> S4 SMART SHOP</p>
            </div>

            <div class="col-lg-3 col-md-4">
                <h5>Contact with Us</h5>
                <ul class="contacts">
                    <li>
                        <a href="tel:+612380933400">
                            <i class=" icon_mobile"></i> +91 9116841049
                        </a>
                    </li>
                    <li>
                        <a href="mailto:help@findoctor.com">
                            <i class="icon_mail_alt"></i> help@s4smartshop.com
                        </a>
                    </li>
                    <li>
                        <i class="icon_pin_alt"></i>
                        Nangpura,Near Government Upper Primary School, Dindoli Rashmi Chittogarh, Rajasthan 312203
                    </li>
                </ul>
            </div>

            <!--/row-->
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <ul id="additional_links">
                        <li><a href="Termsandcondition.php">Terms and conditions</a></li>
                        <li><a href="policy.php">Privacy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div id="copy">© 2025 S4 Smart Shop</div>
                </div>
            </div>
        </div>
</footer>
<!--/footer-->

<div id="toTop"></div>
<!-- Back to top button -->