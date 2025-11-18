<?php
// ================== DB CONNECT ==================
include('../config.php');

// ------------------ DELETE PRODUCT ------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete = $conn->query("DELETE FROM products WHERE id = $id");

    if ($delete) {
        echo "<script>
            setTimeout(function() {
                const modalBody = document.querySelector('#statusModal .modal-body');
                if(modalBody) {
                    modalBody.innerHTML = `
                        <div class='text-center p-4'>
                            <h4 class='text-success mb-2'>
                                <i class='bi bi-check-circle-fill'></i> Product deleted successfully!
                            </h4>
                            <p>Your changes have been saved.</p>
                        </div>
                    `;
                }
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
                modal.show();
                setTimeout(() => { window.location.href = 'add_product.php'; }, 2000);
            }, 500);
        </script>";
    } else {
        die("Delete failed: " . $conn->error);
    }
}
// ------------------ DELETE SINGLE THUMBNAIL ------------------
if (isset($_POST['delete_thumb']) && isset($_POST['product_id'])) {
    $thumbToDelete = $_POST['delete_thumb'];
    $product_id = intval($_POST['product_id']);

    // Remove file
    if ($thumbToDelete && file_exists("uploads/".$thumbToDelete)) unlink("uploads/".$thumbToDelete);

    // Update DB
    $resThumb = $conn->query("SELECT thumbnails FROM products WHERE id=$product_id LIMIT 1");
    if ($resThumb && $resThumb->num_rows > 0) {
        $row = $resThumb->fetch_assoc();
        $thumbs = array_filter(array_map('trim', explode(',', $row['thumbnails'])));
        $thumbs = array_diff($thumbs, [$thumbToDelete]);
        $thumb_csv = implode(",", $thumbs);
        $conn->query("UPDATE products SET thumbnails='$thumb_csv' WHERE id=$product_id");
    }

    echo "<script>
        const modalBody = document.querySelector('#statusModal .modal-body');
        modalBody.innerHTML = '<div class=\"text-center p-4\"><h4 class=\"text-success\"><i class=\"bi bi-check-circle-fill\"></i> Thumbnail deleted!</h4></div>';
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
        modal.show();
        setTimeout(()=>{window.location.href='add_product.php';}, 1500);
    </script>";
}

