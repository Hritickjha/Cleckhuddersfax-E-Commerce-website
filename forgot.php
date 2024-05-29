<?php
session_start();
ob_start();
require "inc/connection.php";

$errors = array();

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    $email = htmlspecialchars(strip_tags($email));

    // Check if email exists
    $email_check = oci_parse($conn, "SELECT * FROM customer WHERE email = :email");
    oci_bind_by_name($email_check, ":email", $email);
    oci_execute($email_check);

    if (oci_fetch($email_check) > 0) {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in the database
        $update_otp = oci_parse($conn, "UPDATE customer SET otp = :otp WHERE email = :email");
        oci_bind_by_name($update_otp, ":otp", $otp);
        oci_bind_by_name($update_otp, ":email", $email);
        oci_execute($update_otp);

        // Send OTP to user's email
        if (sendOTP($email, $otp)) {
            $_SESSION['otp_email'] = $email;
            header('Location: reset_password.php'); // Redirect to reset_password.php
            exit();
        } else {
            $errors['mail-error'] = "Failed to send OTP email.";
        }
    } else {
        $errors['email'] = "No account found with this email.";
    }
}

function sendOTP($email, $otp) {
    $subject = 'Password Reset OTP';
    $message = "Your OTP for password reset is: $otp";
    $headers = "From: msnausad678@gmail.com\r\n";
    $headers .= "Reply-To: msnausad678@gmail.com\r\n";

    return mail($email, $subject, $message, $headers);
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    <div class="login">
        <div class="container">
            <div class="section-header">
                <h3>Forgot Password</h3>
                <p>Please enter your email to receive a password reset OTP.</p>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <label>Email</label>
                        <input name="email" class="form-control" type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" name="send_otp" class="btn shadow-none btn-lg mb-3">Send OTP</button>
                    </div>
                </div>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/slick/slick.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
