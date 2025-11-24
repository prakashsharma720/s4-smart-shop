<?php
include('config.php');

// ---- GET CATEGORY ----
$cat_slug = $_GET['cat_slug'] ?? '';
$search   = $_GET['search'] ?? "";

$cat_id   = 0;
$cat_name = "S4 Smart Shop";

// ---- FETCH CATEGORIES ----
$categories = [];
$cat_sql = "SELECT id, category_name, slug FROM categories ORDER BY category_name ASC";
$cat_result = $conn->query($cat_sql);
while ($cat = $cat_result->fetch_assoc()) {
    $categories[] = $cat;
}

// ---- FIND CATEGORY NAME ----
if ($cat_slug != "") {
    $stmt = $conn->prepare("SELECT id, category_name FROM categories WHERE slug=? LIMIT 1");
    $stmt->bind_param("s", $cat_slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $c = $res->fetch_assoc();
        $cat_id = $c['id'];
        $cat_name = $c['category_name'];
    }
}

// ---- FETCH PRODUCTS ----
$sql = "SELECT * FROM products WHERE 1";
$params = [];
$types  = "";

if ($cat_id > 0) {
    $sql .= " AND category_id=?";
    $params[] = $cat_id;
    $types .= "i";
}

if (!empty($search)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%".$search."%";
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $cat_name; ?> - Shop Now</title>
    <?php include('head.php'); ?>
</head>

<body>

<div class="layer"></div>
<?php include('navbar.php'); ?>

<div class="container my-5">

<!-- FILTERS -->
<form method="get" class="row g-2 mb-4">

    <div class="col-md-4 col-6">
        <input type="text" name="search" class="form-control"
               placeholder="Search products..."
               value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <div class="col-md-4 col-6">
        <select name="cat_slug" class="form-select">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat['slug']; ?>"
                    <?php if($cat_slug == $cat['slug']) echo "selected"; ?>>
                    <?php echo $cat['category_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4 col-12 d-flex gap-2">
        <button class="btn btn-primary w-50">Search</button>
        <a href="shop.php" class="btn btn-danger w-50">Clear</a>
    </div>

</form>

<!-- PRODUCT GRID -->
<div class="row g-4">
<?php if ($products->num_rows > 0): ?>
<?php while($p = $products->fetch_assoc()): 

    // ---------- INDEX STYLE CALCULATION ----------
    $main_price   = $p['price'];  
    $cut_price    = round($main_price + ($main_price * 0.20)); 
?>
    <div class="col-md-4 col-sm-6">
        <div class="product-card bg-white p-2 rounded shadow-sm">

            <!-- IMAGE + SHOP NOW -->
            <div class="product-img-box position-relative">
                <a href="<?php echo $base_url.'detail.php/'.$p['slug']; ?>">
                    <img src="<?php echo $base_url.'back/uploads/'.$p['feature_img']; ?>"
                         class="img-fluid pro-image" alt="">
                </a>
                <a href="<?php echo $base_url.'detail.php/'.$p['slug']; ?>"
                   class="shop-now-btn">Shop Now</a>
            </div>

            <!-- NAME + PRICE + SHARE -->
            <div class="d-flex justify-content-between align-items-center mt-3">

                <a href="<?php echo $base_url.'detail.php/'.$p['slug']; ?>"
                   class="text-decoration-none text-center flex-grow-1">

                    <h6 class="fw-semibold text-muted mb-1"><?php echo $p['name']; ?></h6>

                    <div style="font-size:18px;">
                        <span style="font-weight:700;">₹<?php echo $main_price; ?></span>
                        <span style="text-decoration:line-through; color:#888; margin:0 6px;">
                            ₹<?php echo $cut_price; ?>
                        </span>
                        <span style="color:green; font-weight:600;">20% OFF</span>
                    </div>
                </a>

                <!-- SHARE BUTTON (INDEX SAME POSITION) -->
                <?php if(isset($_SESSION['user'])): ?>
                <div class="dropdown ms-2">
                    <a class="btn p-1 border rounded"
                       data-bs-toggle="dropdown"
                       style="background:#113d56; color:#fff; width:32px; height:32px;">
                        <i class="fa-solid fa-share"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item text-success"
                               href="#"
                               onclick="shareWhatsApp('<?php echo $p['name']; ?>','<?php echo $p['slug']; ?>')">
                               WhatsApp</a></li>

                        <li><a class="dropdown-item text-primary"
                               href="#"
                               onclick="shareFacebook('<?php echo $p['name']; ?>','<?php echo $p['slug']; ?>')">
                               Facebook</a></li>

                        <li><a class="dropdown-item text-danger"
                               href="#"
                               onclick="shareInstagram('<?php echo $p['name']; ?>','<?php echo $p['slug']; ?>')">
                               Instagram</a></li>
                    </ul>
                </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
<?php endwhile; ?>

<?php else: ?>
    <div class="col-12 text-center">
        <p>No Products Found</p>
    </div>
<?php endif; ?>
</div>

</div>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>

<script>
function getURL(slug){
    let url = "<?php echo $base_url; ?>detail.php/"+slug;
    let ref = "<?php echo $_SESSION['user']['user_code'] ?? ''; ?>";
    return ref ? url + "?ref=" + ref : url;
}

function shareWhatsApp(name, slug){
    window.open("https://api.whatsapp.com/send?text="+encodeURIComponent(name+" - "+getURL(slug)));
}
function shareFacebook(name, slug){
    window.open("https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent(getURL(slug)));
}
function shareInstagram(name, slug){
    navigator.clipboard.writeText(name+" - "+getURL(slug));
    alert("Link copied! Paste on Instagram.");
}
</script>

</body>
</html>
