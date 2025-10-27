<?php
include('config.php');
$referal_code ='';

// ================== START SESSION ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$referal_code = $_GET['ref'] ?? '';

// ================== GET LOGGED-IN USER ==================
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

$product_images = !empty($product['image']) ? array_map('trim', explode(',', $product['image'])) : ['default.png'];
$product_sizes = [];
if (!empty($product['sizes'])) {
    $product_sizes = array_filter(array_map('trim', explode(',', $product['sizes'])));
}
$has_sizes = !empty($product_sizes);

// ================== AUTO PLACE ORDER ==================
if ($user && isset($_POST['buy_now'])) {
    $product_id = intval($_POST['product_id']);
    $name       = $user['name'];
    $address    = $user['address'] ?? 'N/A';
    $email      = $user['email'];
    $phone      = $user['phone'];
    $size       = $_POST['size'] ?? '';
    $quantity   = intval($_POST['quantity'] ?? 1);
    $total      = floatval($_POST['total'] ?? 0);
    $referral   = trim($referal_code ?: $user['user_code']);

    $stmt2 = $conn->prepare("
        INSERT INTO orders (user_code, product_id, name, address, email, phone, size, quantity, total, referral_code, created_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,NOW())
    ");
    $stmt2->bind_param("sisssssids", $user_uniquecode, $product_id, $name, $address, $email, $phone, $size, $quantity, $total, $referral);

    if ($stmt2->execute()) {
        $_SESSION['last_order_id'] = $stmt2->insert_id;
        $_SESSION['last_product_id'] = $product_id;
        header("Location: {$base_url}checkout.php");
        exit;
    } else {
        echo "<script>alert('Order failed: " . $stmt2->error . "');</script>";
    }
    $stmt2->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($product['name']) ?> - S4-SMART-SHOP</title>
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

        <!-- Left: Images -->
        <div class="product-images">
            <div class="main-image">
                <img id="productImage"
                    src="<?= $base_url ?>back/uploads/<?= htmlspecialchars($product['feature_img']) ?>"
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
            <div class="total-box mb-2">Price: ₹ <span id="productPrice"><?= $product['price'] ?></span></div>

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
            <div class="price total-box mb-3">Order Total: ₹<span id="totalAmount" style="color: green;"> <?= $product['price'] ?></span></div>

            <!-- ✅ Buy Now -->
            <?php if($user): ?>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="size" id="formsize" value="<?= $has_sizes ? htmlspecialchars($product_sizes[0]) : '' ?>">
                <input type="hidden" name="quantity" id="formQuantity" value="1">
                <input type="hidden" name="total" id="formTotal" value="<?= $product['price'] ?>">
                <button type="submit" name="buy_now" class="btn btn-danger mt-3">Buy Now</button>
            </form>
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

<script>
const productURL = "<?= rtrim($base_url, '/') ?>/detail.php/<?= $product['slug'] ?>";
const productName = "<?= addslashes($product['name']) ?>";
const userCode = "<?= $user['user_code'] ?? '' ?>";

function getShareURL() {
    let url = productURL.replace(/#$/, '');
    if (userCode) url += '?ref=' + userCode;
    return url;
}

function shareWhatsApp() {
    const text = `${productName}\n${getShareURL()}`;
    const encodedText = encodeURIComponent(text);
    window.open(`https://api.whatsapp.com/send?text=${encodedText}`, '_blank');
}

function shareFacebook() {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareURL())}&quote=${encodeURIComponent(productName)}`, '_blank');
}

function shareInstagram() {
    const text = `${productName} - ${getShareURL()}`;
    navigator.clipboard.writeText(text).then(() => alert("Product link copied! Share on Instagram."));
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

function redirectToLogin() {
    const currentURL = window.location.href;
    window.location.href = "<?= $base_url ?>login.php?redirect=" + encodeURIComponent(currentURL);
}
</script>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
