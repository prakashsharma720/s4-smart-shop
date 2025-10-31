<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="S4 Smart Shop">
    <meta name="author" content="prakash">
    <title>S4 Smart Shop</title>
    <?php include("head.php");?>

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
                    <img src="img/banner2.jpg" class="d-block w-100 hero-img" alt="Namkeen Pack 1">
                    <div class="carousel-caption text-center">
                        <!-- <div class="snack-icon"></div> -->
                        <h3 class="hero-subtitle">Trendy & Stylish</h3>
                        <h1 class="hero-title">Fabrics, Watches & Accessories</h1>
                        <!--<a href="<?= $base_url ?>products.html" class="btn-hero">Shop Now</a>-->
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="hero-overlay"></div>
                    <img src="img/banner3.jpg" class="d-block w-100 hero-img" alt="Namkeen Pack 2">
                    <div class="carousel-caption text-center">
                        <div class="snack-icon"></div>
                        <h3 class="hero-subtitle">Trendy & Stylish</h3>
                        <h1 class="hero-title">Fabrics, Watches & Accessories</h1>
                        <!--<a href="<?= $base_url ?>products.html" class="btn-hero">Discover</a>-->
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="hero-overlay"></div>
                    <img src="img/banner4.jpg" class="d-block w-100 hero-img" alt="Namkeen Pack 3">
                    <div class="carousel-caption text-center">
                        <div class="snack-icon"></div>
                        <h3 class="hero-subtitle">Trendy & Stylish</h3>
                        <h1 class="hero-title">Fabrics, Watches & Accessories</h1>
                        <!--<a href="<?= $base_url ?>products.html" class="btn-hero">Explore More</a>-->
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
            🛍️ Welcome to <b>S4 Smart Shop</b> | 💰 Shop More, Save More🚀| 🤝 Shopping That Rewards You!
        </marquee>


        <div class="namkeen-section">




            <section class=" mt-3 welcome-section ">
                <div class="container">
                    <div class="welcome-left slide-left">
                        <div class="circle">
                            <img src="<?= $base_url ?>img/s4smartshop.png" alt="Om Namkeen Logo" class="logo">
                            <img src="	img/belt.png" class="snack snack-top" alt="Snack Top">
                            <img src="img/online-shopping.png" class="snack snack-right" alt="Snack Right">
                            <img src="img/sunglasses.png" class="snack snack-bottom" alt="Snack Bottom">
                            <img src="img/fabric-pattern.png" class="snack snack-left" alt="Snack Left">
                        </div>
                    </div>

                    <!-- Right Side Text -->
                    <div class="welcome-right slide-right">
                        <h1>Welcome to S4 Smart Shop</h1>
                        <p>
                            <strong>S4 Smart Shop</strong> is a trusted online shopping destination offering premium-quality 
                            <strong>fabrics, watches, goggles, hankies, mufflers, and more</strong>. We bring together 
                            style, comfort, and quality to deliver products that suit your everyday lifestyle.
                        </p>
                        <p>
                            Founded by <strong>Mr. Mahendra Singh Rawat</strong>, S4 Smart Shop is built on a vision to provide 
                            an easy, reliable, and affordable online shopping experience for every customer. Our focus is on 
                            offering carefully selected products that combine fashion with functionality.
                        </p>
                        <p>
                            Discover new trends, shop confidently, and experience quality with <strong>S4 Smart Shop</strong> — 
                            your one-stop destination for smart and stylish essentials.
                        </p>
                        <!--<a href="<?= $base_url ?>about-us.php" class="btn-read">Read More</a>-->
                    </div>

            </section>
            <?php
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

           <?php
// ================== Fetch Products ==================
$product_sql = "SELECT * FROM products ORDER BY id ASC";
$product_result = $conn->query($product_sql);

