<?php
include('config.php');

$cat_slug = '';
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$url_array = explode('/',$actual_link);
$cat_slug = isset($url_array['5']) ? trim($url_array['5']) : '';
// print_r($url_array);exit;


// CHANGE 1: Get selected category SLUG
$cat_slug = isset($_GET['cat_slug']) ? trim($_GET['cat_slug']) : $cat_slug;
// Get search keyword
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$cat_id = 0; // Initialize ID
$cat_name = "All Namkeen"; 

// Fetch categories (Include slug for dropdown linking)
$categories = [];
$cat_sql = "SELECT id, name, slug FROM categories ORDER BY name ASC";
$cat_result = $conn->query($cat_sql);
if ($cat_result && $cat_result->num_rows > 0) {
    while ($row = $cat_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// CHANGE 2: Get ID and Name by SLUG securely
if ($cat_slug != "") {
    $current_cat_sql = "SELECT id, name FROM categories WHERE slug = ?";
    $stmt = $conn->prepare($current_cat_sql);
    if ($stmt) {
        $stmt->bind_param("s", $cat_slug);
        $stmt->execute();
        $current_cat_result = $stmt->get_result();
        if ($current_cat_result && $current_cat_result->num_rows > 0) {
            $cat_row = $current_cat_result->fetch_assoc();
            $cat_id = $cat_row['id']; // Get the ID for product fetching
            $cat_name = htmlspecialchars($cat_row['name']);
        }
        $stmt->close();
    }
}

// Fetch products (category + search filter - uses $cat_id derived from $cat_slug)
$product_sql = "SELECT * FROM products WHERE 1"; // SELECT * includes the 'slug' column
$params = [];
$types = '';

if ($cat_id > 0) {
    $product_sql .= " AND category_id = ?";
    $types .= "i";
    $params[] = $cat_id;
}
if ($search != "") {
    $product_sql .= " AND name LIKE ?";
    $types .= "s";
    $search_term = "%" . $search . "%";
    $params[] = $search_term;
}
$product_sql .= " ORDER BY id ASC";

// SECURITY FIX: Using prepared statements for product fetching
$result = false;
$stmt = $conn->prepare($product_sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $cat_name; ?> - Shop, Earn & Grow with S4 Smart Shop</title>
    <?php include('head.php'); ?>
</head>
<body>
<div class="layer"></div>
<div id="preloader"><div data-loader="circle-side"></div></div>

<?php include('navbar.php'); ?>

<div class="container my-5">

<form method="get" class="row mb-4 g-2">

    <!-- Search by name -->
    <div class="col-md-4 col-sm-6 mb-2">
        <input type="text" name="search" class="form-control" 
               placeholder="Search products..." 
               value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <!-- Category dropdown -->
    <div class="col-md-4 col-sm-6 mb-2">
        <select name="cat_slug" class="form-select">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['slug']); ?>" 
                    <?php if($cat_slug == $cat['slug']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Filter & Clear buttons -->
   <div class="col-md-4 col-sm-12 mb-2 d-flex gap-2">
    <button type="submit" class="btn btn-search w-50">Search</button>
    <a href="<?= $_SERVER['PHP_SELF']; ?>" class="btn btn-danger w-50">Clear</a>
</div>

</form>

    <!-- <h2 class="mb-4 text-center">All Products</h2> -->

    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                 <div class="col-md-4 col-sm-6">
                    <a href="<?= $base_url ?>detail.php/<?php echo htmlspecialchars($row['slug']); ?>"
                        class="text-decoration-none">
                        <div class="product-card">
                            <img src="<?= $base_url ?>back/uploads/<?php echo $row['feature-img']; ?>"
                                alt="<?php echo htmlspecialchars($row['name']); ?>"
                                class="img-fluid">
                            <div class="product-info mt-2 text-center">
                                <p><?php echo htmlspecialchars($row['name']); ?></p>
                                <div class="from-price-middle text-center mt-2">
                                   Price : ₹<?php echo $row['price']; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No products found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>