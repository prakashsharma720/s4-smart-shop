<?php
include('../config.php');

$msg = "";

// --------------------- HELPER FUNCTION: SLUG GENERATION ---------------------
function generateUniqueSlug($conn, $name, $id = 0) {
    // 1. Basic slug creation (lowercase, trim, replace non-alphanumeric with hyphen)
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9\s-]+/', '', $slug); // Remove special chars (keep spaces and hyphens)
    $slug = preg_replace('/[\s-]+/', '-', $slug); // Replace spaces/multiple hyphens with single hyphen

    $original_slug = $slug;

    return $original_slug;
}
// -----------------------------------------------------------------------------

// Create uploads folder if not exists
if(!is_dir("uploads")) mkdir("uploads",0777,true);

// --------------------- ADD CATEGORY ---------------------
if(isset($_POST['add_category'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // 1. Generate the unique slug
    $slug = generateUniqueSlug($conn, $name); 

    if(move_uploaded_file($_FILES['image']['tmp_name'],$target)){
        // 2. Update INSERT query to include 'slug'
        $stmt = $conn->prepare("INSERT INTO categories (name, description, image, slug) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $description, $image, $slug); 
        $stmt->execute();
        $stmt->close();
        $msg = "✅ Category added successfully! Slug: " . $slug;
    } else {
        $msg = "❌ Failed to upload image!";
    }
}

// --------------------- EDIT CATEGORY ---------------------
if(isset($_POST['edit_category'])){
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    // 1. Generate the unique slug (pass ID to exclude current record)
    $slug = generateUniqueSlug($conn, $name, $id); 

    $update_fields = ['name' => $name, 'description' => $description, 'slug' => $slug];
    $types = "sssi"; // s for name, s for description, s for slug, i for id
    $params = [&$name, &$description, &$slug, &$id];

    if(!empty($_FILES['image']['name'])){
        $image = $_FILES['image']['name'];
        
        // Delete old image
        $res = $conn->query("SELECT image FROM categories WHERE id=$id");
        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            if(!empty($row['image']) && file_exists("uploads/".$row['image'])) unlink("uploads/".$row['image']);
        }
        
        // Upload new image
        if(move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image)){
            // Insert image field into the update array
            $update_fields['image'] = $image;
            // Rearrange types and params for image update
            $types = "ssssi"; 
            $params = [&$name, &$description, &$slug, &$update_fields['image'], &$id];
        } else {
             $msg = "❌ Failed to upload new image!";
        }
    }

    // 2. Use prepared statement for EDIT (Security Fix!)
    $set_clauses = [];
    foreach ($update_fields as $key => $value) {
        $set_clauses[] = "$key = ?";
    }
    
    $update_sql = "UPDATE categories SET " . implode(', ', $set_clauses) . " WHERE id = ?";
    
    $stmt = $conn->prepare($update_sql);

    // Bind parameters dynamically
    if ($stmt) {
        call_user_func_array([$stmt, 'bind_param'], array_merge([$types], $params));
        $stmt->execute();
        $stmt->close();
    }
    
    $msg = "✅ Category updated successfully! Slug: " . $slug;
}

// --------------------- DELETE CATEGORY ---------------------
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    
    // Fetch image name before deleting record
    $res = $conn->query("SELECT image FROM categories WHERE id=$id");
    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        // Delete file
        if(!empty($row['image']) && file_exists("uploads/".$row['image'])) unlink("uploads/".$row['image']);
    }
    
    // Use prepared statement for DELETE (Security Fix!)
    $stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    $msg = "✅ Category deleted successfully!";
}

// Fetch categories
$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="content-wrapper">
    <div class="container-fluid mt-4">

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="fa fa-table"></i> Category List</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fa fa-plus"></i> Add Category
            </button>
        </div>

        <?php if(!empty($msg)){ ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="card shadow mb-3">
            <div class="card-header bg-dark text-white"><i class="fa fa-table"></i> Category List</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th> <th>Description</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($result->num_rows>0){ $i=1; while($row=$result->fetch_assoc()){ ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['category_name']); ?></td>
                                <td><?= htmlspecialchars($row['slug']); ?></td> <td><?= htmlspecialchars($row['description']); ?></td>
                                <td><img src="uploads/<?= $row['image']; ?>" width="60" height="60" alt=""></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($row['description']); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Image (leave blank to keep current)</label>
                                                    <input type="file" name="image" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="edit_category" class="btn btn-success">Save Changes</button>
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php } } else { ?>
                            <tr><td colspan="6" class="text-center">No categories found</td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_category" class="btn btn-success">Save Category</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); include('js.php'); ?>
</body>
</html>