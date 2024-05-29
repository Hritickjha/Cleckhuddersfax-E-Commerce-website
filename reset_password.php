<?php
session_start();
ob_start();
require "inc/connection.php";

if (!isset($_SESSION['otp_email'])) {
    header('Location: forgot.php');
    exit();
}

$errors = array();

if (isset($_POST['reset_password'])) {
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['otp_email'];

    // Validate and sanitize input
    $otp = htmlspecialchars(strip_tags($otp));
    $new_password = htmlspecialchars(strip_tags($new_password));
    $confirm_password = htmlspecialchars(strip_tags($confirm_password));

    // Password validation
    if ($new_password !== $confirm_password) {
        $errors['password'] = "Passwords do not match!";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$#@])[A-Za-z\d$#@]{6,8}$/', $new_password)) {
        $errors['password'] = "Password must be 6-8 characters long, contain at least one number, one letter, and one special character ($, #, @).";
    } else {
        // Check OTP
        $otp_check = oci_parse($conn, "SELECT * FROM customer WHERE email = :email AND otp = :otp");
        oci_bind_by_name($otp_check, ":email", $email);
        oci_bind_by_name($otp_check, ":otp", $otp);
        oci_execute($otp_check);

        if (oci_fetch($otp_check) > 0) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update password in the database
            $update_password = oci_parse($conn, "UPDATE customer SET password = :password, otp = NULL WHERE email = :email");
            oci_bind_by_name($update_password, ":password", $hashed_password);
            oci_bind_by_name($update_password, ":email", $email);
            oci_execute($update_password);

            if (oci_execute($update_password)) {
                unset($_SESSION['otp_email']);
                echo "<script>alert('Password reset successful'); window.location.href='login.php';</script>";
                exit();
            } else {
                $errors['db-error'] = "Failed to update password in database!";
            }
        } else {
            $errors['otp-error'] = "Invalid OTP!";
        }
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    <div class="login">
        <div class="container">
            <div class="section-header">
                <h3>Reset Password</h3>
                <p>Please enter the OTP sent to your email and your new password.</p>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12">
                        <label>OTP</label>
                        <input name="otp" class="form-control" type="text" placeholder="Enter OTP" required>
                    </div>
                    <div class="col-md-12">
                        <label>New Password</label>
                        <input name="new_password" class="form-control" type="password" placeholder="Enter New Password" required>
                    </div>
                    <div class="col-md-12">
                        <label>Confirm New Password</label>
                        <input name="confirm_password" class="form-control" type="password" placeholder="Confirm New Password" required>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" name="reset_password" class="btn shadow-none btn-lg mb-3">Reset Password</button>
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
