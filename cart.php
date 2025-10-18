<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Find easily a doctor and book online an appointment">
    <meta name="author" content="Ansonika">
    <title>S4 Smart Shop – Rajasthan’s Leading MLM-Based eCommerce Platform</title>
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
    <div id="breadcrumb">
            <div class="container">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li>Cart</li>
                </ul>
            </div>
        </div>
  <div class="container my-4">

    <div class="row g-3">
      
      <!-- LEFT: Cart -->
      <div class="col-md-8">
        <div class="card p-3 mb-3">
          <h6 class="fw-bold mb-3">From Saved Addresses 
            <button class="btn btn-sm btn-outline-secondary float-end">Enter Delivery Pincode</button>
          </h6>

          <!-- Product -->
          <div class="d-flex cart-item">
            <img src="img/tshirt2.webp" alt="Product" class="cart-img me-3">
            <div>
              <h6 class="fw-semibold">Men's Cotton Printed T-Shirt</h6>
              <p class="text-muted small mb-1">Size: M | Color: Black</p>
              <p class="text-muted small mb-1">Seller: FASHIONWORLD <span class="badge bg-primary">Assured</span></p>
              <p class="mb-1">
                <del class="text-muted">₹1,199</del>
                <span class="fw-bold fs-6 ms-2" id="productPrice">₹499</span>
                <span class="text-success small">58% Off</span>
              </p>
              <p class="small text-muted">Delivery by Tue Sep 2</p>
            </div>
          </div>
        </div>

      

        <!-- Quantity + Save Later + Remove -->
        <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
          <!-- Qty Control -->
          <div class="d-flex align-items-center">
            <button class="btn btn-light border qty-btn" id="decreaseQty">-</button>
            <input type="text" class="form-control text-center mx-2" style="width:50px;" id="quantity" value="1" readonly>
            <button class="btn btn-light border qty-btn" id="increaseQty">+</button>
          </div>
          <!-- Save & Remove -->
          <div class="d-flex gap-3">
            <a href="#" class="text-dark fw-semibold small">SAVE FOR LATER</a>
            <a href="#" class="text-dark fw-semibold small" id="removeItem">REMOVE</a>
          </div>
        </div>
      </div>

      <!-- RIGHT: Price Details -->
      <div class="col-md-4">
        <div class="card p-3" id="priceBox">
          <h6 class="fw-bold mb-3">PRICE DETAILS</h6>
          <div class="d-flex justify-content-between mb-2">
            <span>Price (<span id="itemCount">1</span> item)</span>
            <span id="totalPrice">₹1199</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Discount</span>
            <span class="text-success" id="discount">-₹700</span>
          </div>
          <div class="d-flex justify-content-between mb-2" id="warrantyRow" style="display:none;">
            <span>Warranty</span>
            <span id="warrantyPrice">₹29</span>
          </div>
          <hr>
          <div class="d-flex justify-content-between fw-bold mb-2">
            <span>Total Amount</span>
            <span id="finalAmount">₹499</span>
          </div>
          <p class="text-success small" id="savingText">You will save ₹700 on this order</p>
        </div>
        <button class="btn btn-warning w-100 fw-bold mt-3">PLACE ORDER</button>
      </div>

    </div>
  </div>


  <?php include('footer.php');?>
    <?php include('js.php');?>
</body>

</html>
