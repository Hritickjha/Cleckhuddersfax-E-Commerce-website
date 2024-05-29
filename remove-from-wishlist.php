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
    // Remove product from fav_product table
    $query = "DELETE FROM fav_product WHERE CUSTOMER_ID = :customer_id AND ADD_PRODUCT_ID = :product_id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':customer_id', $customer_id);
    oci_bind_by_name($stmt, ':product_id', $product_id);
    oci_execute($stmt);
}

// Redirect back to the wishlist page
header("Location: wishlist.php");
exit;

oci_close($conn);
?>
