<?php
session_start();
include('config.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $stmt_insert = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $name, $email, $mobile, $hashed_password);

            if ($stmt_insert->execute()) {
                // ✅ Success message via session
                $_SESSION['success_msg'] = "Registration Successful! Redirecting to login...";
                echo "<script>
                        alert('Registration Successful! Redirecting to login...');
                        window.location.href='login.php';
                      </script>";
                exit; // stop further execution
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
</body>
</html>
