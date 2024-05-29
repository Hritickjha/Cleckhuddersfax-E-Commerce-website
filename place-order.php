<?php
session_start();
include('inc/connection.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paynow'])) {
    $customer_id = $_SESSION['user_id'];
    $products = $_POST['products'];
    $subtotal = $_POST['subtotal'];
    $discount = $_POST['discount'];
    $total_after_discount = $_POST['total_after_discount'];
    $grandtotal = $_POST['grandtotal'];
    $status = 1; // Assuming 1 means order placed

    // Insert each product in the order into the orderdetail table
    foreach ($products as $product_id => $quantity) {
        // Get product details from the database
        $query = "SELECT PRODUCT_NAME, PRICE FROM products WHERE ADD_PRODUCT_ID = :product_id";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':product_id', $product_id);
        oci_execute($stmt);

        if ($row = oci_fetch_assoc($stmt)) {
            $product_name = $row['PRODUCT_NAME'];
            $price = $row['PRICE'];
            $total_price = $price * $quantity;

            // Insert order details
            $insert_query = "INSERT INTO orderdetail (CUSTOMER_ID, QUANTITY_ORDERED, PRICE, STATUS, ADD_PRODUCT_ID, PRODUCT_NAME) 
                             VALUES (:customer_id, :quantity, :total_price, :status, :product_id, :product_name)";
            $insert_stmt = oci_parse($conn, $insert_query);
            oci_bind_by_name($insert_stmt, ':customer_id', $customer_id);
            oci_bind_by_name($insert_stmt, ':quantity', $quantity);
            oci_bind_by_name($insert_stmt, ':total_price', $total_price);
            oci_bind_by_name($insert_stmt, ':status', $status);
            oci_bind_by_name($insert_stmt, ':product_id', $product_id);
            oci_bind_by_name($insert_stmt, ':product_name', $product_name);
            oci_execute($insert_stmt);

            // Get the ORDER_ID of the last inserted row
            $order_id_query = "SELECT orderdetail_seq.currval AS ORDER_ID FROM dual"; // Adjust the sequence name if different
            $order_id_stmt = oci_parse($conn, $order_id_query);
            oci_execute($order_id_stmt);
            $order_id_row = oci_fetch_assoc($order_id_stmt);
            $order_id = $order_id_row['ORDER_ID'];

            // Insert into cart table
            $insert_cart_query = "INSERT INTO cart (CUSTOMER_ID, ORDER_ID) VALUES (:customer_id, :order_id)";
            $insert_cart_stmt = oci_parse($conn, $insert_cart_query);
            oci_bind_by_name($insert_cart_stmt, ':customer_id', $customer_id);
            oci_bind_by_name($insert_cart_stmt, ':order_id', $order_id);
            oci_execute($insert_cart_stmt);

            // Get the CART_ID of the last inserted row
            $cart_id_query = "SELECT cart_seq.currval AS CART_ID FROM dual"; // Adjust the sequence name if different
            $cart_id_stmt = oci_parse($conn, $cart_id_query);
            oci_execute($cart_id_stmt);
            $cart_id_row = oci_fetch_assoc($cart_id_stmt);
            $cart_id = $cart_id_row['CART_ID'];

            // Insert into cartproduct table
            $insert_cartproduct_query = "INSERT INTO cartproduct (CART_ID, ADD_PRODUCT_ID, QUANTITY, TOTAL_PRICE) 
                                         VALUES (:cart_id, :product_id, :quantity, :total_price)";
            $insert_cartproduct_stmt = oci_parse($conn, $insert_cartproduct_query);
            oci_bind_by_name($insert_cartproduct_stmt, ':cart_id', $cart_id);
            oci_bind_by_name($insert_cartproduct_stmt, ':product_id', $product_id);
            oci_bind_by_name($insert_cartproduct_stmt, ':quantity', $quantity);
            oci_bind_by_name($insert_cartproduct_stmt, ':total_price', $total_price);
            oci_execute($insert_cartproduct_stmt);
        }
    }

    // Insert data into collection_slot table
    $collection_insert_query = "INSERT INTO collection_slot (CUSTOMER_ID) VALUES (:customer_id)";
    $collection_insert_stmt = oci_parse($conn, $collection_insert_query);
    oci_bind_by_name($collection_insert_stmt, ':customer_id', $customer_id);
    oci_execute($collection_insert_stmt);

    // Clear the cart after placing the order
    unset($_SESSION['cart']);

    echo "<script>alert('Order placed successfully!');window.location.href = 'product-list.php'</script>";
} else {
    echo "<script>alert('Invalid request');window.location.href = 'cart.php'</script>";
}

oci_close($conn);
?>
