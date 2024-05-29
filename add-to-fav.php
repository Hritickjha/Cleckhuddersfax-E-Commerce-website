<?php
session_start();
include('inc/connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve customer ID from session
$customer_id = $_SESSION['user_id'];

// Retrieve product ID from GET parameters
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id) {
    // Check if the product is already in the wishlist
    $checkQuery = "SELECT * FROM fav_product WHERE CUSTOMER_ID = :customer_id AND ADD_PRODUCT_ID = :product_id";
    $checkStmt = oci_parse($conn, $checkQuery);
    oci_bind_by_name($checkStmt, ':customer_id', $customer_id);
    oci_bind_by_name($checkStmt, ':product_id', $product_id);
    oci_execute($checkStmt);

    if (!oci_fetch($checkStmt)) {
        // Insert product into fav_product table
        $query = "INSERT INTO fav_product (CUSTOMER_ID, ADD_PRODUCT_ID) VALUES (:customer_id, :product_id)";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':customer_id', $customer_id);
        oci_bind_by_name($stmt, ':product_id', $product_id);
        oci_execute($stmt);
    }
}

// Redirect back to the product list or previous page
header("Location: product-list.php");
exit;

oci_close($conn);
?>
