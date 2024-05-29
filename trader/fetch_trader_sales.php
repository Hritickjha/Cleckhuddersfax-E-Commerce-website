<?php
session_start();
include('inc/connection.php');

if (!isset($_SESSION['trader_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$trader_id = $_SESSION['trader_id'];

// Helper function to get sales data
function getSalesData($conn, $trader_id, $interval) {
    $query = "
        SELECT p.PRODUCT_NAME, SUM(od.QUANTITY_ORDERED) AS TOTAL_QUANTITY, TO_CHAR(od.ORDER_DATE, '$interval') AS TIME_PERIOD
        FROM orderdetail od
        JOIN products p ON od.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID
        WHERE p.TRADER_ID = :trader_id
        GROUP BY p.PRODUCT_NAME, TO_CHAR(od.ORDER_DATE, '$interval')
        ORDER BY TIME_PERIOD, TOTAL_QUANTITY DESC";
    
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':trader_id', $trader_id);
    oci_execute($stmt);
    
    $salesData = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $timePeriod = $row['TIME_PERIOD'];
        if (!isset($salesData[$timePeriod])) {
            $salesData[$timePeriod] = [];
        }
        $salesData[$timePeriod][] = $row;
    }
    
    oci_free_statement($stmt);
    return $salesData;
}

// Fetch sales data
$weeklyData = getSalesData($conn, $trader_id, 'IW');
$monthlyData = getSalesData($conn, $trader_id, 'MM');
$yearlyData = getSalesData($conn, $trader_id, 'YYYY');

echo json_encode(['weekly' => $weeklyData, 'monthly' => $monthlyData, 'yearly' => $yearlyData]);
?>
