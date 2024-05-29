<?php
session_start();
include('inc/connection.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $keep_signed_in = isset($_POST['keep_signed_in']);

    if (!$keep_signed_in) {
        $error = "Please check the box";
    } else {
        $query = "SELECT * FROM customer WHERE email=:email";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':email', $email);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);

        if ($row) {
            if ($row['STATUS'] === 'active') {
                if (password_verify($password, $row['PASSWORD'])) {
                    $_SESSION['user_id'] = $row['CUSTOMER_ID'];  // Assuming user_id is the primary key
                    $_SESSION['name'] = $row['FIRSTNAME'];
                    $_SESSION['lastname'] = $row['LASTNAME'];
                    $_SESSION['email'] = $row['EMAIL'];
                    $_SESSION['phone'] = $row['PHONE_NUMBER'];
                    $_SESSION['address'] = $row['ADDRESS'];
                    $_SESSION['profile_image'] = 'customer_img/' . $row['PROFILE'];
                    echo "<script>alert('Login successful'); window.location.href='index.php';</script>";
                    exit;
                } else {
                    $error = "Invalid password";
                }
            } elseif ($row['STATUS'] === 'inactive') {
                $_SESSION['email'] = $email;
                header('Location: verify_otp.php');
                exit;
            }
        } else {
            $error = "No account found with this email";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    <!-- Login Form Start -->
    <div class="login">
        <div class="container">
            <div class="section-header">
                <h3>Login</h3>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <label>E-mail</label>
                        <input name="email" class="form-control" type="email" placeholder="Enter Email" required>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <label>Password</label>
                        <input name="password" class="form-control" type="password" placeholder="Enter Password" required>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="keep_signed_in" class="custom-control-input shadow-none" id="newaccount">
                            <label class="custom-control-label shadow-none" for="newaccount">Keep me signed in</label><br>
                            <a href="forgot.php">Forgot Password</a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" name="login" class="btn shadow-none btn-lg mb-3">Login</button>
                        <?php if (!empty($error)): ?>
                            <p class="text-danger"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Login Form End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/slick/slick.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
