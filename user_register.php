<?php
session_start();
ob_start();
require "inc/connection.php";

$errors = array();

function sendOTP($email, $otp) {
    $subject = 'OTP Verification';
    $message = "Your OTP for verification is: $otp";
    $headers = "From: msnausad678@gmail.com\r\n";
    $headers .= "Reply-To: msnausad678@gmail.com\r\n";

    return mail($email, $subject, $message, $headers);
}

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_num'];
    $address = $_POST['address'];

    // Validate and sanitize input
    $fname = htmlspecialchars(strip_tags($fname));
    $lname = htmlspecialchars(strip_tags($lname));
    $email = htmlspecialchars(strip_tags($email));
    $password = htmlspecialchars(strip_tags($password));
    $phone_number = htmlspecialchars(strip_tags($phone_number));
    $address = htmlspecialchars(strip_tags($address));

    // Password validation
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$#@])[A-Za-z\d$#@]{6,8}$/', $password)) {
        $errors['password'] = "Password must be 6-8 characters long, contain at least one number, one letter, and one special character ($, #, @).";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $email_check = oci_parse($conn, "SELECT * FROM customer WHERE email = :email");
    oci_bind_by_name($email_check, ":email", $email);
    oci_execute($email_check);

    if (oci_fetch($email_check) > 0) {
        $errors['email'] = "Email already exists!";
    }

    // Check if first name and last name combination already exists
    $name_check = oci_parse($conn, "SELECT * FROM customer WHERE firstname = :fname AND lastname = :lname");
    oci_bind_by_name($name_check, ":fname", $fname);
    oci_bind_by_name($name_check, ":lname", $lname);
    oci_execute($name_check);

    if (oci_fetch($name_check) > 0) {
        $errors['name'] = "First name and Last name combination already exists!";
    }

    // Handle file upload
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "customer_img/";
        $profile_image = basename($_FILES['profile']['name']);
        $target_file = $target_dir . $profile_image;

        // Check if file is an image
        $check = getimagesize($_FILES['profile']['tmp_name']);
        if ($check !== false) {
            if (!move_uploaded_file($_FILES['profile']['tmp_name'], $target_file)) {
                $errors['profile'] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors['profile'] = "File is not an image.";
        }
    } else {
        $profile_image = null;
    }

    if (count($errors) === 0) {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Insert data into customer table
        $insert_customer = oci_parse($conn, "INSERT INTO customer (firstname, lastname, email, password, phone_number, address, profile, status, otp) 
                                           VALUES (:fname, :lname, :email, :password, :phone_number, :address, :profile, 'inactive', :otp)");
        oci_bind_by_name($insert_customer, ":fname", $fname);
        oci_bind_by_name($insert_customer, ":lname", $lname);
        oci_bind_by_name($insert_customer, ":email", $email);
        oci_bind_by_name($insert_customer, ":password", $hashed_password);
        oci_bind_by_name($insert_customer, ":phone_number", $phone_number);
        oci_bind_by_name($insert_customer, ":address", $address);
        oci_bind_by_name($insert_customer, ":profile", $profile_image);
        oci_bind_by_name($insert_customer, ":otp", $otp);

        $execute_customer = oci_execute($insert_customer);

        if ($execute_customer) {
            if (sendOTP($email, $otp)) {
                $_SESSION['registration_success'] = true; // Set session variable for successful registration
                $_SESSION['email'] = $email;
                header('Location: verify_otp.php'); // Redirect to verify_otp.php
                exit();
            } else {
                $errors['mail-error'] = "Failed to send OTP email.";
            }
        } else {
            $errors['db-error'] = "Failed to insert data into database!";
        }
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <?php include('inc/link.php'); ?>
    <script>
        // Function to display password suggestions
        function showPasswordSuggestions() {
            var passwordInput = document.getElementById("password");
            var suggestions = document.getElementById("password-suggestions");

            // Check if password input is not empty
            if (passwordInput.value.length > 0) {
                // Validate password using regex
                var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$#@])[A-Za-z\d$#@]{6,8}$/;
                if (regex.test(passwordInput.value)) {
                    suggestions.innerHTML = "Password meets criteria";
                    suggestions.style.color = "green";
                } else {
                    suggestions.innerHTML = "Password must be 6-8 characters long, contain at least one number, one letter, and one special character ($, #, @).";
                    suggestions.style.color = "red";
                }
            } else {
                suggestions.innerHTML = "";
            }
        }
    </script>
</head>

<body>
    <?php include('inc/header.php'); ?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">User</a></li>
                <li class="breadcrumb-item active">Login</li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Login Start -->
    <div class="login">
        <div class="container">
            <div class="section-header">
                <h3>User Registration</h3>
                <p>
                    Please register first to use the website.
                </p>
            </div>
            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">';
                foreach ($errors as $error) {
                    echo '<p>' . $error . '</p>';
                }
                echo '</div>';
            }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <label>First Name</label>
                        <input name="fname" class="form-control" type="text" placeholder="First Name" required>
                    </div>
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <label>Last Name</label>
                        <input name="lname" class="form-control" type="text" placeholder="Last Name" required>
                    </div>
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <label>Email</label>
                        <input name="email" class="form-control" type="email" placeholder="your@gmail.com" required>
                    </div>
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <label>Password</label>
                        <input id="password" name="password" class="form-control" type="password" placeholder="password" onkeyup="showPasswordSuggestions()" required>
                        <small id="password-suggestions"></small>
                    </div>
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <label>Profile</label>
                        <input name="profile" class="form-control" type="file">
                    </div>
                    <div class="col-md-12 col-lg-6 col-sm-12">
                        <label>Phone Number</label>
                        <input name="phone_num" class="form-control" type="number" placeholder="phone number" required>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <label>Address</label>
                        <input name="address" class="form-control" type="text" placeholder="Address" required>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" name="submit" class="btn shadow-none btn-lg mb-3">Submit</button>
                        <button class="btn shadow-none btn-lg mb-3"><a href="login.php" class="dropdown-item">Login</a></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Login End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/slick/slick.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>