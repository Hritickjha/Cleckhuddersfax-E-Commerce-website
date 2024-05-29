<?php 
session_start();
include('inc/connection.php');

// Define how many results you want per page
$results_per_page = 9;

// Find out the number of results stored in database
$query = "SELECT COUNT(*) AS total FROM products";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);
$number_of_results = $row['TOTAL'];

// Determine the total number of pages available
$number_of_pages = ceil($number_of_results / $results_per_page);

// Determine which page number visitor is currently on
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
} elseif ($page > $number_of_pages) {
    $page = $number_of_pages;
}

// Determine the starting number for the results on the displaying page
$this_page_first_result = ($page - 1) * $results_per_page + 1;
$this_page_last_result = $page * $results_per_page;

// Retrieve selected results from database using Oracle pagination
$query = "SELECT * FROM (
            SELECT a.*, ROWNUM rnum FROM (
                SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRICE, IMAGE FROM products ORDER BY PRICE ASC
            ) a WHERE ROWNUM <= $this_page_last_result
        ) WHERE rnum >= $this_page_first_result";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);

$products = [];
while ($row = oci_fetch_assoc($stmt)) {
    $products[] = $row;
}

// Close database connection
oci_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>product-list</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce Template" name="keywords">
    <meta content="Bootstrap Ecommerce" name="description">
    <?php include('inc/link.php') ?>
</head>

<body>

    <?php include('inc/header.php') ?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Products</a></li>
                <li class="breadcrumb-item active">Product List</li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product List Start -->
    <div class="product-view">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sidebar-widget category">
                        <h2 class="title">Category</h2>
                        <ul>
                            <li><a href="product-list.php">All Products</a></li>
                            <li><a href="butcher.php">Butcher</a></li>
                            <li><a href="fishmonger.php">Fishmonger</a></li>
                            <li><a href="bakery.php">Bakery</a></li>
                            <li><a href="greengrocer.php">Greengrocer</a></li>
                            <li><a href="delicatessen.php">Delicatessen</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-widget image">
                        <h2 class="title">Featured Product</h2>
                        <a href="#">
                            <img src="images/grape.png" alt="Image">
                        </a>
                    </div>

                    <div class="sidebar-widget tag">
                        <h2 class="title">Tags</h2>
                        <a href="#">#food</a>
                        <a href="#">#e-commerce</a>
                        <a href="#">#fruits</a>
                        <a href="#">#vegetables</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-8">
                                <form action="search.php" method="get">
                                    <div class="product-search">
                                        <input type="search" name="query" id="searchInput" placeholder="Search Product">
                                        <button><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                                </div>
                                <div class="col-md-4">
                                    <div class="product-short">
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sort by</a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" class="dropdown-item">Newest</a>
                                                <a href="#" class="dropdown-item">Popular</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Display products
                        foreach ($products as $product) {
                            $image_name = $product['IMAGE'];
                            $image_path = "images/" . $image_name; // Assuming images are stored in 'images' folder

                            echo '
                                <div class="col-lg-4 col-md-6">
                                    <div class="product-item">
                                        <div class="product-image">
                                            <a href="product-detail.php?id=' . $product['ADD_PRODUCT_ID'] . '">
                                                <img src="' . $image_path . '" alt="Product Image">
                                            </a>
                                            <div class="product-action">
                                                <a href="add-to-cart.php?id=' . $product['ADD_PRODUCT_ID'] . '"><i class="fa fa-cart-plus"></i></a>
                                                <a href="add-to-fav.php?id=' . $product['ADD_PRODUCT_ID'] . '"><i class="fa fa-heart"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="title"><a href="product-detail.php?id=' . $product['ADD_PRODUCT_ID'] . '">' . $product['PRODUCT_NAME'] . '</a></div>
                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <div class="price">$' . $product['PRICE'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                        ?>
                    </div>

                    <div class="col-lg-12">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <?php
                                // Display the links to the pages
                                for ($page_num = 1; $page_num <= $number_of_pages; $page_num++) {
                                    echo '<li class="page-item ' . ($page_num == $page ? 'active' : '') . '"><a class="page-link" href="product-list.php?page=' . $page_num . '">' . $page_num . '</a></li> ';
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Product List End -->

    <?php include('inc/footer.php') ?>

</body>

</html>
