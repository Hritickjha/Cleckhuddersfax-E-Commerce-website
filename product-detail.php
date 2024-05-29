<?php
include('inc/connection.php');
session_start();

// Redirect to login page if the user is not logged in and is attempting to submit a review
if (isset($_POST['submit_review']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_review'])) {
    $customer_id = $_SESSION['user_id'];
    $product_id = $_GET['id'];
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $review = htmlspecialchars($_POST['review'], ENT_QUOTES, 'UTF-8');
    $rating = (int)$_POST['rating'];

    $query = "INSERT INTO userreview (CUSTOMER_ID, ADD_PRODUCT_ID, NAME, EMAIL, REVIEW, RATING, STATUS) 
              VALUES (:customer_id, :product_id, :name, :email, :review, :rating, 'active')";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':customer_id', $customer_id);
    oci_bind_by_name($stmt, ':product_id', $product_id);
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':review', $review);
    oci_bind_by_name($stmt, ':rating', $rating);

    if (oci_execute($stmt)) {
        echo "<script>alert('Review submitted successfully. It will be visible after approval by the Admin.');
        window.location = 'product-list.php';
        </script>";
    } else {
        echo "<script>alert('Error submitting review. Please try again.');</script>";
    }
}

// Fetch product details
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, STOCK_AVAILABLE, MIN_ORDER, MAX_ORDER, ALLERGY_INFO 
              FROM products WHERE ADD_PRODUCT_ID = :id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':id', $product_id);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    if ($row) {
        $product_name = $row['PRODUCT_NAME'];
        $product_desc = $row['PRODUCT_DESC'];
        $price = $row['PRICE'];
        $image_name = $row['IMAGE'];
        $image_path = "images/" . $image_name;
        $available = $row['STOCK_AVAILABLE'];
        $min_order = $row['MIN_ORDER'];
        $max_order = $row['MAX_ORDER'];
        $allergy = $row['ALLERGY_INFO'];
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Close database connection
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product Detail</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <meta content="Bootstrap Ecommerce" name="description">
    <?php include('inc/link.php'); ?>
</head>
<body>
    <?php include('inc/header.php'); ?>

    <!-- Product Detail Start -->
    <div class="product-detail">
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
                            <img src="images/salad.png" alt="Image">
                        </a>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row align-items-center product-detail-top">
                        <div class="col-md-5">
                            <div class="product-slider-single">
                                <img src="<?php echo $image_path ?>" alt="Product Image">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="product-content">
                                <div class="title">
                                    <h2><?php echo $product_name; ?></h2>
                                </div>
                                <div class="ratting">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="price">$<?php echo $price; ?> <span>$25</span></div>
                                <div class="details">
                                    <p><?php echo $product_desc; ?></p>
                                </div>
                                <div class="mt-3">
                                    <a href="add-to-cart.php?id=<?php echo $row['ADD_PRODUCT_ID']; ?>"><button class="btn btn-success btn-sm">Add to Cart</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row product-detail-bottom">
                        <div class="col-lg-12">
                            <ul class="nav nav-pills nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="pill" href="#description">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#specification">Allergy Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#reviews">Reviews</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="description" class="container tab-pane active"><br>
                                    <h4>Product description</h4>
                                    <p><?php echo $product_desc; ?></p>
                                </div>
                                <div id="specification" class="container tab-pane fade"><br>
                                    <h4>Allergy Description</h4>
                                    <p><?php echo $allergy; ?></p>
                                </div>
                                <div id="reviews" class="container tab-pane fade"><br>
                                    <div class="reviews-submitted">
                                        <div class="reviewer">CapitalTradeHub - <span>01 Jan 2024</span></div>
                                        <div class="ratting">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
                                    </div>
                                    <div class="reviews-submit">
                                        <h4>Give your Review:</h4>
                                        <form action="" method="post">
                                            <div class="ratting">
                                                <input type="radio" name="rating" value="1"><i class="fa fa-star-o"></i>
                                                <input type="radio" name="rating" value="2"><i class="fa fa-star-o"></i>
                                                <input type="radio" name="rating" value="3"><i class="fa fa-star-o"></i>
                                                <input type="radio" name="rating" value="4"><i class="fa fa-star-o"></i>
                                                <input type="radio" name="rating" value="5"><i class="fa fa-star-o"></i>
                                            </div>
                                            <div class="row form">
                                                <div class="col-sm-6">
                                                    <input type="text" name="name" placeholder="Name" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="email" name="email" placeholder="Email" required>
                                                </div>
                                                <div class="col-sm-12">
                                                    <textarea name="review" placeholder="Review" required></textarea>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button type="submit" name="submit_review">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center product-slider product-slider-3">
                        <!-- Related product items here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Detail End -->

    <?php include('inc/footer.php'); ?>
</body>
</html>
