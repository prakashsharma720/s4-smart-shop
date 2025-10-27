<?php
session_start();
include('config.php');
if (session_status() === PHP_SESSION_NONE) session_start();
// $_SESSION['login_redirect'] = $_GET['redirect'] ?? ($base_url . "index.php");
 $referal_code = $_GET['redirect'];
parse_str(parse_url($referal_code, PHP_URL_QUERY), $params);

// Step 3: Extract 'ref' if available
$ref_code = $params['ref'] ?? '';

// echo htmlspecialchars($ref_code);exit;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmtCount = $conn->prepare("SELECT * FROM users");
    $stmtCount->execute();
    $stmtCount->store_result();
    if($stmtCount->num_rows < 10){
        $user_code = 'S40000'.$stmtCount->num_rows+100+1;
    }else if($stmtCount->num_rows >= 10 || $stmtCount->num_rows < 100+1){
          $user_code = 'S4000'.$stmtCount->num_rows+100+1;
    }else if($stmtCount->num_rows >= 100 || $stmtCount->num_rows < 1000){
          $user_code = 'S400'.$stmtCount->num_rows+1;
    }else if($stmtCount->num_rows >= 1000 || $stmtCount->num_rows <= 10000){
          $user_code = 'S40'.$stmtCount->num_rows+1;
    }else{
          $user_code = 'S4'.$stmtCount->num_rows+1;
    }
    // echo $user_code;exit;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // echo "<pre>";print_r($_POST);exit;
    // Validation
    if (empty($name) || empty($email) || empty($mobile) || empty($password)) {
        $error = "All fields are required!";
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {
        $error = "Phone number must be exactly 10 digits!";
    } elseif (strlen($password) < 6 || strlen($password) > 10) {
        $error = "Password must be between 6 and 10 characters!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
     
      

        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt_insert = $conn->prepare("INSERT INTO users (user_code, name, email, phone, password, referal_code) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssss", $user_code,$name, $email, $mobile, $hashed_password,$ref_code);

            if ($stmt_insert->execute()) {
                $user_id = $stmt_insert->insert_id;
                // ✅ Success message via session
                $_SESSION['user'] = [
                    'id' => $user_id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $mobile,
                    'user_code' => $user_code ?? '',
                ];

                $success = "Registration successful!";
                $_SESSION['login_redirect'] = $_GET['redirect'] ?? ($base_url . "index.php");
                // echo "<script>
                //         alert('Registration Successful! Redirecting to login...');
                //         window.location.href='login.php';
                //       </script>";
                // exit; // stop further execution
            } else {
                $error = "Registration failed. Try again.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - S4 Smart Shop</title>
    <?php include('head.php'); ?>
</head>
<body>
    <?php include('navbar.php'); ?>
<div id="breadcrumb">
        <div class="container">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li>Register</li>
            </ul>
        </div>
    </div>
    <div class="form-container">
        <form method="post">
            <h2>Register</h2>

            <!-- Display messages -->
            <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

            <input type="text" name="name" placeholder="Full Name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            <input type="text" name="phone" placeholder="Phone (10 digits)" pattern="\d{10}" maxlength="10" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>
            <input type="password" name="password" placeholder="Password (6-10 chars)" pattern=".{6,10}" maxlength="10" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password (6-10 chars)" pattern=".{6,10}" maxlength="10" required>
            <input type="submit" value="Register">

            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>

    <?php include('footer.php'); ?>
    <?php include('js.php'); ?>
    <!-- ✅ Success Popup Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h4 class="text-success mb-2"><i class="bi bi-check-circle-fill"></i> Registration Successful!</h4>
      <p>Redirecting, please wait...</p>
    </div>
  </div>
</div>

<?php if ($success): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
    setTimeout(() => {
        window.location.href = "<?= $_SESSION['login_redirect'] ?>";
    }, 1500);
});
</script>
<?php unset($_SESSION['login_redirect']); endif; ?>

</body>
</html>
