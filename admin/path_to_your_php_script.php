<?php
// Database connection
include('inc/connection.php');

// Check if connection is successful
if (!$conn) {
    $e = oci_error();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . htmlentities($e['message'])]);
    exit;
}

// Query to fetch the necessary data
$query = "SELECT t.FULL_NAME, SUM(od.QUANTITY_ORDERED) AS total_quantity
          FROM ORDERDETAIL od
          JOIN PRODUCTS p ON od.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID
          JOIN TRADER t ON p.TRADER_ID = t.TRADER_ID
          GROUP BY t.FULL_NAME";

$stid = oci_parse($conn, $query);

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
