<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email</title>
    <?php include('inc/link.php'); ?>
</head>
<body>
<div class="main-wrapper">
    <div class="header-container fixed-top">
        <!-- top header start -->
        <?php include('inc/header.php'); ?>
        <!-- top header end -->
    </div>

    <!-- side bar start -->
    <?php include('inc/sidebar.php'); ?>
    <!-- side bar end -->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>Send Email</h2>
                    <form method="post">
                        <div class="mb-3">
                            <label for="to" class="form-label">Recipient Email</label>
                            <input type="email" class="form-control" id="to" name="to" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $to = $_POST["to"];
                        $subject = $_POST["subject"];
                        $message = $_POST["message"];
                        $headers = "From: msnausad678@gmail.com\r\n";
                        $headers .= "Reply-To: msnausad678@gmail.com\r\n";

                        $mail_sent = mail($to, $subject, $message, $headers);
                        if($mail_sent) {
                            echo "<div class='alert alert-success' role='alert'>Mail sent successfully to $to</div>";
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>Mail sending failed</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('inc/footer.php'); ?>
</body>
</html>
