<?php
session_start();
include('inc/connection.php'); // Include your database connection file

// Check if the product ID is passed
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Check if the cart session array exists, if not, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product is already in the cart, increase the quantity
        $_SESSION['cart'][$product_id]++;
    } else {
        // If the product is not in the cart, add it with a quantity of 1
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect back to the product list or any other page
    header("Location: product-list.php");
    exit();
} else {
    // If the product ID is not set, redirect back to the product list
    header("Location: product-list.php");
    exit();
}
?>
