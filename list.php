<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meesho Style Product Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f9f9f9; font-family: Arial, sans-serif; }
    .product-container { max-width: 1200px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 3px 12px rgba(0,0,0,0.1); }
    .carousel-inner img { border-radius: 8px; }
    .btn-buy { margin-top: 15px; }
    .info-box { padding: 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #eee; background: none; font-weight: 500; }
    .info-box h6 { font-weight: 600; margin-bottom: 10px; font-size: 14px; color: #000; }
    .size-option, .color-swatch { cursor: pointer; }
    .size-option { display: inline-block; padding: 5px 12px; border: 1px solid #ccc; margin-right: 10px; border-radius: 4px; }
    .size-option.selected { border: 2px solid red; font-weight: bold; }
    .color-swatch { display: inline-block; width: 25px; height: 25px; border-radius: 50%; margin-right: 10px; border: 1px solid #ccc; }
    .color-swatch.selected { border: 2px solid red; }
    .price { font-size: 1.6rem; font-weight: 500; color: #000; margin-bottom: 6px; }
    .price del { font-size: 0.9rem; color: #999; margin-right: 5px; }
    .offer { color: #03A66A; font-size: 14px; font-weight: 500; }
    .rating-wrap { display: flex; align-items: center; gap: 6px; font-size: 12px; margin-top: 6px; }
    .rating-box { display: inline-flex; align-items: center; gap: 2px; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #fff; background: #03A66A; }
    .rating-box .star { font-size: 12px; color: #fff; }
    .reviews { color: #999; font-size: 12px; }
    .free { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #fff; background: #999; }
    .product-details p, .product-details li { margin-bottom: 4px; color: #999; font-size: 15px; font-weight: 500; }
    .product-details ul { padding-left: 18px; margin-bottom: 6px; }
    .similar-colors { margin-top: 20px; }
    .similar-colors h6 { font-weight: 600; margin-bottom: 10px; font-size: 18px; color: #000; }
    .similar-swatch { display: inline-block; width: 40px; height: 40px; border-radius: 6px; margin-right: 10px; border: 1px solid #ccc; cursor: pointer; transition: all 0.2s ease; }
    .similar-swatch:hover, .similar-swatch.selected { border: 2px solid #ff4d4d; }
    .seller-box { padding: 15px; border: 1px solid #eee; border-radius: 6px; font-weight: 500; margin-top: 20px; }
    .seller-box h6 { font-weight: 600; margin-bottom: 10px; font-size: 18px; color: #000; }
    .seller-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .seller-profile { display: flex; align-items: center; gap: 10px; }
    .seller-icon { width: 40px; height: 40px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #666; }
    .seller-name { font-size: 18px; font-weight: 600; color: #333; }
    .view-shop-btn { font-size: 15px; color: #d32f2f; font-weight: 600; cursor: pointer; }
    .seller-stats { display: flex; flex-wrap: wrap; gap: 15px; font-size: 13px; color: #999; }
    .seller-stats div strong { display: block; font-size: 14px; color: #333; }
    .rating-review-box { border: 1px solid #eee; border-radius: 6px; background: #fff; margin-top: 20px; padding: 15px; }
    .rating-review-box h6 { font-weight: bold; font-size: 14px; color: #000; margin-bottom: 15px; }
    .rating-score { font-size: 36px; font-weight: bold; color: #000; }
    .rating-score .star { color: #03A66A; font-size: 22px; margin-left: 2px; }
    .rating-bar { display: flex; align-items: center; gap: 10px; font-size: 13px; margin-bottom: 6px; }
    .rating-bar span { width: 80px; color: #555; }
    .rating-bar .progress { flex: 1; height: 8px; background: #f0f0f0; border-radius: 4px; }
    .rating-bar .progress-bar { border-radius: 4px; }
    .rating-bar .count { width: 50px; text-align: right; color: #555; }
    .bg-orange { background-color: #ff9800 !important; }
  </style>
</head>
<body>

<div class="product-container">
  <div class="row">
    <!-- Left -->
    <div class="col-md-6">
      <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active"><img src="img/tshirtt.avif" class="d-block w-100" alt="Product 1"></div>
          <div class="carousel-item"><img src="img/tshirtt.avif" class="d-block w-100" alt="Product 2"></div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
      </div>
      <div class="d-flex gap-2 btn-buy">
        <button class="btn btn-danger flex-fill">Buy Now</button>
        <button class="btn btn-outline-danger flex-fill">Add to Cart</button>
      </div>
      <hr class="my-3">
      <div class="similar-colors">
        <h6>Available Colors</h6>
        <span class="similar-swatch selected" style="background: red;"></span>
        <span class="similar-swatch" style="background: blue;"></span>
        <span class="similar-swatch" style="background: green;"></span>
        <span class="similar-swatch" style="background: black;"></span>
      </div>
    </div>

    <!-- Right -->
    <div class="col-md-6">
      <div class="info-box">
        <h6>Men Casual T-shirt</h6>
        <div class="price"><span>₹699</span> <del>₹1,199</del> <span class="offer">42% OFF</span></div>
        <div class="rating-wrap">
          <span class="rating-box">3.8<span class="star">★</span></span>
          <span class="reviews">500 Ratings,</span>
          <span class="reviews">434 Reviews</span>
        </div>
        <div class="mt-2"><span class="free">Free Delivery</span></div>
      </div>

      <div class="info-box">
        <h6>Select Size</h6>
        <span class="size-option selected">S</span>
        <span class="size-option">M</span>
        <span class="size-option">L</span>
        <span class="size-option">XL</span>
      </div>

      <div class="info-box product-details">
        <h6>Product Details</h6>
        <p><strong>Name :</strong> Men Formal Shirts</p>
        <p><strong>Fabric :</strong> Cotton Blend</p>
        <p><strong>Sleeve Length :</strong> Long Sleeves</p>
        <p><strong>Pattern :</strong> Solid</p>
        <p><strong>Net Quantity (N) :</strong> 1</p>
        <h6>Sizes :</h6>
        <ul>
          <li>M (Chest 38 in, Length 28 in)</li>
          <li>L (Chest 40 in, Length 29 in)</li>
          <li>XL (Chest 42 in, Length 29 in)</li>
          <li>XXL (Chest 44 in, Length 30 in)</li>
        </ul>
        <p><strong>Package Contains :</strong> 1 Piece of Men Shirt</p>
        <p><strong>Country of Origin :</strong> India</p>
        <h6>More Information</h6>
        <p>This Men’s formal shirt is made from premium Cotton Blend fabric for comfort and durability. Perfect for office wear, parties, and casual outings.</p>
      </div>

      <!-- Seller -->
      <div class="seller-box">
        <h6>Sold By</h6>
        <div class="seller-header">
          <div class="seller-profile">
            <div class="seller-icon"><i class="bi bi-shop"></i></div>
            <span class="seller-name">ANITA ENTERPRISES 327</span>
          </div>
          <span class="view-shop-btn">View Shop</span>
        </div>
        <div class="seller-stats">
          <div><strong>3.9 ★</strong>102,930 Ratings</div>
          <div><strong>2,556</strong> Followers</div>
          <div><strong>55</strong> Products</div>
        </div>
      </div>

      <!-- Ratings & Reviews -->
      <div class="rating-review-box">
        <h6>Product Ratings & Reviews</h6>
        <div class="row">
          <div class="col-md-3 text-center border-end">
            <div class="rating-score">3.9<span class="star">★</span></div>
            <p class="mb-1 text-muted small">29,163 Ratings</p>
            <p class="mb-0 text-muted small">7,080 Reviews</p>
          </div>
          <div class="col-md-9">
            <div class="rating-bar"><span>Excellent</span><div class="progress"><div class="progress-bar bg-success" style="width:65%"></div></div><span class="count">14,222</span></div>
            <div class="rating-bar"><span>Very Good</span><div class="progress"><div class="progress-bar bg-success" style="width:25%"></div></div><span class="count">5,608</span></div>
            <div class="rating-bar"><span>Good</span><div class="progress"><div class="progress-bar bg-warning" style="width:18%"></div></div><span class="count">3,795</span></div>
            <div class="rating-bar"><span>Average</span><div class="progress"><div class="progress-bar bg-orange" style="width:12%"></div></div><span class="count">1,885</span></div>
            <div class="rating-bar"><span>Poor</span><div class="progress"><div class="progress-bar bg-danger" style="width:20%"></div></div><span class="count">3,653</span></div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.size-option').forEach(option => {
    option.addEventListener('click', () => {
      document.querySelectorAll('.size-option').forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');
    });
  });
  document.querySelectorAll('.similar-swatch').forEach(swatch => {
    swatch.addEventListener('click', () => {
      document.querySelectorAll('.similar-swatch').forEach(s => s.classList.remove('selected'));
      swatch.classList.add('selected');
    });
  });
</script>
</body>
</html>
