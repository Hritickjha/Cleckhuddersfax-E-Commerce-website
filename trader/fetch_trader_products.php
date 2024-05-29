<?php
session_start();
include('inc/connection.php');

if (!isset($_SESSION['trader_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$trader_id = $_SESSION['trader_id'];

// Prepare the query to fetch stock available details for the logged-in trader
$query = "
    SELECT p.PRODUCT_NAME, p.STOCK_AVAILABLE
    FROM products p
    WHERE p.TRADER_ID = :trader_id
    ORDER BY p.STOCK_AVAILABLE DESC";

$stmt = oci_parse($conn, $query);
oci_bind_by_name($stmt, ':trader_id', $trader_id);
oci_execute($stmt);

$products = [];
while ($row = oci_fetch_assoc($stmt)) {
    $products[] = $row;
}

oci_free_statement($stmt);

echo json_encode($products);
?>
