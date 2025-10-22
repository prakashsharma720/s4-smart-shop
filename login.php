<?php
include('config.php');
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION['login_redirect'] = $_GET['redirect'] ?? ($base_url . "index.php");
// echo $_GET['redirect'];exit;
$error = '';
$success = '';

// Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            if (password_verify($password, $userData['password'])) {
                $_SESSION['user'] = [
                    'id' => $userData['id'],
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'user_code' => $userData['user_code'] ?? '',
                ];

                $success = "Login successful!";
                $_SESSION['login_redirect'] = $_GET['redirect'] ?? ($base_url . "index.php");
            } else {
                $error = "Wrong password!";
            }
        } else {
            $error = "Email not registered!";
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
<title>S4 Smart Shop - Login</title>
<?php include('head.php'); ?>
</head>
<body>

<?php include('navbar.php'); ?>

<main>
  <div class="bg_color_2">
    <div class="container margin_60_35">
      <div id="login-2">
        <h1>Please login to S4 Smart Shop!</h1>

        <?php if($error): ?>
          <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" id="loginForm">
          <div class="box_form clearfix">
            <div class="box_login">
              <a href="#0" class="social_bt facebook">Login with Facebook</a>
              <a href="#0" class="social_bt google">Login with Google</a>
              <a href="#0" class="social_bt linkedin">Login with Linkedin</a>
            </div>
            <div class="box_login last">
              <div class="form-group">
                <input type="email" class="form-control" placeholder="Your email address" name="email" required>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password (6-10 chars)" pattern=".{6,10}" maxlength="10" required>
                <a href="#0" class="forgot"><small>Forgot password?</small></a>
              </div>
              <div class="form-group">
                <input class="btn_1" type="submit" value="Login">
              </div>
            </div>
          </div>
        </form>

        <p class="text-center register-text">
          Do not have an account yet? <a href="register.php?redirect=<?php echo $_SESSION['login_redirect'];?>" class="register-link">Register now!</a>
        </p>
      </div>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>

<!-- ✅ Success Popup Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h4 class="text-success mb-2"><i class="bi bi-check-circle-fill"></i> Login Successful!</h4>
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
