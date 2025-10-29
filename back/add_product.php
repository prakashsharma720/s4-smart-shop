<?php
// ================== DB CONNECT ==================
include('../config.php');

$msg = "";

// ------------------ DELETE PRODUCT ------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete = $conn->query("DELETE FROM products WHERE id = $id");

    if ($delete) {
        echo "<script>
            setTimeout(function() {
                const modalBody = document.querySelector('#statusModal .modal-body');
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class='text-center p-4'>
                            <h4 class='text-success mb-2'>
                                <i class=\"bi bi-check-circle-fill\"></i> Product deleted successfully!
                            </h4>
                            <p>Your changes have been saved.</p>
                        </div>
                    `;
                }
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
                modal.show();
                setTimeout(() => {
                    window.location.href = 'add_product.php';
                }, 2000);
            }, 500);
        </script>";
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

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    if (!is_dir("uploads")) mkdir("uploads", 0777, true);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO products (category_id, name, slug, description, price, feature_img) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $category_id, $name, $slug, $description, $price, $image);
        $stmt->execute();
        echo "<script>
            setTimeout(function() {
                const modalBody = document.querySelector('#statusModal .modal-body');
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class='text-center p-4'>
                            <h4 class='text-success mb-2'>
                                <i class=\"bi bi-check-circle-fill\"></i> Product added successfully!
                            </h4>
                            <p>Your new product has been saved.</p>
                        </div>
                    `;
                }
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
                modal.show();
                setTimeout(() => {
                    window.location.href = 'add_product.php';
                }, 2000);
            }, 500);
        </script>";
        $stmt->close();
    } else {
        echo "<script>
            setTimeout(function() {
                const modalBody = document.querySelector('#statusModal .modal-body');
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class='text-center p-4'>
                            <h4 class='text-danger mb-2'>
                                <i class=\"bi bi-x-circle-fill\"></i> Upload failed!
                            </h4>
                            <p>Please try again.</p>
                        </div>
                    `;
                }
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
                modal.show();
                setTimeout(() => {
                    window.location.href = 'add_product.php';
                }, 2000);
            }, 500);
        </script>";
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
        $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, feature_img=?, sizes=? WHERE id=?");
        $stmt->bind_param("issdssi", $category_id, $name, $description, $price, $image, $sizes, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, sizes=? WHERE id=?");
        $stmt->bind_param("issdsi", $category_id, $name, $description, $price, $sizes, $id);
    }
    $stmt->execute();
    echo "<script>
        setTimeout(function() {
            const modalBody = document.querySelector('#statusModal .modal-body');
            if (modalBody) {
                modalBody.innerHTML = `
                    <div class='text-center p-4'>
                        <h4 class='text-success mb-2'>
                            <i class=\"bi bi-check-circle-fill\"></i> Product updated successfully!
                        </h4>
                        <p>Your changes have been saved.</p>
                    </div>
                `;
            }
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
            modal.show();
            setTimeout(() => {
                window.location.href = 'add_product.php';
            }, 2000);
        }, 500);
    </script>";
    $stmt->close();
}

// ------------------ FETCH PRODUCTS ------------------
$catResultBase = $conn->query("SELECT * FROM categories");
$result = $conn->query("
    SELECT p.*, c.category_name AS category_name 
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
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0):
                            $i = 1;
                            while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= substr(strip_tags($row['description']), 0, 50) . '...'; ?></td>
                                <td>₹<?= $row['price']; ?></td>
                                <td><?= htmlspecialchars($row['category_name']); ?></td>
                                <td><img src="uploads/<?= htmlspecialchars($row['feature_img']); ?>" width="60" height="60" style="object-fit:cover;"></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['id']; ?>"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>"><i class="fa fa-edit"></i></button>
                                    <a href="add_product.php?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5>View Product</h5></div>
                                        <div class="modal-body">
                                            <p><b>Name:</b> <?= htmlspecialchars($row['name']); ?></p>
                                            <p><b>Price:</b> ₹<?= $row['price']; ?></p>
                                            <p><b>Category:</b> <?= htmlspecialchars($row['category_name']); ?></p>
                                            <p><b>Description:</b></p>
                                            <div class="border p-2"><?= $row['description']; ?></div>
                                            <div class="img" style="width: 500px;">
                                                <img src="uploads/<?= htmlspecialchars($row['feature_img']); ?>" class="img-fluid mt-3" alt="Product Image">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-header"><h5>Edit Product</h5></div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label>Product Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" id="edit_description<?= $row['id']; ?>" class="form-control" rows="5"><?= htmlspecialchars($row['description']); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Price</label>
                                                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $row['price']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Category</label>
                                                    <select name="category_id" class="form-control" required>
                                                        <?php
                                                        $catResultBase->data_seek(0);
                                                        while ($cat = $catResultBase->fetch_assoc()) {
                                                            $sel = $cat['id'] == $row['category_id'] ? "selected" : "";
                                                            echo "<option value='{$cat['id']}' $sel>" . htmlspecialchars($cat['category_name']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Image</label>
                                                    <input type="file" name="image" class="form-control">
                                                    <img src="uploads/<?= htmlspecialchars($row['feature_img']); ?>" width="80" class="mt-2">
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
                            <tr><td colspan="7" class="text-center">No products found</td></tr>
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
                                    echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['category_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
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

    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4"></div>
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
