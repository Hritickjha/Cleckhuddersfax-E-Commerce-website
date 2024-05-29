<?php
session_start();
include('inc/connection.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $trader_id = $_GET['id'];

    $query = "SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRODUCT_DESC, PRICE, IMAGE, STOCK_AVAILABLE, ALLERGY_INFO, DATE_ADDED 
              FROM products 
              WHERE TRADER_ID = :trader_id";
    
    $data = oci_parse($conn, $query);
    oci_bind_by_name($data, ':trader_id', $trader_id);
    oci_execute($data);
} else {
    echo "Invalid Trader ID.";
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trader Products</title>
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
            <?php
include('inc/connection.php');

// Query to calculate the sum of total price in orderdetail table for products with TRADER_ID = 1
$query = "
    SELECT SUM(od.PRICE) AS total_price
    FROM orderdetail od
    JOIN products p ON od.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID
    WHERE p.TRADER_ID = 1
";

// Prepare and execute the query
$stmt = oci_parse($conn, $query);
if (!$stmt) {
    $e = oci_error($conn);
    echo "Error parsing query: " . htmlentities($e['message']);
    exit;
}

$r = oci_execute($stmt);
if (!$r) {
    $e = oci_error($stmt);
    echo "Error executing query: " . htmlentities($e['message']);
    exit;
}

// Fetch the result
$row = oci_fetch_assoc($stmt);
$total_price = $row['TOTAL_PRICE'];

// Display the total price
echo '

<h1 class="mt-3 text-bold mb-3">total sells  $'.htmlspecialchars($total_price).'</h1>;
';
// Free the statement and close the connection
oci_free_statement($stmt);
oci_close($conn);
?>

                <?php
                while ($result = oci_fetch_assoc($data)) {
                    echo '
                    <div class="card mb-4 border-0 shadow m-0 p-0">
                        <div class="row g-0 p-3 align-items-center">
                            <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                <img src="../images/' . htmlspecialchars($result['IMAGE']) . '" class="img-fluid rounded w-100">
                            </div>
                            <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                <h5>Product Name</h5>
                                <p>' . htmlspecialchars($result['PRODUCT_NAME']) . '</p>
                                <div class="features mb-1 mt-3">
                                    <h6 class="mb-3">Product Description</h6>
                                    <p class="m-0 p-0">' . htmlspecialchars($result['PRODUCT_DESC']) . '</p>
                                </div>
                                <div class="facilities mb-3">
                                    <h6 class="mb-1">Stock Available</h6>
                                    <p>' . htmlspecialchars($result['STOCK_AVAILABLE']) . '</p>
                                </div>
                                <div class="facilities mb-3">
                                    <h6 class="mb-1">Allergy Info</h6>
                                    <p>' . htmlspecialchars($result['ALLERGY_INFO']) . '</p>
                                </div>
                                <div class="facilities mb-3">
                                    <h6 class="mb-1">Added Date</h6>
                                    <p>' . htmlspecialchars($result['DATE_ADDED']) . '</p>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <h6 class="mb-4">Price $' . htmlspecialchars($result['PRICE']) . '</h6>
                                <a href="delete_product.php?id=' . htmlspecialchars($result['ADD_PRODUCT_ID']) . '&trader_id=' . $trader_id . '" class="btn btn-sm text-white btn-danger shadow-none">Delete</a>
                                <a href="manage_trader.php" class="btn btn-sm btn-outline-dark shadow-none bg-dark text-white">Traders</a>
                                
                            </div>
                        </div>
                    </div>';
                }

                if (oci_num_rows($data) === 0) {
                    echo "<p>No products found for this trader.</p>";
                }

                // Close connection
                oci_free_statement($data);
                oci_close($conn);
                ?>
            </div>
        </div>
    </div>
    <?php include('inc/footer.php'); ?>
</body>
</html>
