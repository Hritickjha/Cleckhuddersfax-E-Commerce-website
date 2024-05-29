<?php
session_start();
include('inc/connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the item already exists in the wishlist
    $query = "SELECT * FROM wishlist WHERE USER_ID = :user_id AND PRODUCT_ID = :product_id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':user_id', $user_id);
    oci_bind_by_name($stmt, ':product_id', $product_id);
    oci_execute($stmt);

    if (!oci_fetch_assoc($stmt)) { // If the item does not exist in the wishlist, add it
        $insert_query = "INSERT INTO wishlist (USER_ID, PRODUCT_ID) VALUES (:user_id, :product_id)";
        $insert_stmt = oci_parse($conn, $insert_query);
        oci_bind_by_name($insert_stmt, ':user_id', $user_id);
        oci_bind_by_name($insert_stmt, ':product_id', $product_id);

        if (oci_execute($insert_stmt)) {
            // Wishlist item added successfully
            header('Location: wishlist.php');
            exit;
        } else {
            // Error occurred while adding to the wishlist
            echo "Error: Unable to add to wishlist.";
        }
    } else {
        // Item already exists in the wishlist
        echo "Item already exists in the wishlist.";
    }
} else {
    // Product ID not provided
    echo "Error: Product ID not provided.";
}

oci_close($conn);
?>
