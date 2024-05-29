<?php
// Start the session
session_start();

// Database connection
include('inc/connection.php');

// Check if the user is logged in
if (!isset($_SESSION['trader_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$trader_id = $_SESSION['trader_id'];

// Check if connection is successful
if (!$conn) {
    $e = oci_error();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . htmlentities($e['message'])]);
    exit;
}

// Query to fetch the necessary data
$query = "SELECT p.PRODUCT_NAME, SUM(od.QUANTITY_ORDERED) AS total_quantity
          FROM ORDERDETAIL od
          JOIN PRODUCTS p ON od.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID
          JOIN TRADER t ON p.TRADER_ID = t.TRADER_ID
          WHERE t.TRADER_ID = :trader_id
          GROUP BY p.PRODUCT_NAME";

$stid = oci_parse($conn, $query);

// Bind the trader_id to the query
oci_bind_by_name($stid, ':trader_id', $trader_id);

// Check if query execution is successful
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Query execution failed: ' . htmlentities($e['message'])]);
    oci_free_statement($stid);
    oci_close($conn);
    exit;
}

$data = array();
while ($row = oci_fetch_assoc($stid)) {
    $data[] = $row;
}

oci_free_statement($stid);
oci_close($conn);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
