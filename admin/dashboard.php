<?php
session_start();
$admin_profile = $_SESSION['admin_name'];
if ($admin_profile == true) {
} else {
    header('location: login.php');
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin dashboard</title>
    <?php include('inc/link.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .left-menu .active {
            background-color: green;
            /* Change to your preferred color */
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="header-container fixed-top">

            <!-- top header start -->
            <?php include('inc/header.php'); ?>

            <!-- top header end -->

        </div>

        <!-- navbar end -->

        <!-- side bar start -->
        <?php include('inc/sidebar.php'); ?>
        <!-- side bar end -->


        <div class="content-wrapper">
            <section class="dashboard-top-sec">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="bg-white top-chart-earn">
                                <div class="row">
                                    <div class="col-sm-4 my-2 pe-0">
                                        <div class="last-month">
                                            <h5>Dashboard</h5>
                                            <p>Overview of Latest Month</p>
                                            <?php 
include('inc/connection.php');

// SQL query to fetch all PRICE from orderdetail
$query = 'SELECT PRICE FROM orderdetail';

// Prepare the statement
$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    echo "Failed to prepare SQL statement: " . htmlspecialchars($e['message']);
    exit;
}

// Execute the statement
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    echo "Failed to execute SQL statement: " . htmlspecialchars($e['message']);
    exit;
}

$prices = []; // Array to store PRICE values
$total_price = 0; // Variable to store the sum of all prices

// Fetch the results and store in the array
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $prices[] = $row['PRICE'];
    $total_price += $row['PRICE']; // Add the price to the total
}

