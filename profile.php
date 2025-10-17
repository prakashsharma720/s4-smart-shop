<?php
session_start();

if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

$conn = new mysqli("localhost", "root", "", "s4shopdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$success = '';
$error = '';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($email) || empty($phone)) {
        $error = "All fields are required!";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $error = "Phone number must be 10 digits!";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $phone, $user['id']);
        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $_SESSION['user']['name']  = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $user = $_SESSION['user'];
        } else {
            $error = "Failed to update profile: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Profile - S4 Smart Shop</title>
<?php include('head.php'); ?>
<style>
body { background-color: #f1f3f6; }
.profile-wrapper { display: flex; flex-wrap: wrap; margin-top: 30px; gap: 20px; }
.sidebar { width: 220px; background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.sidebar a { display: block; padding: 10px 15px; color: #333; margin-bottom: 5px; border-radius: 5px; text-decoration: none; }
.sidebar a.active, .sidebar a:hover { background-color: #f7931e; color: #fff; }
.profile-card { flex: 1; background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 300px; }
.profile-card h3 { margin-bottom: 20px; }
input[readonly] { background-color: #f8f9fa; }
.btn-group { margin-top: 20px; }
</style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container profile-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h5>Account</h5>
        <a href="#" class="active">Profile</a>
        <a href="#">Orders</a>
        <a href="#">Addresses</a>
        <a href="#">Wishlist</a>
        <a href="login.php?action=logout">Logout</a>
    </div>

    <!-- Profile Card -->
    <div class="profile-card">
        <h3>My Profile</h3>
        <?php if($success) echo "<p class='text-success'>$success</p>"; ?>
        <?php if($error) echo "<p class='text-danger'>$error</p>"; ?>

        <form method="POST" id="profileForm">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" readonly required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" readonly maxlength="10" pattern="\d{10}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Referral Code</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($user['user_code'] ?? '') ?>" readonly>
            </div>

            <div class="btn-group">
                <button type="button" id="editBtn" class="btn btn-warning">Edit</button>
                <button type="submit" name="update_profile" id="saveBtn" class="btn btn-primary" style="display:none;">Save</button>
                <button type="button" id="cancelBtn" class="btn btn-secondary" style="display:none;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
const editBtn = document.getElementById('editBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');
const formFields = document.querySelectorAll('#profileForm input[name="name"], #profileForm input[name="email"], #profileForm input[name="phone"]');

// Store original values
let originalValues = {};
formFields.forEach(f => originalValues[f.name] = f.value);

// Enable edit mode
editBtn.addEventListener('click', () => {
    formFields.forEach(f => f.removeAttribute('readonly'));
    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-block';
    cancelBtn.style.display = 'inline-block';
});

// Cancel edit
cancelBtn.addEventListener('click', () => {
    formFields.forEach(f => {
        f.value = originalValues[f.name];
        f.setAttribute('readonly', true);
    });
    editBtn.style.display = 'inline-block';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
});
</script>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