$products = [];
if ($product_result && $product_result->num_rows > 0) {
    while ($row = $product_result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<div class="container margin_120_95">
    <div class="main_title text-center mb-4">
        <h2>Our Products</h2>
    </div>

    <?php if (!empty($products)): ?>
    <div class="product-carousel-wrapper position-relative">
        <!-- Left Arrow -->
        <button class="scroll-btn left" id="scrollLeft">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <!-- Product Scroll Container -->
        <div class="product-scroll d-flex overflow-auto" id="productScroll">
            <?php foreach ($products as $product): ?>
            <div class="product-item flex-shrink-0">
                <a href="<?= $base_url ?>detail.php/<?php echo htmlspecialchars($product['slug']); ?>"
                    class="text-decoration-none">
                    <div class="product-card text-center shadow-sm p-3 rounded h-100">
                        <img src="<?= $base_url ?>back/uploads/<?php echo $product['feature_img']; ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid rounded">
                        <div class="product-info mt-3">
                            <h5 class="fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <div class="from-price-middle mt-2 text-muted">
                                Price: ₹<?php echo $product['price']; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Right Arrow -->
        <button class="scroll-btn right" id="scrollRight">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    <?php else: ?>
    <p class="text-center">No products available.</p>
    <?php endif; ?>
</div>
     <div class="container my-5">
            <div class="main_title text-center mb-5">
                <h2>How It Works</h2>
                <p>Start your earning journey with S4 Smart Shop in 2 simple steps!</p>
            </div>

            <div class="row text-center process-grid">

                <!-- Step 1 -->
                <div class="col-lg-6 col-md-6 mb-4 process-step-wrapper">
                    <div class="process-step">
                        <img src="img/computer.jpg" alt="Register" class="process-img">
                        <h4>Register</h4>
                        <p>Create your free account and become a member of S4 Smart Shop.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-lg-6 col-md-6 mb-4 process-step-wrapper">
                    <div class="process-step">
                        <img src="img/bag.jpg" alt="Shop Products" class="process-img">
                        <h4>Shop Products</h4>
                        <p>Explore a wide range of products and shop your favorites with ease.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <!-- <div class="col-lg-4 col-md-6 mb-4 process-step-wrapper">
                    <div class="process-step">
                        <img src="img/referral.png" alt="Refer & Earn" class="process-img">
                        <h4>Refer & Earn</h4>
                        <p>Invite others to S4 Smart Shop and earn referral rewards instantly.</p>
                    </div>
                </div> -->

                <!-- Step 4 -->
                <!--<div class="col-lg-3 col-md-6 mb-4 process-step-wrapper">-->
                <!--    <div class="process-step">-->
                <!--        <img src="img/investment.png" alt="Grow & Earn" class="process-img">-->
                <!--        <h4>Grow & Earn</h4>-->
                <!--        <p>Build your network, increase your level, and enjoy lifelong income benefits.</p>-->
                <!--    </div>-->
                <!--</div>-->

            </div>
        </div>

       



        <section class="reviews-section py-1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="row g-4">

                        <!-- Left Column: Why Choose S4 Smart Shop -->
                        <div class="col-lg-12 mb-4">
                            <div class="p-4 rounded shadow-sm text-dark" style="background:#deedf7d6;">
                                <h3 class="mb-3 brand-name" style="font-size:1.5rem;">Why Choose S4 Smart Shop</h3>
                                <p>
                                    S4 Smart Shop brings you <strong>premium quality men’s clothing and
                                        accessories</strong>,
                                    crafted for style and comfort. Shop the latest trends and elevate your wardrobe
                                    effortlessly.
                                </p>
                                <ul class="list-unstyled mt-3">
                                    <li class="mb-2">🌟 Stylish & Trendy Apparel</li>
                                    <li class="mb-2">🌟 Premium Quality Accessories</li>
                                    <li class="mb-2">🌟 Trusted by Fashion Enthusiasts</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Right Column: Reviews Carousel -->
                       

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
  <script>
// ======= Scroll Arrows + Auto Scroll =======
const scrollContainer = document.getElementById('productScroll');
const leftBtn = document.getElementById('scrollLeft');
const rightBtn = document.getElementById('scrollRight');

let autoScrollActive = true;
let scrollSpeed = 1.2; // speed in pixels/frame

// Manual Scroll Buttons
leftBtn.addEventListener('click', () => {
  scrollContainer.scrollBy({ left: -300, behavior: 'smooth' });
});
rightBtn.addEventListener('click', () => {
  scrollContainer.scrollBy({ left: 300, behavior: 'smooth' });
});

// Auto Scroll Function
function autoScroll() {
  if (!autoScrollActive) return;

  scrollContainer.scrollBy({ left: scrollSpeed, behavior: 'smooth' });

  // Reset when end reached
  if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth - 2) {
    scrollContainer.scrollTo({ left: 0, behavior: 'auto' });
  }

  requestAnimationFrame(autoScroll);
}

// Pause auto-scroll when user interacts
scrollContainer.addEventListener('mouseenter', () => (autoScrollActive = false));
scrollContainer.addEventListener('mouseleave', () => {
  if (!autoScrollActive) {
    autoScrollActive = true;
    requestAnimationFrame(autoScroll);
  }
});

// Also pause when clicking arrows (resume after short delay)
[leftBtn, rightBtn].forEach(btn => {
  btn.addEventListener('click', () => {
    autoScrollActive = false;
    setTimeout(() => {
      autoScrollActive = true;
      requestAnimationFrame(autoScroll);
    }, 3000);
  });
});

// Start Auto Scroll
window.addEventListener('load', () => {
  setTimeout(() => requestAnimationFrame(autoScroll), 1500);
});
</script>

    <?php include('footer.php');?>
    <?php include('js.php');?>
</body>

</html>