<?php
session_start();

// Check if the product ID is passed
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$product_id]);
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit();
} else {
    // If the product ID is not set, redirect back to the cart page
    header("Location: cart.php");
    exit();
}
?>
