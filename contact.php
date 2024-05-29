<?php
session_start();
include('inc/connection.php');

$error = '';
$success_message = '';

if (isset($_POST['submit'])) {
    if (isset($_SESSION['name'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $query = "INSERT INTO customer_query (name, email, subject, message) VALUES (:name, :email, :subject, :message)";
        $stmt = oci_parse($conn, $query);

        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':email', $email);
        oci_bind_by_name($stmt, ':subject', $subject);
        oci_bind_by_name($stmt, ':message', $message);

        if (oci_execute($stmt)) {
            $success_message = "Your query has been submitted successfully.";
        } else {
            $error = "There was an error submitting your query.";
        }
    } else {
        $error = "You are not logged in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <meta content="Bootstrap Ecommerce" name="description">
    <?php include('inc/link.php'); ?>
</head>
<body>
    <?php include('inc/header.php'); ?>
    <div class="breadcrumb-wrap">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Contact Us</li>
            </ul>
        </div>
    </div>

    <div class="contact">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="form">
                        <form action="" method="POST">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input name="name" type="text" class="form-control" placeholder="Your Name" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <input name="email" type="email" class="form-control" placeholder="Your Email" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <input name="subject" type="text" class="form-control" placeholder="Subject" required />
                            </div>
                            <div class="form-group">
                                <textarea name="message" class="form-control" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div>
                                <button type="submit" name="submit">Send Message</button>
                            </div>
                            <?php
                            if (!empty($error)) {
                                echo "<div class='error-message' style='color: red; margin-top: 10px;'>$error</div>";
                            } elseif (!empty($success_message)) {
                                echo "<div class='success-message' style='color: green; margin-top: 10px;'>$success_message</div>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="contact-info">
                        <div class="section-header">
                            <h3>Get in Touch</h3>
                            <p>Have a question, suggestion, or just want to say hello? We'd love to hear from you! Our team is here to assist you with any inquiries or assistance you may need. Feel free to reach out via email, phone, or our online contact form below. Your feedback is invaluable to us as we strive to provide the best possible shopping experience for you.</p>
                        </div>
                        <h4><i class="fa fa-map-marker"></i>West Yorkshire</h4>
                        <h4><i class="fa fa-envelope"></i>cleckhuddersfaxeehub@gmail.com</h4>
                        <h4><i class="fa fa-phone"></i>+977-9819931015</h4>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    

 <?php include('inc/footer.php'); ?>
</body>
</html>
