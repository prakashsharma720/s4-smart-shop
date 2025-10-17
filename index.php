<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Find easily a doctor and book online an appointment">
    <meta name="author" content="Ansonika">
    <title>S4 Smart Shop</title>
    <?php include('head.php');?>

</head>

<body>

    <div class="layer"></div>
    <!-- Mobile menu overlay mask -->

    <div id="preloader">
        <div data-loader="circle-side"></div>
    </div>
    <!-- End Preload -->


    <?php include('navbar.php');?>
    <main>
        <!-- ✅ Namkeen Carousel (Fun Namkeen Theme) -->
        <div id="namkeenCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">

                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="hero-overlay"></div>
                    <img src="img/banner2.jpg" class="d-block w-100 hero-img"
                        alt="Namkeen Pack 1">
                    <div class="carousel-caption text-center">
                        <!-- <div class="snack-icon">👗🕶️</div> -->
                        <h3 class="hero-subtitle">Trendy & Stylish</h3>
                         <h1 class="hero-title">Fabrics, Watches & Accessories</h1>
                        <a href="<?= $base_url ?>products.html" class="btn-hero">Shop Now</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="hero-overlay"></div>
                    <img src="img/banner3.jpg" class="d-block w-100 hero-img"
                        alt="Namkeen Pack 2">
                    <div class="carousel-caption text-center">
                        <div class="snack-icon">🥜</div>
                        <h3 class="hero-subtitle">Trendy & Stylish</h3>
                         <h1 class="hero-title">Fabrics, Watches & Accessories</h1>
                        <a href="<?= $base_url ?>products.html" class="btn-hero">Discover</a>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="hero-overlay"></div>
                    <img src="img/banner4.jpg" class="d-block w-100 hero-img"
                        alt="Namkeen Pack 3">
                    <div class="carousel-caption text-center">
                        <div class="snack-icon">🍲</div>
                        <h3 class="hero-subtitle">Trendy & Stylish</h3>
                         <h1 class="hero-title">Fabrics, Watches & Accessories</h1>
                        <a href="<?= $base_url ?>products.html" class="btn-hero">Explore More</a>
                    </div>
                </div>
            </div>

            <!-- Indicators -->
            <div class="carousel-indicators custom-indicators">
                <button type="button" data-bs-target="#namkeenCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#namkeenCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#namkeenCarousel" data-bs-slide-to="2"></button>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#namkeenCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#namkeenCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <!-- ✅ Marquee Section (After Carousel) -->
     <marquee behavior="scroll" direction="left" scrollamount="6" class="marquee-text">
    🛍️ Welcome to <b>S4 Smart Shop</b> | 💰 Shop Smart, Earn Smarter | 🌐 Join Our Fast-Growing Network | 🚀 Empowering Digital Entrepreneurs Across India | 🤝 Shopping That Rewards You!
    </marquee>


        <div class="namkeen-section">
           
         
         

            <section class=" mt-3 welcome-section ">
                <div class="container">
                    <div class="welcome-left slide-left">
                        <div class="circle">
                            <img src="<?= $base_url ?>img/s4smartshop.png" alt="Om Namkeen Logo" class="logo">
                            <img src="	https://www.omnamkeenahmd.com/wp-content/themes/twentytwentyone/assets/images/restant2.png"
                                class="snack snack-top" alt="Snack Top">
                            <img src="https://www.omnamkeenahmd.com/wp-content/themes/twentytwentyone/assets/images/restant3.png"
                                class="snack snack-right" alt="Snack Right">
                            <img src="https://www.omnamkeenahmd.com/wp-content/themes/twentytwentyone/assets/images/restant4.png"
                                class="snack snack-bottom" alt="Snack Bottom">
                            <img src="https://www.omnamkeenahmd.com/wp-content/themes/twentytwentyone/assets/images/restant5.png"
                                class="snack snack-left" alt="Snack Left">
                        </div>
                    </div>

                    <!-- Right Side Text -->
                 <div class="welcome-right slide-right">
                    <h1>Welcome to S4 Smart Shop</h1>
                    <p>
                        <strong>S4 Smart Shop</strong> is a leading <strong>MLM-based eCommerce platform</strong> founded by 
                        <strong>Mr. Mahendra Singh Rawar</strong> with a vision to redefine online shopping and income opportunities in India. 
                        We bring together innovation, transparency, and growth to help individuals shop smarter and earn more.
                    </p>
                    <p>
                        Our platform offers a wide range of high-quality products across multiple categories, 
                        along with a rewarding referral system that allows members to build a stable source of income. 
                        What started as a dream to empower entrepreneurs has today evolved into a fast-growing digital network 
                        connecting smart shoppers across the nation.
                    </p>
                    <a href="<?= $base_url ?>#" class="btn-read">Read More</a>
                </div>
            </section>
            <?php
              $conn = new mysqli("localhost", "root", "", "s4shopdb");
                if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                // Fetch all products from DB
                $product_sql = "SELECT * FROM products ORDER BY id ASC";
                $product_result = $conn->query($product_sql);

                // Prepare products array
                $products = [];
                if ($product_result && $product_result->num_rows > 0) {
                    while($row = $product_result->fetch_assoc()) {
                        $products[] = $row;
                    }
                }
                // Chunk products into groups of 3 per slide
                $slides = array_chunk($products, 3);
            ?>

            <div class="container margin_120_95">
                <div class="main_title">
                    <h2 class="mb-4">Our Products</h2>

                    <?php if(!empty($slides)): ?>
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">

                            <?php foreach($slides as $index => $slide): ?>
                            <div class="carousel-item <?php if($index === 0) echo 'active'; ?>">
                                <div class="row g-4 justify-content-center">
                                    <?php foreach($slide as $product): ?>
                                    <div class="col-md-4 col-sm-6">
                                        <a href="<?= $base_url ?>detail.php/<?php echo htmlspecialchars($product['slug']); ?>"
                                            class="text-decoration-none">
                                            <div class="product-card">
                                                <img src="<?= $base_url ?>back/uploads/<?php echo $product['feature-img']; ?>"
                                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                    class="img-fluid">
                                                <div class="product-info mt-2 text-center">
                                                    <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                                                    <div class="from-price-middle text-center mt-2">
                                                        Price:  ₹<?php echo $product['price']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>

                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <?php else: ?>
                    <p class="text-center">No products available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
      <div class="container my-5">
        <div class="main_title text-center mb-5">
            <h2>How It Works</h2>
            <p>Start your earning journey with S4 Smart Shop in 4 simple steps!</p>
        </div>

        <div class="row text-center process-grid">

            <!-- Step 1 -->
            <div class="col-lg-3 col-md-6 mb-4 process-step-wrapper">
            <div class="process-step">
                <img src="img/computer.jpg" alt="Register" class="process-img">
                <h4>Register</h4>
                <p>Create your free account and become a member of S4 Smart Shop.</p>
            </div>
            </div>

            <!-- Step 2 -->
            <div class="col-lg-3 col-md-6 mb-4 process-step-wrapper">
            <div class="process-step">
                <img src="img/bag.jpg" alt="Shop Products" class="process-img">
                <h4>Shop Products</h4>
                <p>Explore a wide range of products and shop your favorites with ease.</p>
            </div>
            </div>

            <!-- Step 3 -->
            <div class="col-lg-3 col-md-6 mb-4 process-step-wrapper">
            <div class="process-step">
                <img src="img/truck.jpg" alt="Refer & Earn" class="process-img">
                <h4>Refer & Earn</h4>
                <p>Invite others to join S4 Smart Shop and earn referral rewards instantly.</p>
            </div>
            </div>

            <!-- Step 4 -->
            <div class="col-lg-3 col-md-6 mb-4 process-step-wrapper">
            <div class="process-step">
                <img src="img/enjoy.jpg" alt="Grow & Earn" class="process-img">
                <h4>Grow & Earn</h4>
                <p>Build your network, increase your level, and enjoy lifelong income benefits.</p>
            </div>
            </div>

        </div>
    </div>

    <section class="why-choose-us py-5">
        <div class="container text-center">
            <h2 class="section-title mb-4">Why Choose Us</h2>
            <p class="section-subtitle mb-5">
            S4 Smart Shop offers a trusted platform that combines smart shopping, referral rewards, and financial growth opportunities for everyone.
            </p>
            <div class="row g-4">

            <!-- Card 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="choose-card animate-left">
                <div class="icon-box"><i class="fas fa-shopping-cart"></i></div>
                <h4>Smart Shopping</h4>
                <p>Shop from a wide range of quality products at unbeatable prices.</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="choose-card animate-up">
                <div class="icon-box"><i class="fas fa-hand-holding-usd"></i></div>
                <h4>Earn While You Shop</h4>
                <p>Get rewarded for every purchase and referral through our MLM system.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="choose-card animate-down">
                <div class="icon-box"><i class="fas fa-users"></i></div>
                <h4>Strong Network</h4>
                <p>Build your own network and grow together with a supportive community.</p>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="choose-card animate-right">
                <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                <h4>Unlimited Growth</h4>
                <p>Enjoy consistent income and growth opportunities with full transparency.</p>
                </div>
            </div>

            </div>
        </div>
    </section>

        

        <section class="reviews-section py-5">
            <div class="container">
                <div class="row align-items-center">

                    <!-- Left Column: Feedback Intro -->
                    <div class="col-lg-4 mb-4">
                        <div class="p-4 rounded shadow-sm text-dark" style="background:#fff3e0;">
                            <h2 class="mb-3 text-danger fw-bold">Why Choose S4 Smart Shop</h2>
                            <p>
                                S4 Smart Shop brings you <strong>authentic taste, unmatched quality</strong>, 
                                and the joy of snacking since 2002.
                            </p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2">🌟 100% Fresh & Hygienic Products</li>
                                <li class="mb-2">🌟 Traditional Recipes with Authentic Flavor</li>
                                <li class="mb-2">🌟 Loved & Trusted by Families Across India</li>
                            </ul>
                        </div>
                    </div>


                    <!-- Right Column: Reviews Carousel -->
                    <div class="col-lg-8">
                        <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel"
                            data-bs-interval="3500">
                            <div class="carousel-inner">

                                <!-- Slide 1 -->
                                <div class="carousel-item active">
                                  <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="about-review p-3 border-0 rounded shadow bg-white h-100">
                                            <div class="rating mb-2 text-warning fs-5">★★★★★</div>
                                            <p>"Absolutely love the fabrics! Premium quality and trendy designs every time."</p>

                                            <div class="user_review d-flex align-items-center mt-3">
                                                <img src="slides/Screenshot 2025-08-12 144358.png" class="rounded-circle me-2" width="50" alt="User">
                                                <div>
                                                    <strong>Dr. Joseph Luiss</strong><br><small>Doctor</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="about-review p-3 border-0 rounded shadow bg-white h-100">
                                            <div class="rating mb-2 text-warning fs-5">★★★★★</div>
                                            <p>"The accessories collection is amazing! Stylish watches and belts that complete any outfit."</p>

                                            <div class="user_review d-flex align-items-center mt-3">
                                                <img src="slides/Screenshot 2025-08-12 145321.png" class="rounded-circle me-2" width="50" alt="User">
                                                <div>
                                                    <strong>Pablo Jemenez</strong><br><small>Food Blogger</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                </div>

                                <!-- Slide 2 -->
                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="about-review p-3 border-0 rounded shadow bg-white h-100">
                                                <div class="rating mb-2 text-warning fs-5">★★★★★</div>
                                                <p>"Every festival feels incomplete without their Namkeen mixes!"</p>
                                                <div class="user_review d-flex align-items-center mt-3">
                                                    <img src="slides/Screenshot 2025-08-12 145321.png"
                                                        class="rounded-circle me-2" width="50" alt="User">
                                                    <div>
                                                        <strong>Marc Twain</strong><br><small>Customer</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="about-review p-3 border-0 rounded shadow bg-white h-100">
                                                <div class="rating mb-2 text-warning fs-5">★★★★★</div>
                                                <p>"Quality is top-notch. Even my kids love the Aloo Bhujia!"</p>
                                                <div class="user_review d-flex align-items-center mt-3">
                                                    <img src="slides/Screenshot 2025-08-12 144416.png"
                                                        class="rounded-circle me-2" width="50" alt="User">
                                                    <div>
                                                        <strong>Dr. Julia Roberts</strong><br><small>Teacher</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Carousel Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

       
        <!-- /app_section -->
    </main>
    <!-- /main content -->
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const animated = document.querySelectorAll('.slide-left, .slide-right');

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        animated.forEach(el => observer.observe(el));
    });

        window.addEventListener("scroll", () => {
            document.querySelectorAll(".choose-card").forEach((card, i) => {
                const rect = card.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    // Add small delay for sequential effect
                    setTimeout(() => {
                        card.classList.add("show");
                    }, i * 150);
                }
            });
        });
        </script>
    <?php include('footer.php');?>
    <?php include('js.php');?>
</body>

</html>