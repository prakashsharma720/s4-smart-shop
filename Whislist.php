<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "s4shopdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_SESSION['user_id'];
$base_url = "http://localhost/php-project/s4-shop/";

// Fetch wishlist items (join with products)
$sql = "
    SELECT p.id, p.name, p.slug, p.image, p.price 
    FROM wishlist w 
    JOIN products p ON w.product_id = p.id 
    WHERE w.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Wishlist - Taste of Namkeen</title>
  <?php include('head.php'); ?>
  <style>
    .wishlist-card {
      border: 1px solid #eee;
      border-radius: 10px;
      padding: 15px;
      transition: 0.3s ease;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      position: relative;
    }
    .wishlist-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .wishlist-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
    }
    .wishlist-info h6 {
      font-size: 1rem;
      margin-top: 10px;
      color: #d32f2f;
      font-weight: 600;
    }
    .wishlist-info small {
      color: #555;
    }
    .remove-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background: transparent;
      border: none;
      color: #d32f2f;
      font-size: 20px;
    }
  </style>
</head>
<body>
<div class="layer"></div>
<div id="preloader"><div data-loader="circle-side"></div></div>

<?php include('navbar.php'); ?>

<div class="container my-5">
  <h2 class="text-center mb-4 fw-bold">My Wishlist ❤️</h2>

  <div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-3 col-sm-6">
          <div class="wishlist-card text-center">
            <button class="remove-btn" onclick="removeWishlist(<?= $row['id'] ?>)">
              <i class="bi bi-x-circle"></i>
            </button>
            <a href="<?= $base_url ?>detail.php/<?php echo htmlspecialchars($row['slug']); ?>" class="text-decoration-none">
              <img src="<?= $base_url ?>img/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
              <div class="wishlist-info">
                <h6><?php echo htmlspecialchars($row['name']); ?></h6>
                <small>₹<?php echo htmlspecialchars($row['price']); ?></small>
              </div>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-muted fs-5">Your wishlist is empty. 🛒</p>
        <a href="<?= $base_url ?>products.php" class="btn btn-primary mt-3">Browse Products</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
function removeWishlist(productId) {
  if (confirm("Remove this item from your wishlist?")) {
    fetch("remove_wishlist.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "product_id=" + productId
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      location.reload();
    });
  }
}
</script>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