// Free the statement identifier and close the connection
oci_free_statement($stid);
oci_close($conn);
?>


                                            <div class="earn">
                                            <h5>$<?php echo htmlspecialchars($total_price); ?></h5>
                                                
                                            </div>
                                            <div class="sale mb-3">
                                            <p>Current monh Sale</p>
                                                <h2>$95</h2>
                                                
                                            </div>
                                            <a href="#" class="di-btn purple-gradient">Last Month Summary</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-8 my-2 ps-0">
        <div class="classic-tabs">
            <!-- nav tabs -->
            <div class="tabs-wrapper">
                <ul class="nav nav-pills chart-header-tab mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link chart-nav active" id="pills-week-tab" data-bs-toggle="pill" data-bs-target="#pills-week" type="button" role="tab" aria-controls="pills-week" aria-selected="true">Week</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link chart-nav" id="pills-month-tab" data-bs-toggle="pill" data-bs-target="#pills-month" type="button" role="tab" aria-controls="pills-month" aria-selected="false">Month</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#" class="nav-link chart-nav" id="pills-year-tab" data-bs-toggle="pill" data-bs-target="#pills-year" type="button" role="tab" aria-controls="pills-year" aria-selected="false">Year</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-week" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div id="containerID">
                            <div>
                                <canvas id="stackedChartID"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div id="containerID2">
                            <div>
                                <canvas id="stackedChartID2"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-year" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div id="containerID1">
                            <div>
                                <canvas id="stackedChartID1"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Function to fetch data and render Chart.js bar chart
    function renderChartJS(canvasID, url) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.PRODUCT_NAME);
                const quantities = data.map(item => item.TOTAL_QUANTITY);

                const ctx = document.getElementById(canvasID).getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Quantity Sold',
                            data: quantities,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Function to fetch data and render ApexCharts donut chart
    function renderApexChart(containerID, url) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.PRODUCT_NAME);
                const series = data.map(item => item.TOTAL_QUANTITY);

                var options = {
                    series: series,
                    chart: {
                        type: 'donut'
                    },
                    labels: labels,
                    responsive: [{
                        breakpoint: 1024,
                        options: {
                            chart: {
                                width: '110px',
                                height: '400px'
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                var chart = new ApexCharts(document.querySelector(`#${containerID}`), options);
                chart.render();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Render charts for each tab
    renderChartJS('stackedChartID', 'fetch_data.php?period=week');
    renderChartJS('stackedChartID2', 'fetch_data.php?period=month');
    renderChartJS('stackedChartID1', 'fetch_data.php?period=year');

    // Optional: Add event listeners to fetch data for each tab when clicked
    document.getElementById('pills-week-tab').addEventListener('click', () => renderChartJS('stackedChartID', 'fetch_data.php?period=week'));
    document.getElementById('pills-month-tab').addEventListener('click', () => renderChartJS('stackedChartID2', 'fetch_data.php?period=month'));
    document.getElementById('pills-year-tab').addEventListener('click', () => renderChartJS('stackedChartID1', 'fetch_data.php?period=year'));

    </script>
                                </div>
                                <div class="wre-sec">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3 col-6 my-1 bdr-cls">
                                            <div class="earn-view">
                                                <span class="fas fa-crown earn-icon wallet"></span>
                                                <div class="earn-view-text">
                                                    <p class="name-text">
                                                        Wallet Ballane
                                                    </p>
                                                    <h6 class="balance-text">
                                                        $1684.54
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-3 col-6 my-1 bdr-cls">
                                            <div class="earn-view">
                                                <span class="fas fa-heart earn-icon referral"></span>
                                                <div class="earn-view-text">
                                                    <p class="name-text">
                                                        Referral Earning
                                                    </p>
                                                    <h6 class="balance-text">
                                                        $1204.54
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-6 my-1 bdr-cls">
                                            <div class="earn-view">
                                                <span class="fab fa-salesforce earn-icon sales"></span>
                                                <div class="earn-view-text">
                                                    <p class="name-text">
                                                        Estimate Sales
                                                    </p>
                                                    <h6 class="balance-text">
                                                        $154.54
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-6 my-1 bdr-cls my-1 bdr-cls">
                                            <div class="earn-view">
                                                <span class="fas fa-chart-line earn-icon earning"></span>
                                                <div class="earn-view-text">
                                                    <p class="name-text">
                                                        Earning
                                                    </p>
                                                    <h6 class="balance-text">
                                                        $10684.54
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
<!-- traffic trader seller -->
<div class="col-lg-4">
        <div class="bg-white top-chart-earn">
            <div class="traffice-title">
                <p>Traffic</p>
            </div>
            <div class="traffice">
                <div id="pie"></div>
            </div>
        </div>
    </div>

    <script>
    // Fetch data from the PHP script
    fetch('path_to_your_php_script.php')
        .then(response => response.json())
        .then(data => {
            // Check for errors
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            // Extract the necessary information
            let seriesData = [];
            let labelsData = [];
            
            data.forEach(item => {
                seriesData.push(parseInt(item.TOTAL_QUANTITY));
                labelsData.push(item.FULL_NAME);
            });

            // Define chart options
            var options = {
                series: seriesData,
                chart: {
                    type: 'donut',
                },
                labels: labelsData,
                responsive: [{
                    breakpoint: 1024,
                    options: {
                        chart: {
                            width: '110px',
                            height: '400px'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            // Render the chart
            var chart = new ApexCharts(document.querySelector("#pie"), options);
            chart.render();
        })
        .catch(error => console.error('Error fetching data:', error));
    </script>
                    </div>
                </div>
            </section>

            <section>
                <div class="sm-chart-sec my-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 my-2">
                                <div class="revinue revinue-one_hybrid">
                                    <div class="revinue-hedding">
                                        <div class="w-title py-3 px-3">
                                            <div class="w-icon">
                                                <span class="fas fa-users"></span>

                                            </div>
                                            <?php 
include('inc/connection.php'); // Include your database connection script

// SQL query to fetch all customer_id
$query = 'SELECT customer_id FROM customer';

// Prepare the statement
$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    echo "Failed to prepare SQL statement: " . htmlspecialchars($e['message']);
    exit;
}

// Execute the statement
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    echo "Failed to execute SQL statement: " . htmlspecialchars($e['message']);
    exit;
}

$customer_ids = []; // Array to store customer IDs

// Fetch the results and store in the array
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $customer_ids[] = $row['CUSTOMER_ID'];
}

// echo '<h1>Total Rows: ' . count($customer_ids) . '</h1>';

// Free the statement identifier and close the connection
oci_free_statement($stid);
oci_close($conn);
?>


                                            <div class="sm-chart-text ">
                                            <h5>Followers</h5>
                                            <?php  echo '<p class="w-value">' . count($customer_ids) . '</p>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="revinue-content">
                                        <div id="hybrid_followers"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 my-2">
                                <div class="revinue page-one_hybrid">
                                    <div class="revinue-hedding">
                                        <div class="w-title py-3 px-3">
                                            <div class="w-icon">
                                                <i class="fa-solid fa-pager"></i>
                                            </div>
                                            <?php 
include('inc/connection.php');

// SQL query to fetch all NEWSLETTER_ID
$query = 'SELECT NEWSLETTER_ID FROM newsletter';

// Prepare the statement
$stid = oci_parse($conn, $query);
if (!$stid) {
    $e = oci_error($conn);
    echo "Failed to prepare SQL statement: " . htmlspecialchars($e['message']);
    exit;
}

// Execute the statement
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    echo "Failed to execute SQL statement: " . htmlspecialchars($e['message']);
    exit;
}

$NEWSLETTER_ID = []; // Array to store NEWSLETTER_IDs

// Fetch the results and store in the array
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $NEWSLETTER_ID[] = $row['NEWSLETTER_ID'];
}

// Free the statement identifier and close the connection
oci_free_statement($stid);
oci_close($conn);
?>
<div class="sm-chart-text">
    <p class="w-value">Page View</p>
    <h5><?php echo count($NEWSLETTER_ID); ?></h5>
</div>

                                        </div>
                                    </div>
                                    <div class="revinue-content">
                                        <div id="hybrid_followers1"></div>
                                    </div>
                                </div>


                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6 my-2">
                                <div class="revinue bonuce-one_hybrid">
                                    <div class="revinue-hedding">
                                        <div class="w-title py-3 px-3">
                                            <div class="w-icon">
                                                <i class="fa-regular fa-scale-unbalanced"></i>
                                            </div>
                                            <div class="sm-chart-text">
                                                <p class="w-value">$433</p>
                                                <h5>Bonuce Rate</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="revinue-content">
                                        <div id="hybrid_followers2"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-6 col-sm-6 my-2">
                                <div class="revinue rv-status-one_hybrid">
                                    <div class="revinue-hedding">
                                        <div class="w-title py-3 px-3">
                                            <div class="w-icon">
                                                <i class="bi bi-star-fill"></i>
                                            </div>
                                            <div class="sm-chart-text">
                                                
                                                <h5>$765 <small>jan 01 - jan 10</small></h5>
                                                <p class="w-value">Revinue Status</p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="revinue-content">
                                        <div id="hybrid_followers3"></div>
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </section>
            
            <!--admin show and order satatus table-->
            <section>
                <div class="all-admin my-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="admin-list">
                                    <p class="admin-ac-title">All Traders</p>
                                    <ul class="admin-ul">
                                        <?php
                                        include('inc/connection.php');
                                        $query = "SELECT * FROM trader";
                                        $data = oci_parse($conn, $query);
                                        oci_execute($data);
                                        $row_count = 0;
                                        while ($result = oci_fetch_assoc($data)) {
                                            echo '
    <li class="admin-li">
        <img src="assets/img/admin1.png" alt="" srcset="" class="admin-image">
        <div class="admin-ac-details">
            <div>
                <p class="admin-name">' . $result['FULL_NAME'] . '</p>
                <p class="activaty-text">Active Now</p>
            </div>
            <div class="status bg-success"></div>
        </div>
    </li>
    ';
                                        }
                                        ?>

                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="order-list">
                                    <p class="order-ac-title">Order Status</p>

                                    <div class="data-table-section table-responsive">
                                        <table id="example" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price </th>
                                                    <th>Status</th>
                                                    <th>Order Date</th>


                                                </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            
echo '<h5>Total Price: ' . htmlspecialchars($total_price) . '</h5>';
include('inc/connection.php');

// Modified query to join orderdetail and customer tables
$query = "
    SELECT o.CUSTOMER_ID, c.FIRSTNAME || ' ' || c.LASTNAME AS FULLNAME, o.PRODUCT_NAME, o.QUANTITY_ORDERED, o.PRICE, o.STATUS, o.ORDER_DATE
    FROM orderdetail o
    JOIN customer c ON o.CUSTOMER_ID = c.CUSTOMER_ID
    ORDER BY o.ORDER_DATE DESC
";

$data = oci_parse($conn, $query);
oci_execute($data);

while ($result = oci_fetch_assoc($data)) {
    $button_text = ($result['STATUS'] == 1) ? 'Pending' : 'Delivered';

    echo '
        <tr>
            <td>' . htmlspecialchars($result['FULLNAME']) . '</td>
            <td>' . htmlspecialchars($result['PRODUCT_NAME']) . '</td>
            <td>' . htmlspecialchars($result['QUANTITY_ORDERED']) . '</td>
            <td>' . htmlspecialchars($result['PRICE']) . '</td>
            <td>                    <button class="btn btn-' . ($result['STATUS'] == 1 ? 'warning' : 'success') . '">' . $button_text . '</button>
            </td>
            <td>' . htmlspecialchars($result['ORDER_DATE']) . '</td>
        </tr>
    ';
}

oci_close($conn);
?>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--admin show and order satatus table end-->

        </div>
    </div>
    <?php include('inc/footer.php'); ?>
</body>

</html>