<?php session_start(); ?>
<!doctype html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>map</title>
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
                                <!-- please add map coding this comment under -->

                                <div class="row">
                                        <h3 class="text-upper text-warning">Our Location Map</h3>
                                        <div class="col-lg-12 col-md-12">
                                                <div class="mt-4">
                                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d604175.7587954897!2d-2.9325517514598474!3d53.73497838814979!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48791339c06b18f1%3A0x6517b5a23c63c194!2sWest%20Yorkshire%2C%20UK!5e0!3m2!1sen!2snp!4v1715493382976!5m2!1sen!2snp" width="1100" height="550" style="border:1;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                </div>
                                        </div>
                                </div>
                                <!-- map end -->

                        </div>
                </div>
                <?php include('inc/footer.php'); ?>
</body>

</html>