<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Wishlist</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <meta content="Bootstrap Ecommerce" name="description">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-wrap">
        <div class="container">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="product-list.php">Products</a></li>
                <li class="breadcrumb-item active">Wishlist</li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Wishlist Start -->
    <div class="cart-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Add to Cart</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                <?php
                                include('inc/connection.php');

                                // Check if user is logged in
                                if (!isset($_SESSION['user_id'])) {
                                    echo "<script>alert('Please register first to use the service.');window.location = 'user_register.php';</script>";

                                    exit;
                                }

                                $customer_id = $_SESSION['user_id'];

                                // Retrieve wishlist items for the logged-in user
                                $query = "SELECT p.IMAGE, p.PRODUCT_NAME, p.PRICE, f.ADD_PRODUCT_ID 
                                          FROM fav_product f 
                                          JOIN products p ON f.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID 
                                          WHERE f.CUSTOMER_ID = :customer_id";
                                $stmt = oci_parse($conn, $query);
                                oci_bind_by_name($stmt, ':customer_id', $customer_id);
                                oci_execute($stmt);

                                // Display wishlist items
                                while ($row = oci_fetch_assoc($stmt)) {
                                    $image_path = "images/" . $row['IMAGE']; // Assuming images are stored in 'images' folder

                                    echo '
                                    <tr>
                                        <td><a href="product-detail.php?id=' . $row['ADD_PRODUCT_ID'] . '"><img src="' . $image_path . '" alt="Product Image"></a></td>
                                        <td><a href="product-detail.php?id=' . $row['ADD_PRODUCT_ID'] . '">' . $row['PRODUCT_NAME'] . '</a></td>
                                        <td>$' . $row['PRICE'] . '</td>
                                        <td><a href="add-to-cart.php?id=' . $row['ADD_PRODUCT_ID'] . '"><button>Add to Cart</button></a></td>
                                        <td><a href="remove-from-wishlist.php?id=' . $row['ADD_PRODUCT_ID'] . '"><button><i class="fa fa-trash"></i></button></a></td>
                                    </tr>';
                                }

                                // Close database connection
                                oci_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wishlist End -->

    <?php include('inc/footer.php'); ?>
</body>
</html>
