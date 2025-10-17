<?php
include('config.php');
// ================== START SESSION ==================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$error = '';
$success = '';

// ================== LOGOUT ==================
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// ================== LOGIN ==================
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
                // ✅ Store full user data including user_code in session
                $_SESSION['user'] = [
                    'id' => $userData['id'],
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'user_code' => $userData['user_code'] ?? '',
                ];

                $success = "Login successful!";
                header("Location: index.php");
                exit;
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

        <?php if($success) echo "<p style='color:green;text-align:center;'>$success</p>"; ?>
        <?php if($error) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>

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
          Do not have an account yet? <a href="register.php" class="register-link">Register now!</a>
        </p>
      </div>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>
<?php include('js.php'); ?>
</body>
</html>
