<?php
// ================== Database Connection & Save ==================
$conn = new mysqli("localhost", "root", "", "s4shopdb");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Success message variable
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $conn->real_escape_string($_POST['name_contact']);
    $lastname = $conn->real_escape_string($_POST['lastname_contact']);
    $email    = $conn->real_escape_string($_POST['email_contact']);
    $phone    = $conn->real_escape_string($_POST['phone_contact']);
    $message  = $conn->real_escape_string($_POST['message_contact']);

    $sql = "INSERT INTO contact_messages (name, lastname, email, phone, message)
            VALUES ('$name', '$lastname', '$email', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success_msg = "Your message has been sent successfully!";
    } else {
        $success_msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Find easily a doctor and book online an appointment">
    <meta name="author" content="Ansonika">
    <title>Contact Us || S4 Smart Shop</title>
    <?php include('head.php');?>
</head>

<body>

<div class="layer"></div>

<div id="preloader">
    <div data-loader="circle-side"></div>
</div>

<?php include('navbar.php');?>

<main>
<div class="banner-contact position-relative">
    <img src="<?php echo $base_url;?>img/AdobeStock_524039911_Preview (1).jpeg" 
         alt="Contact Banner" 
         class="img-fluid w-100" 
         style="height:400px; object-fit: cover;">
</div>

<div class="container margin_60_35">
    <div class="row">
        <aside class="col-lg-3 col-md-4">
            <div id="contact_info">
                <h3>Contacts info</h3>
                <p>
                Chittorgarh, Rajasthan 312203<br>
                <i class="bi bi-telephone-fill me-2"></i>
                <a href="tel:+918107875712" class="text-dark text-decoration-none">+91 8107875712</a>
                <br>
                <i class="bi bi-envelope-fill me-2"></i>
                <a href="mailto:info@s4smartshop.com" class="text-dark text-decoration-none">info@s4smartshop.com</a>
                </p>
                <h4>Get directions</h4>
                <form action="http://maps.google.com/maps" method="get" target="_blank">
                    <div class="form-group">
                        <input type="text" name="saddr" placeholder="Enter your location" class="form-control styled">
                        <input type="hidden" name="daddr" value="Nangpura,Near Government Upper Primary School, Chittorgarh, Rajasthan 312203">
                    </div>
                    <input type="submit" value="Get directions" class="btn_1 add_bottom_45">
                </form>
                <ul>
                    <li><strong>Administration</strong><br>
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel://003823932342">+918107875712</a><br>
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:admin@s4smartshop.com">admin@s4smartshop.com</a><br>
                        <small>24*7 Available</small>
                    </li>
                    <li><strong>General questions</strong><br>
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel://003823932342">+918107875712</a><br>
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:questions@s4smartshop.com">questions@s4smartshop.com</a><br>
                        <small>24*7 Available</small>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="col-lg-8 col-md-8 ml-auto">
            <div class="box_general">
                <h3>Contact us</h3>
                <p>Fill the form to connect with us.</p>

                <div>
                    <div id="message-contact"></div>
                    <form method="post" action="" id="contactform">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name_contact" name="name_contact" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="lastname_contact" name="lastname_contact" placeholder="Last name">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="email" id="email_contact" name="email_contact" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <input type="text" id="phone_contact" name="phone_contact" class="form-control" placeholder="Phone number" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea rows="5" id="message_contact" name="message_contact" class="form-control" style="height:100px;" placeholder="Message"></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Submit" class="btn_1 add_top_20" id="submit-contact">
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</main>

<?php include('footer.php');?>
<?php include('js.php');?>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h4 class="text-success mb-2"><i class="bi bi-check-circle-fill"></i> Success!</h4>
      <p id="successModalMessage"></p>
      <button type="button" class="btn btn-success mt-3" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<script>
<?php if($success_msg != ''): ?>
    document.addEventListener('DOMContentLoaded', function() {
        // Set modal message
        document.getElementById('successModalMessage').innerText = "<?php echo $success_msg; ?>";

        // Show the modal
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        // Redirect after 3 seconds
        setTimeout(function() {
            window.location.href = 'contact.php';
        }, 3000);
    });
<?php endif; ?>
</script>
