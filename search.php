<?php
session_start();
include('inc/connection.php');

if (isset($_GET['query'])) {
    $search_query = htmlspecialchars($_GET['query']);
    $words = explode(' ', $search_query);

    if (count($words) > 0) {
        $query = "SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRICE, IMAGE FROM products WHERE ";
        
        $conditions = [];
        foreach ($words as $index => $word) {
            $conditions[] = "UPPER(PRODUCT_NAME) LIKE '%' || UPPER(:word_$index) || '%'";
        }
        
        $query .= implode(' OR ', $conditions);

        $stmt = oci_parse($conn, $query);

        foreach ($words as $index => $word) {
            oci_bind_by_name($stmt, ":word_$index", $words[$index]);
        }

        oci_execute($stmt);

        $results_found = false;
        $output = '<div class="container row align-items-center mt-3 mb-3 m-0 p-0">';
        
        while ($row = oci_fetch_assoc($stmt)) {
            $results_found = true;
            $image_name = $row['IMAGE'];
            $image_path = "images/" . $image_name;

            $output .= '
            <div class="col-lg-4 col-md-3">
                <div class="product-item">
                    <div class="product-image">
                        <a href="product-detail.php?id='.$row['ADD_PRODUCT_ID'].'">
                            <img src="' . $image_path . '" alt="Product Image" class="w-100 img-fluid">
                        </a>
                        <div class="product-action">
                            <a href="add-to-cart.php?id=' . $row['ADD_PRODUCT_ID'] . '"><i class="fa fa-cart-plus"></i></a>
                            <a href="#"><i class="fa fa-heart"></i></a>
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="title"><a href="product-detail.php?id='.$row['ADD_PRODUCT_ID'].'">' . $row['PRODUCT_NAME'] . '</a></div>
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
            </div>';
        }
        $output .= '</div>';

        if (!$results_found) {
            $output = '<p>No products found matching your search criteria.</p>';
        }
    } else {
        $output = '<p>Invalid search query.</p>';
    }
} else {
    $output = '<p>Invalid search query.</p>';
}

oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <?php include('inc/link.php'); ?>
</head>
<body>
    <?php include('inc/header.php'); ?>
    
    <div class="container mt-5">
        <h2>Search Results</h2>
        <?php echo $output; ?>
    </div>
    
    <?php include('inc/footer.php'); ?>
</body>
</html>
