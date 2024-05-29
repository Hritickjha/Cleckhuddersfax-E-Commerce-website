<?php
session_start();
include('inc/connection.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Start a transaction
    oci_execute(oci_parse($conn, "BEGIN"));

    try {
        // Delete from orderdetail
        $query_orderdetail = "DELETE FROM orderdetail WHERE ADD_PRODUCT_ID = :product_id";
        $stmt_orderdetail = oci_parse($conn, $query_orderdetail);
        oci_bind_by_name($stmt_orderdetail, ':product_id', $product_id);
        if (!oci_execute($stmt_orderdetail, OCI_NO_AUTO_COMMIT)) {
            throw new Exception(oci_error($stmt_orderdetail)['message']);
        }

        // Delete from cartproduct
        $query_cartproduct = "DELETE FROM cartproduct WHERE ADD_PRODUCT_ID = :product_id";
        $stmt_cartproduct = oci_parse($conn, $query_cartproduct);
        oci_bind_by_name($stmt_cartproduct, ':product_id', $product_id);
        if (!oci_execute($stmt_cartproduct, OCI_NO_AUTO_COMMIT)) {
            throw new Exception(oci_error($stmt_cartproduct)['message']);
        }

        // Delete from fav_product
        $query_fav_product = "DELETE FROM fav_product WHERE ADD_PRODUCT_ID = :product_id";
        $stmt_fav_product = oci_parse($conn, $query_fav_product);
        oci_bind_by_name($stmt_fav_product, ':product_id', $product_id);
        if (!oci_execute($stmt_fav_product, OCI_NO_AUTO_COMMIT)) {
            throw new Exception(oci_error($stmt_fav_product)['message']);
        }

        // Delete from cart (optional, if needed, otherwise ignore this section)
        $query_cart = "DELETE FROM cart WHERE CART_ID IN (SELECT CART_ID FROM cartproduct WHERE ADD_PRODUCT_ID = :product_id)";
        $stmt_cart = oci_parse($conn, $query_cart);
        oci_bind_by_name($stmt_cart, ':product_id', $product_id);
        if (!oci_execute($stmt_cart, OCI_NO_AUTO_COMMIT)) {
            throw new Exception(oci_error($stmt_cart)['message']);
        }

        // Finally, delete from products
        $query_products = "DELETE FROM products WHERE ADD_PRODUCT_ID = :product_id";
        $stmt_products = oci_parse($conn, $query_products);
        oci_bind_by_name($stmt_products, ':product_id', $product_id);
        if (!oci_execute($stmt_products, OCI_NO_AUTO_COMMIT)) {
            throw new Exception(oci_error($stmt_products)['message']);
        }

        // Commit the transaction
        oci_commit($conn);
        header('Location: view_product.php?id=' . $_GET['trader_id']);
        exit;

    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        oci_rollback($conn);
        // Check if the error is due to a foreign key constraint
        if (strpos($e->getMessage(), 'ORA-02292') !== false) {
            echo "<script>alert('You cannot delete the product because it has been bought by users.'); window.location.href = 'view_product.php?id=" . $_GET['trader_id'] . "';</script>";
        } else {
            echo "Error executing query: " . htmlentities($e->getMessage());
        }
        exit;
    } finally {
        // Free the statements
        oci_free_statement($stmt_orderdetail);
        oci_free_statement($stmt_cartproduct);
        oci_free_statement($stmt_fav_product);
        oci_free_statement($stmt_cart);
        oci_free_statement($stmt_products);
        // Close the connection
        oci_close($conn);
    }

} else {
    echo "Invalid Product ID.";
    exit;
}
?>
