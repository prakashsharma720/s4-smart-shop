<?php
// ================== DB CONNECT ==================
$conn = new mysqli("localhost", "root", "", "s4shopdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";

// ------------------ DELETE PRODUCT ------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM products WHERE id=$id")) {
        $msg = "🗑️ Product deleted successfully!";
        header("Location: add_product.php?msg=" . urlencode($msg)); // ✅ fixed redirect path
        exit;
    } else {
        die("Delete failed: " . $conn->error);
    }
}

// ------------------ ADD PRODUCT ------------------
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // ✅ Generate slug from product name
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    if (!is_dir("uploads")) mkdir("uploads", 0777, true);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO products (category_id, name, slug, description, price, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $category_id, $name, $slug, $description, $price, $image);
        $stmt->execute();
        $msg = "✅ Product added successfully!";
        $stmt->close();
    } else {
        $msg = "❌ Failed to upload image!";
    }
}

// ------------------ EDIT PRODUCT ------------------
if (isset($_POST['edit_product'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $sizes = isset($_POST['sizes']) ? implode(",", $_POST['sizes']) : "";

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target); 

        $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, image=?, sizes=? WHERE id=?");
        $stmt->bind_param("issdssi", $category_id, $name, $description, $price, $image, $sizes, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, sizes=? WHERE id=?");
        $stmt->bind_param("issdsi", $category_id, $name, $description, $price, $sizes, $id);
    }
    $stmt->execute();
    $msg = "✏️ Product updated successfully!";
    $stmt->close();
}

// ------------------ FETCH PRODUCTS ------------------
$catResultBase = $conn->query("SELECT * FROM categories");
$result = $conn->query("
    SELECT p.*, c.name AS category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
");
?>

<?php include('head.php'); ?>
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<body>
<?php include('navbar.php'); ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="fa fa-table"></i> Product List</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa fa-plus"></i> Add Product
            </button>
        </div>

        <?php 
        $display_msg = !empty($msg) ? $msg : (isset($_GET['msg']) ? urldecode($_GET['msg']) : "");
        if (!empty($display_msg)) { ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($display_msg); ?></div>
        <?php } ?>

        <div class="card mb-3">
            <div class="card-header"><i class="fa fa-table"></i> Product List</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <!-- <th>Sizes</th> -->
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0):
                            $i = 1;
                            while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo substr(strip_tags($row['description']), 0, 50) . '...'; ?></td>
                                <td>₹<?php echo $row['price']; ?></td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <!-- <td><?php //echo htmlspecialchars($row['sizes']); ?></td> -->
                                <td><img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="60" height="60" style="object-fit:cover;"></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></button>
                                    
                                    <!-- ✅ FIXED DELETE LINK -->
                                    <a href="add_product.php?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this product?');">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5>View Product</h5></div>
                                        <div class="modal-body">
                                            <p><b>Name:</b> <?php echo htmlspecialchars($row['name']); ?></p>
                                            <p><b>Price:</b> ₹<?php echo $row['price']; ?></p>
                                            <p><b>Category:</b> <?php echo htmlspecialchars($row['category_name']); ?></p>
                                            <!-- <p><b>Sizes:</b> <?php //echo htmlspecialchars($row['sizes']); ?></p> -->
                                            <p><b>Description:</b></p>
                                            <div class="border p-2"><?php echo $row['description']; ?></div>
                                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="img-fluid mt-3" alt="Product Image">
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-header"><h5>Edit Product</h5></div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                                                <div class="mb-3">
                                                    <label>Product Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" id="edit_description<?php echo $row['id']; ?>" class="form-control" rows="5"><?php echo htmlspecialchars($row['description']); ?></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Price</label>
                                                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $row['price']; ?>" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label>Category</label>
                                                    <select name="category_id" class="form-control" required>
                                                        <?php
                                                        $catResultBase->data_seek(0);
                                                        while ($cat = $catResultBase->fetch_assoc()) {
                                                            $sel = $cat['id'] == $row['category_id'] ? "selected" : "";
                                                            echo "<option value='{$cat['id']}' $sel>" . htmlspecialchars($cat['name']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- <div class="mb-3">
                                                    <label>Sizes (Select Multiple)</label>
                                                    <?php //$selectedSizes = explode(",", $row['sizes']); ?>
                                                    <select name="sizes[]" multiple class="form-control">
                                                        <?php
                                                        // $sizeOptions = ['S', 'M', 'L', 'XL', 'XXL'];
                                                        // foreach ($sizeOptions as $s) {
                                                        //     $sel = in_array($s, $selectedSizes) ? "selected" : "";
                                                        //     echo "<option value='$s' $sel>$s</option>";
                                                        // }
                                                        ?>
                                                    </select>
                                                </div> -->

                                                <div class="mb-3">
                                                    <label>Image</label>
                                                    <input type="file" name="image" class="form-control">
                                                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="80" class="mt-2">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="edit_product" class="btn btn-success">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; else: ?>
                            <tr><td colspan="8" class="text-center">No products found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header"><h5>Add Product</h5></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="add_description" class="form-control" rows="5"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <?php
                                $catResultAdd = $conn->query("SELECT * FROM categories");
                                while ($cat = $catResultAdd->fetch_assoc()) {
                                    echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- <div class="mb-3">
                            <label>Sizes (Select Multiple)</label>
                            <select name="sizes[]" multiple class="form-control">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div> -->

                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_product" class="btn btn-success">Save Product</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>

<script>
    CKEDITOR.replace('add_description');
    <?php
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        echo "CKEDITOR.replace('edit_description{$row['id']}');";
    }
    ?>
</script>
</body>
</html>
