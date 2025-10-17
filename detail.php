<?php
include('config.php');

// ================== START SESSION ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================== GET LOGGED-IN USER ==================
$user = $_SESSION['user'] ?? null;

// ================== GET PRODUCT BY SLUG ==================
$product_slug = trim($_SERVER['PATH_INFO'] ?? '', '/');
if (!$product_slug) die("No product specified.");

$stmt = $conn->prepare("
    SELECT p.*, c.name AS category_name, c.slug AS category_slug
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ? LIMIT 1
");
$stmt->bind_param("s", $product_slug);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Product not found.");
$product = $result->fetch_assoc();

// Handle multiple images
$product_images = !empty($product['image']) ? array_map('trim', explode(',', $product['image'])) : ['default.png'];

// Handle multiple sizes
$product_sizes = [];
if (!empty($product['sizes'])) {
    $product_sizes = array_filter(array_map('trim', explode(',', $product['sizes'])));
}
$has_sizes = !empty($product_sizes);

// ================== HANDLE ORDER ==================
if ($user && isset($_POST['place_order'])) {
    $product_id = intval($_POST['product_id']);
    $name       = trim($_POST['customer_name']);
    $address    = trim($_POST['customer_address']);
    $email      = trim($_POST['customer_email']);
    $phone      = trim($_POST['customer_phone']);
    $size       = $_POST['size'] ?? '';
    $quantity   = intval($_POST['quantity'] ?? 1);
    $total      = floatval($_POST['total'] ?? 0);
    $referral   = trim($_POST['referral_code'] ?? '');

    $stmt2 = $conn->prepare("
        INSERT INTO orders (product_id, name, address, email, phone, size, quantity, total, referral_code, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt2->bind_param("isssssids", $product_id, $name, $address, $email, $phone, $size, $quantity, $total, $referral);

    if ($stmt2->execute()) {
        $order_id = $stmt2->insert_id;

        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalBody = document.querySelector('#buyNowModal .modal-body');
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class='text-center p-4'>
                        <h4 class='text-success mb-2'><i class=\"bi bi-check-circle-fill\"></i> Order placed successfully!</h4>
                        <p>Redirecting to Thank You page...</p>
                    </div>
                `;
            }
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('buyNowModal'));
            modal.show();
            setTimeout(() => {
                window.location.href = '{$base_url}thankyou.php?product_id={$product_id}&order_id={$order_id}';
            }, 2000);
        });
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
<title><?= htmlspecialchars($product['name']) ?> - Taste of Namkeenn</title>
<?php include('head.php'); ?>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <div class="product-container">

        <!-- Left: Images -->
        <div class="product-images">
            <!-- <div class="thumbnails mb-2">
                <?php foreach($product_images as $i => $img): ?>
                <img src="<?= $base_url ?>back/uploads/<?= htmlspecialchars($img) ?>"
                    class="<?= $i === 0 ? 'active' : '' ?>"
                    onclick="changeImage(this)"
                    alt="<?= htmlspecialchars($product['name']) ?>">
                <?php endforeach; ?>
            </div> -->
            <div class="main-image">
                <img id="productImage"
                    src="<?= $base_url ?>back/uploads/<?= htmlspecialchars($product_images[0]) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>

        <!-- Right: Details -->
        <div class="product-details position-relative">
            <div class="product-name" style="width:73%;">
                <h1 class="product-name mb-3"><?= htmlspecialchars($product['name']) ?></h1>
            </div>

            <!-- Share Icons -->
            <div class="dropdown share-icons-column position-absolute top-0 end-0 d-flex flex-column gap-2">
                <a href="#" class="btn btn-outline-danger" id="shareDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-share-fill"></i> 
                </a>
                <ul class="dropdown-menu" aria-labelledby="shareDropdown">
                    <li><a class="dropdown-item text-success" href="#" onclick="shareWhatsApp(); return false;"><i class="bi bi-whatsapp me-2"></i> WhatsApp</a></li>
                    <li><a class="dropdown-item text-primary" href="#" onclick="shareFacebook(); return false;"><i class="bi bi-facebook me-2"></i> Facebook</a></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="shareInstagram(); return false;"><i class="bi bi-instagram me-2"></i> Instagram</a></li>
                </ul>
            </div>

            <!-- Price -->
            <div class="price mb-2">Price: Rs. <span id="productPrice"><?= $product['price'] ?></span></div>

            <?php if($has_sizes): ?>
            <div class="size-section mb-2">
                <strong>Size:</strong>
                <div class="sizes d-flex gap-2 mt-1">
                    <?php foreach($product_sizes as $index => $size): ?>
                        <button type="button"
                            class="btn btn-outline-danger <?= $index === 0 ? 'active' : '' ?>"
                            data-size="<?= htmlspecialchars($size) ?>"
                            data-price="<?= $product['price'] ?>"
                            onclick="selectSize(this)">
                            <?= htmlspecialchars($size) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quantity -->
            <div class="quantity-section mb-2">
                <strong>Quantity:</strong>
                <div class="quantity">
                    <button type="button" id="decrease">-</button>
                    <input type="text" id="quantity" value="1" readonly>
                    <button type="button" id="increase">+</button>
                </div>
            </div>

            <!-- Total -->
            <div class="total-box">Total: Rs. <span id="totalAmount"><?= $product['price'] ?></span></div>

            <!-- Buy Now / Login -->
            <?php if($user): ?>
                <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#buyNowModal">Buy Now</button>
            <?php else: ?>
                <button class="btn btn-danger mt-3" onclick="redirectToLogin()">Buy Now</button>
            <?php endif; ?>

            <!-- Description -->
            <div class="description mt-3">
                <h3>Description:</h3>
                <div class="description-content"><?php echo $product['description'] ?></div>
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
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="orderForm">
                <div class="modal-body">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="size" id="formsize" value="<?= $has_sizes ? htmlspecialchars($product_sizes[0]) : '' ?>">
                    <input type="hidden" name="quantity" id="formQuantity" value="1">
                    <input type="hidden" name="total" id="formTotal" value="<?= $product['price'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Referral Code (Optional)</label>
                        <input type="text" name="referral_code" class="form-control" placeholder="Enter referral code" value="<?= htmlspecialchars($user['user_code'] ?? '') ?>">
                    </div>

                    <hr class="my-3">

                    <div class="mb-3"><label class="form-label">Full Name</label>
                    <input type="text" name="customer_name" class="form-control" value="<?php echo $user['name'];?>"></div>
                    <div class="mb-3"><label class="form-label">Email</label>
                    <input type="email" name="customer_email" class="form-control" value="<?php echo $user['email'];?>"></div>
                    <div class="mb-3"><label class="form-label">Mobile</label>
                    <input type="tel" name="customer_phone" class="form-control" required maxlength="10" pattern="[0-9]{10}" value="<?php echo $user['phone'];?>"></div>
                    <div class="mb-3"><label class="form-label">Shipping Address</label>(<i>Please enter full address</i>)
                    <textarea type="text" name="customer_address" class="form-control" required></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="place_order" class="btn btn-danger">Place Order</button>
                </div>
            </form>
        </div>
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
    window.open(`https://web.whatsapp.com/send?text=${encodeURIComponent(text)}`, '_blank');
}

function shareFacebook() {
    const text = `${productName} - ${getShareURL()}`;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareURL())}&quote=${encodeURIComponent(productName)}`, '_blank');
}

function shareInstagram() {
    const text = `${productName} - ${getShareURL()}`;
    navigator.clipboard.writeText(text).then(() => alert("Product link copied! Share on Instagram."));
}

// Image & Quantity JS
function changeImage(img){
    document.getElementById('productImage').src = img.src;
    document.querySelectorAll('.thumbnails img').forEach(i=>i.classList.remove('active'));
    img.classList.add('active');
}

function selectSize(btn){
    document.querySelectorAll('.sizes button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('formsize').value = btn.dataset.size;
    updateTotal();
}

document.getElementById('increase').addEventListener('click', ()=>updateQuantity(1));
document.getElementById('decrease').addEventListener('click', ()=>updateQuantity(-1));

function updateQuantity(val){
    let qtyInput = document.getElementById('quantity');
    let qty = Number(qtyInput.value)||1;
    qty += val;
    if(qty<1) qty=1;
    qtyInput.value = qty;
    updateTotal();
}

function updateTotal(){
    let activeSizeBtn = document.querySelector('.sizes button.active');
    let price = activeSizeBtn ? parseInt(activeSizeBtn.dataset.price) : <?= $product['price'] ?>;
    let qty = parseInt(document.getElementById('quantity').value);
    let total = price*qty;
    document.getElementById('totalAmount').innerText = total;
    document.getElementById('formQuantity').value = qty;
    document.getElementById('formTotal').value = total;
}

// 🔁 Redirect to login and come back
function redirectToLogin() {
    const currentURL = window.location.href;
    window.location.href = "<?= $base_url ?>login.php?redirect=" + encodeURIComponent(currentURL);
}
</script>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
