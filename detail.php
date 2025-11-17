<?php
include('config.php');

// ================== START SESSION ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$referal_code = $_GET['ref'] ?? '';
$user = $_SESSION['user'] ?? null;
$user_uniquecode = $_SESSION['user']['user_code'] ?? '';

// ================== GET PRODUCT BY SLUG ==================
$product_slug = trim($_SERVER['PATH_INFO'] ?? '', '/');
if (!$product_slug) die("No product specified.");

$stmt = $conn->prepare("
    SELECT p.*, c.category_name AS category_name, c.slug AS category_slug
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ? LIMIT 1
");
$stmt->bind_param("s", $product_slug);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Product not found.");
$product = $result->fetch_assoc();

// Handle multiple sizes
$product_sizes = [];
if (!empty($product['sizes'])) {
    $product_sizes = array_filter(array_map('trim', explode(',', $product['sizes'])));
}
$has_sizes = !empty($product_sizes);

// ================== HANDLE ORDER ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

    $product_id     = intval($_POST['product_id']);
    $quantity       = intval($_POST['quantity']);
    $total          = floatval($_POST['total']);
    $size           = $_POST['size'] ?? '';
    $referral_code  = trim($_POST['referral_code']);
    $referal_remark = trim($_POST['referal_remark']);

    $address1 = trim($_POST['address1'] ?? '');
    $address2 = trim($_POST['address2'] ?? '');
    $city     = trim($_POST['city'] ?? '');
    $state    = trim($_POST['state'] ?? '');
    $pincode  = trim($_POST['pincode'] ?? '');

    $name   = trim($_POST['customer_name']);
    $email  = trim($_POST['customer_email']);
    $phone  = trim($_POST['customer_phone']);

    $stmt2 = $conn->prepare("
        INSERT INTO orders (
            user_code, name, email, phone, product_id, size, quantity, total,
            referral_code, referal_remark, address1, address2, city, state, pincode
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt2->bind_param(
        "sssisisdsssssss",
        $user_uniquecode,
        $name,
        $email,
        $phone,
        $product_id,
        $size,
        $quantity,
        $total,
        $referral_code,
        $referal_remark,
        $address1,
        $address2,
        $city,
        $state,
        $pincode
    );

    if ($stmt2->execute()) {
        $_SESSION['last_order_id'] = $stmt2->insert_id;
        $_SESSION['last_product_id'] = $product_id;

        echo "<script>
            localStorage.setItem('redirect_checkout', '1');
            window.location.href = window.location.href; // reload same page to show loader
        </script>";
        exit;
    } else {
        echo "<script>alert('Failed to place order: " . $stmt2->error . "');</script>";
    }
    $stmt2->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($product['name']) ?> - S4 Smart Shop</title>
<?php include('head.php'); ?>
</head>
<body>
<?php include('navbar.php'); ?>

<div id="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li>Detail</li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="product-container">

        <!-- Left: Image -->
        <div class="product-images">
            <div class="main-image">
                <img id="productImage" src="<?= $base_url ?>back/uploads/<?= htmlspecialchars($product['feature_img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>

           <div class="product-details position-relative">
            <div class="product-name" style="width:73%;">
            <h1 class="product-name mb-3"><?= htmlspecialchars($product['name']) ?></h1>

            </div>

            <!-- Share Icons -->
            <div class="dropdown share-icons-column position-absolute top-0 end-0 d-flex flex-column gap-2">
                <a href="#" class="btn btn-outline-" id="shareDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-share-fill"></i> Share
                </a>
                <ul class="dropdown-menu" aria-labelledby="shareDropdown">
                    <li>
                        <a class="dropdown-item text-success" href="#" onclick="shareWhatsApp(); return false;">
                            <i class="bi bi-whatsapp me-2"></i> WhatsApp
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-primary" href="#" onclick="shareFacebook(); return false;">
                            <i class="bi bi-facebook me-2"></i> Facebook
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="shareInstagram(); return false;">
                            <i class="bi bi-instagram me-2"></i> Instagram
                        </a>
                    </li>
                </ul>
            </div>
            <div class="total-box mb-2">Price: ₹ <span id="productPrice"><?= $product['price'] ?></span></div>

            <?php if($has_sizes): ?>
            <div class="size-section mb-2">
                <strong>Size:</strong>
                <div class="sizes d-flex gap-2 mt-1">
                    <?php foreach($product_sizes as $index => $size): ?>
                        <button type="button"
                            class="btn btn-outline-danger <?= $index === 0 ? 'active' : '' ?> "
                            data-size="<?= htmlspecialchars($size) ?>"
                            onclick="selectSize(this)">
                            <?= htmlspecialchars($size) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="quantity-section mb-2">
                <strong>Quantity:</strong>
                <div class="quantity">
                    <button type="button" id="decrease">-</button>
                    <input type="text" id="quantity" value="1" readonly>
                    <button type="button" id="increase">+</button>
                </div>
            </div>

            <div class="price total-box">Order Total: ₹ <span id="totalAmount" style="color: green;"><?= $product['price'] ?></span></div>

         <?php if($user): ?>
    <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#buyNowModal">
        Order Now
    </button>
<?php else: ?>
    <button class="btn btn-danger mt-3" onclick="redirectToLogin()">
        Buy Now
    </button>
<?php endif; ?>


            <div class="description mt-3">
                <h3>Description:</h3>
                <div class="description-content"><?= $product['description'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Buy Now Modal -->
<?php if($user): ?>
<div class="modal fade" id="buyNowModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header custom-header">
        <h5 class="modal-title">Shipping Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
     <form method="POST">
  <div class="modal-body">

    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <input type="hidden" name="size" id="formsize" value="<?= $has_sizes ? htmlspecialchars($product_sizes[0]) : '' ?>">
    <input type="hidden" name="quantity" id="formQuantity" value="1">
    <input type="hidden" name="total" id="formTotal" value="<?= $product['price'] ?>">

    <input type="hidden" name="customer_name" value="<?= htmlspecialchars($user['name']); ?>">
    <input type="hidden" name="customer_email" value="<?= htmlspecialchars($user['email']); ?>">
    <input type="hidden" name="customer_phone" value="<?= htmlspecialchars($user['phone']); ?>">

    <div class="mb-3">
        <label class="form-label">Referral Code (Optional)</label>
        <input type="text" name="referral_code" class="form-control"
               value="<?= htmlspecialchars($referal_code ?? $user['user_code']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Referral Remark (Optional)</label>
        <textarea name="referal_remark" class="form-control" rows="2"
                  placeholder="Enter any note or instruction (optional)"></textarea>
    </div>

    <hr class="my-3">
    <h6 class="text-danger mb-2">Shipping Address</h6>

    <div class="mb-3">
      <label class="form-label">Address Line 1</label>
      <input type="text" name="address1" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Address Line 2</label>
      <input type="text" name="address2" class="form-control">
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">State</label>
        <select name="state" class="form-select" required>
          <option value="">Select State</option>
          <option>Rajasthan</option>
          <option>Madhya Pradesh</option>
          <option>Gujarat</option>
          <option>Maharashtra</option>
          <option>Delhi</option>
          <option>Haryana</option>
          <option>Punjab</option>
          <option>Uttar Pradesh</option>
          <option>Bihar</option>
          <option>West Bengal</option>
          <option>Goa</option>
          <option>Karnataka</option>
          <option>Tamil Nadu</option>
          <option>Kerala</option>
          <option>Telangana</option>
          <option>Andhra Pradesh</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Pincode</label>
      <input type="text" name="pincode" class="form-control" maxlength="6"
             pattern="[0-9]{6}" required>
    </div>

  </div>

  <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <button type="submit" name="place_order" class="btn btn-danger">Place Order</button>
  </div>
</form>

    </div>
  </div>
</div>



<div id="loader-overlay">
  <div class="loader-box text-center">
      <img src="https://s4smartshop.com/img/s4smartshop.png" alt="S4 Smart Shop Logo" class="mb-3" style="width:80px;height:auto;">
      <div class="spinner-border" role="status" style="width:4rem;height:4rem;color:#113d56;"></div>
      <div id="loader-text">Processing your order, please wait...</div>
  </div>
</div>
<?php endif; ?>

<script>
    const productURL = "<?= $base_url ?>detail.php/<?= $product['slug'] ?>";
const productName = "<?= addslashes($product['name']) ?>";
const userCode = "<?= $user['user_code'] ?? '' ?>";

function getShareURL() {
    let url = productURL;
    if(userCode) url += '?ref=' + userCode;
    return url;
}

function shareWhatsApp() {
    const text = `${productName} - ${getShareURL()}`;
window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`, '_blank');
}

function shareFacebook() {
    const text = `${productName} - ${getShareURL()}`;
window.open(
  "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(getShareURL()),
  "_blank"
);
}

function shareInstagram() {
    const text = `${productName} - ${getShareURL()}`;
    navigator.clipboard.writeText(text).then(() => alert("Product link copied! Share on Instagram."));
}

document.getElementById('increase').onclick = ()=>updateQuantity(1);
document.getElementById('decrease').onclick = ()=>updateQuantity(-1);

function updateQuantity(val){
    let qty = Number(document.getElementById('quantity').value) + val;
    if(qty < 1) qty = 1;
    document.getElementById('quantity').value = qty;
    let price = <?= $product['price'] ?>;
    document.getElementById('totalAmount').innerText = price * qty;
    document.getElementById('formQuantity').value = qty;
    document.getElementById('formTotal').value = price * qty;
}

function selectSize(btn){
    document.querySelectorAll('.sizes button').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('formsize').value = btn.dataset.size;
}

function redirectToLogin(){
    window.location.href = "<?= $base_url ?>login.php?redirect=" + encodeURIComponent(window.location.href);
}

document.addEventListener('DOMContentLoaded', function(){
  const loader = document.getElementById('loader-overlay');
  const loaderText = document.getElementById('loader-text');
  const form = document.querySelector('#buyNowModal form');

  // ✅ When placing order
  if(form){
    form.addEventListener('submit', function(){
      loader.style.display = 'block';
      loaderText.textContent = "Placing your order, please wait...";
    });
  }

  // ✅ After reload → redirect to checkout
  if(localStorage.getItem('redirect_checkout') === '1'){
    loader.style.display = 'block';
    loaderText.textContent = "Redirecting to checkout, please wait...";
    localStorage.removeItem('redirect_checkout');

    setTimeout(()=>{
      window.location.href = "<?= $base_url ?>checkout.php";
    }, 2000);
  }
});
</script>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