// ------------------ ADD PRODUCT ------------------
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

    if (!is_dir("uploads")) mkdir("uploads", 0777, true);

    // Feature Image
    $image = $_FILES['image']['name'];
    $imageTarget = "uploads/" . basename($image);

    // Thumbnails (max 5)
    $thumbNames = [];
    if (!empty($_FILES['thumbnails']['name'][0])) {
        for ($i = 0; $i < count($_FILES['thumbnails']['name']); $i++) {
            if ($i >= 5) break; // max 5
            $tname = $_FILES['thumbnails']['name'][$i];
            $ttmp  = $_FILES['thumbnails']['tmp_name'][$i];
            $ext = pathinfo($tname, PATHINFO_EXTENSION);
            $newName = time() . "_$i." . $ext;
            $tTarget = "uploads/" . $newName;
            if (move_uploaded_file($ttmp, $tTarget)) {
                $thumbNames[] = $newName;
            }
        }
    }
    $thumbCSV = implode(",", $thumbNames);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imageTarget)) {
        $stmt = $conn->prepare("INSERT INTO products (category_id, name, slug, description, price, feature_img, thumbnails) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdss", $category_id, $name, $slug, $description, $price, $image, $thumbCSV);
        $stmt->execute();
        $stmt->close();
        echo "<script>
            setTimeout(function() {
                const modalBody = document.querySelector('#statusModal .modal-body');
                modalBody.innerHTML = `
                    <div class='text-center p-4'>
                        <h4 class='text-success mb-2'><i class='bi bi-check-circle-fill'></i> Product added successfully!</h4>
                        <p>Your new product has been saved.</p>
                    </div>`;
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
                modal.show();
                setTimeout(() => { window.location.href = 'add_product.php'; }, 2000);
            }, 500);
        </script>";
    } else {
        echo "<script>
            setTimeout(function() {
                const modalBody = document.querySelector('#statusModal .modal-body');
                modalBody.innerHTML = `
                    <div class='text-center p-4'>
                        <h4 class='text-danger mb-2'><i class='bi bi-x-circle-fill'></i> Upload failed!</h4>
                        <p>Please try again.</p>
                    </div>`;
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
                modal.show();
                setTimeout(() => { window.location.href = 'add_product.php'; }, 2000);
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

    // Get existing thumbnails
    $resThumb = $conn->query("SELECT feature_img, thumbnails FROM products WHERE id = $id LIMIT 1");
    $rowData = $resThumb->fetch_assoc();
    $existingThumbs = $rowData['thumbnails'] ?? '';
    $existingThumbsArray = $existingThumbs ? array_filter(array_map('trim', explode(',', $existingThumbs))) : [];

    // Feature Image Update
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    } else {
        $image = $rowData['feature_img'];
    }

    // New Thumbnails
    $newThumbs = [];
    if (!empty($_FILES['thumbnails']['name'][0])) {
        for ($i = 0; $i < count($_FILES['thumbnails']['name']); $i++) {
            if (count($existingThumbsArray) + count($newThumbs) >= 5) break;
            $tname = $_FILES['thumbnails']['name'][$i];
            if (!$tname) continue;
            $ttmp = $_FILES['thumbnails']['tmp_name'][$i];
            $ext = pathinfo($tname, PATHINFO_EXTENSION);
            $newName = time() . "_$i." . $ext;
            if (move_uploaded_file($ttmp, "uploads/" . $newName)) {
                $newThumbs[] = $newName;
            }
        }
    }
    $mergedThumbs = array_slice(array_merge($existingThumbsArray, $newThumbs), 0, 5);
    $thumbCSV = implode(",", $mergedThumbs);

    $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, feature_img=?, thumbnails=? WHERE id=?");
    $stmt->bind_param("issdssi", $category_id, $name, $description, $price, $image, $thumbCSV, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
        setTimeout(function() {
            const modalBody = document.querySelector('#statusModal .modal-body');
            modalBody.innerHTML = `
                <div class='text-center p-4'>
                    <h4 class='text-success mb-2'><i class='bi bi-check-circle-fill'></i> Product updated successfully!</h4>
                    <p>Your changes have been saved.</p>
                </div>`;
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('statusModal'));
            modal.show();
            setTimeout(() => { window.location.href = 'add_product.php'; }, 2000);
        }, 500);
    </script>";
}

// ------------------ FETCH PRODUCTS ------------------
$categories = $conn->query("SELECT * FROM categories");
$products = $conn->query("
    SELECT p.*, c.category_name FROM products p
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
                            <th>Thumbnails</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($products && $products->num_rows > 0): $i=1; while($row=$products->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= substr(strip_tags($row['description']),0,50).'...'; ?></td>
                            <td>₹<?= $row['price']; ?></td>
                            <td><?= htmlspecialchars($row['category_name']); ?></td>
                            <td><img src="uploads/<?= htmlspecialchars($row['feature_img']); ?>" width="60" height="60" style="object-fit:cover;"></td>
                            <td>
    <?php
    $thumbs = $row['thumbnails'] ? array_filter(explode(',', $row['thumbnails'])) : [];
    foreach($thumbs as $t):
    ?>
    <div style="display:inline-block; position:relative; margin-right:4px;">
        <img src="uploads/<?= htmlspecialchars($t); ?>" width="40" height="40" style="object-fit:cover;border-radius:4px;">
        <form method="POST" style="position:absolute; top:-5px; right:-5px;" onsubmit="return confirm('Delete this thumbnail?');">
            <input type="hidden" name="delete_thumb" value="<?= htmlspecialchars($t); ?>">
            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
            <button type="submit" style="background:red;border:none;color:white;border-radius:50%;width:16px;height:16px;line-height:14px;font-weight:bold;cursor:pointer;">×</button>
        </form>
    </div>
    <?php endforeach; ?>
</td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['id']; ?>"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>"><i class="fa fa-edit"></i></button>
                                <a href="add_product.php?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <!-- VIEW MODAL -->
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
                                        <img src="uploads/<?= htmlspecialchars($row['feature_img']); ?>" class="img-fluid mt-3" alt="Product Image" style="max-width:400px;">
                                        <?php if($thumbs): ?>
                                        <hr><h6>Thumbnails</h6>
                                        <div class="d-flex flex-wrap">
                                            <?php foreach($thumbs as $t): ?>
                                                <img src="uploads/<?= htmlspecialchars($t); ?>" width="120" height="120" style="object-fit:cover;margin:4px;border-radius:6px;">
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- EDIT MODAL -->
                        <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="modal-header"><h5>Edit Product</h5></div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <div class="mb-3">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <textarea name="description" id="edit_description<?= $row['id']; ?>" class="form-control"><?= htmlspecialchars($row['description']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Price</label>
                                                <input type="number" step="0.01" name="price" class="form-control" value="<?= $row['price']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Category</label>
                                                <select name="category_id" class="form-control">
                                                    <?php
                                                    $categories->data_seek(0);
                                                    while($c=$categories->fetch_assoc()) {
                                                        $sel = $c['id']==$row['category_id']?'selected':'';
                                                        echo "<option value='{$c['id']}' $sel>".htmlspecialchars($c['category_name'])."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Feature Image</label>
                                                <input type="file" name="image" class="form-control">
                                                <img src="uploads/<?= htmlspecialchars($row['feature_img']); ?>" width="80" class="mt-2">
                                            </div>
                                            <div class="mb-3">
                                                <label>Upload Thumbnails (max 5)</label>
                                                <input type="file" name="thumbnails[]" class="form-control" multiple>
                                                <small class="text-muted">Existing thumbnails shown below.</small>
                                            </div>
                                            <div class="mb-3 d-flex flex-wrap">
                                                <?php foreach($thumbs as $t): ?>
                                                    <img src="uploads/<?= htmlspecialchars($t); ?>" width="80" height="80" style="margin:4px;border-radius:6px;">
                                                <?php endforeach; ?>
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

    <!-- ADD PRODUCT MODAL -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header"><h5>Add Product</h5></div>
                    <div class="modal-body">
                        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-3"><label>Description</label><textarea name="description" id="add_description" class="form-control" rows="5"></textarea></div>
                        <div class="mb-3"><label>Price</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                        <div class="mb-3"><label>Category</label>
                            <select name="category_id" class="form-control">
                                <?php $categories->data_seek(0); while($c=$categories->fetch_assoc()) echo "<option value='{$c['id']}'>".htmlspecialchars($c['category_name'])."</option>"; ?>
                            </select>
                        </div>
                        <div class="mb-3"><label>Feature Image</label><input type="file" name="image" class="form-control" required></div>
                        <div class="mb-3"><label>Thumbnails (Max 5)</label><input type="file" name="thumbnails[]" class="form-control" multiple></div>
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
            <div class="modal-content"><div class="modal-body text-center p-4"></div></div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>

<script>
CKEDITOR.replace('add_description');
<?php
$products->data_seek(0);
while($row=$products->fetch_assoc()) {
    echo "CKEDITOR.replace('edit_description{$row['id']}');";
}
?>
</script>

</body>
</html>
