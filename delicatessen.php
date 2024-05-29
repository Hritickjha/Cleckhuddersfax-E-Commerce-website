<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Delicatessen</title>
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
                            <li><a href="bakery.php">Butcher</a></li>
                            <li><a href="fishmonger.php">Fishmonger</a></li>
                            <li><a href="bakery.php">Bakery</a></li>
                            <li><a href="greengrocer.php">Greengrocer</a></li>
                            <li><a href="delicatessen.php">Delicatessen</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-widget image">
                        <h2 class="title">Featured Product</h2>
                        <a href="#">
                            <img src="images/almondbutter.png" alt="Image">
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
include('inc/connection.php');

$query = "SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRICE, IMAGE FROM products WHERE TRADER_ID = 62";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);

// Display data using a while loop
while ($row = oci_fetch_assoc($stmt)) {
    $image_name = $row['IMAGE'];
    $image_path = "images/" . $image_name; // Assuming images are stored in 'images' folder

    echo '
        <div class="col-lg-4">
        <div class="product-item">
            <div class="product-image">
                <a href="product-detail.php?id=' . $row['ADD_PRODUCT_ID'] . '">
                    <img src="' . $image_path . '" alt="Product Image">
                </a>
                <div class="product-action">
                    <a href="add-to-cart.php?id=' . $row['ADD_PRODUCT_ID'] . '"><i class="fa fa-cart-plus"></i></a>
                    <a href="add-to-fav.php?id=' . $row['ADD_PRODUCT_ID'] . '"><i class="fa fa-heart"></i></a>
                </div>
            </div>
            <div class="product-content">
                <div class="title"><a href="product-detail.php?id=' . $row['ADD_PRODUCT_ID'] . '">' . $row['PRODUCT_NAME'] . '</a></div>
                <div class="ratting">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <div class="price">$' . $row['PRICE'] . '</div>
            </div>
        </div>
    </div>
    ';
}

// Close database connection
oci_close($conn);
?>

                    </div>

                    <div class="col-lg-12">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
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
