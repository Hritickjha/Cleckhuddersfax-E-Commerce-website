<?php
session_start();
ob_start();
require "inc/connection.php";

if (!isset($_SESSION['email'])) {
    header('Location: user_register.php');
    exit();
}

$errors = array();

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];
    $email = $_SESSION['email'];

    // Validate and sanitize input
    $otp = htmlspecialchars(strip_tags($otp));

    // Check OTP and update status
    $otp_check = oci_parse($conn, "SELECT * FROM customer WHERE email = :email AND otp = :otp");
    oci_bind_by_name($otp_check, ":email", $email);
    oci_bind_by_name($otp_check, ":otp", $otp);
    oci_execute($otp_check);

    if (oci_fetch($otp_check) > 0) {
        $update_status = oci_parse($conn, "UPDATE customer SET status = 'active', otp = NULL WHERE email = :email");
        oci_bind_by_name($update_status, ":email", $email);
        if (oci_execute($update_status)) {
            unset($_SESSION['email']);
            
            header('Location: login.php');
            echo"<script>alert('thanks for register on my website')</script>";
            exit();
        } else {
            $errors['db-error'] = "Failed to update status in database!";
        }
    } else {
        $errors['otp-error'] = "Invalid OTP!";
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>OTP Verification</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    <!-- OTP Verification Start -->
    <div class="login">
        <div class="container">
            <div class="section-header">
                <h3>OTP Verification</h3>
                <p>
                    Please enter the OTP sent to your email.
                </p>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <label>OTP</label>
                        <input name="otp" class="form-control" type="text" placeholder="Enter OTP">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" name="verify" class="btn shadow-none btn-lg mb-3">Verify</button>
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
    <!-- OTP Verification End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/slick/slick.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
