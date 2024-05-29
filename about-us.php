<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="css/common.css">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>

    <div class="container mt-4">
        <div class="my-5 px-4">
            <h2 class="fw-bold h-font text-center">ABOUT US</h2>
            <div class="h-line bg-dark"></div>
            <p class="text-center mt-3">
                Welcome to Cleckhuddersfax's community driven e-commerce platform, connecting you to our local traders' unique offerings. In a city where independent businesses shine, we've created a digital marketplace blending tradition with convenience.
                Our platform features diverse traders from butchers to bakeries, each offering distinct products that celebrate our area's heritage. With user-friendly navigation and a shared shopping basket, shopping from multiple traders has never been easier.
                For traders, we provide a comprehensive web interface to manage products and access valuable sales insights. Our platform supports community spirit, local business growth, and offers a seamless shopping experience for all.
                Join us in supporting Cleckhuddersfax's local economy and discover the best of our community's offerings online!</p>

        </div>
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                    <h3 class="mb-3">CEO</h3>
                    <p>
                

                    </p>
                </div>
                <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                    <div class="border border-success border-2">
                    <img src="images/about/about.jpeg" class="rounded-4 w-100" style="height: 350px; width: 250px;">
                    </div>
                    
                </div>
            </div>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                            <img src="images/about/hotel.svg" class="w-100">
                            <h4>100+ </h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                            <img src="images/about/customers.svg" class="w-100">
                            <h4>200+ CUSTOMERS</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                            <img src="images/about/rating.svg" class="w-100">
                            <h4>150+ REVIEWS</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                            <img src="images/about/staff.svg" class="w-100">
                            <h4>100+ STAF</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5 px-4">
            <h2 class="fw-bold h-font text-center">OUR TEAM MEMBERS</h2>
            <div class="h-line bg-dark"></div>
            <p class="text-center mt-3">
                These are the wonderful minds behind creation of this e-commerce website. Check them out!
            </p>
        </div>

        <div class="container px-4">
            <!-- Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper mb-5">
                    <div class="swiper-slide bg-white text-center overfolw-didden rounded">
                        <img src="profile pic/277819313_1066468527284910_7080900500345823763_n.png" class="w-100">
                        <h5 class="mt-2">Mohammad</h5>
                    </div>
                    <div class="swiper-slide bg-white text-center overfolw-didden rounded">
                        <img src="profile pic/IMG_2610.png" class="w-100">
                        <h5 class="mt-2">Roshna</h5>
                    </div>
                    <div class="swiper-slide bg-white text-center overfolw-didden rounded">
                        <img src="profile pic/438196717_7513072728762181_9173129149574462614_n.png" class="w-100">
                        <h5 class="mt-2">Kaustubha </h5>
                    </div>
                    <div class="swiper-slide bg-white text-center overfolw-didden rounded">
                        <img src="profile pic/IMG_20240422_203047.png" class="w-100">
                        <h5 class="mt-2">Ashwika</h5>
                    </div>
                   
                    <div class="swiper-slide bg-white text-center overfolw-didden rounded">
                        <img src="profile pic/IMG_5303.png" class="w-100">
                        <h5 class="mt-2">Jevish</h5>
                    </div>
                    <div class="swiper-slide bg-white text-center overfolw-didden rounded">
                        <img src="profile pic/WhatsApp Image 2024-05-23 at 15.25.40_d13725e9.png" class="w-100">
                        <h5 class="mt-2">Hritick</h5>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>


    <?php include('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper(".mySwiper", {
                slidesPerView: 3,
                spaceBetween: 40,
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        });
    </script>


</body>

</html>