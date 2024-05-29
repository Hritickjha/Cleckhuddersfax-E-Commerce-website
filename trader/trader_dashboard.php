<?php session_start(); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trader dashboard</title>
    <?php include('inc/link.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .left-menu .active {
            background-color: #285ED1;
            /* Change this to your preferred background color */
        }
    </style>
    <style>
        .top-chart-earn {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .traffice-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .chart-container {
            margin-bottom: 30px;
        }
        canvas {
            width: 100% !important;
            height: 400px !important;
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
                                            <div class="earn">
                                                <h2>$3367.98</h2>
                                                <p>Total Sales</p>
                                            </div>
                                            <div class="sale mb-3">
                                                <h2>513</h2>
                                                <p>Current Sale</p>
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
    fetch('fetch_trader_products.php')
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
                seriesData.push(parseInt(item.STOCK_AVAILABLE));
                labelsData.push(item.PRODUCT_NAME);
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


           

            <!--trader show and order satatus table-->
            <section>
                <div class="all-admin my-5">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-md-12">
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
                                                    <th>Delivery</th>
                                                    <th>Order Date</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
include('inc/connection.php');

// Check if the trader_id session is set
if (isset($_SESSION['trader_id'])) {
    $trader_id = $_SESSION['trader_id'];

    $query = "
        SELECT o.CUSTOMER_ID, c.FIRSTNAME || ' ' || c.LASTNAME AS FULLNAME, o.PRODUCT_NAME, o.QUANTITY_ORDERED, o.PRICE, o.STATUS, o.ORDER_DATE
        FROM orderdetail o
        JOIN customer c ON o.CUSTOMER_ID = c.CUSTOMER_ID
        JOIN products p ON o.ADD_PRODUCT_ID = p.ADD_PRODUCT_ID
        WHERE p.TRADER_ID = :trader_id
        ORDER BY o.ORDER_DATE DESC
    ";

    $data = oci_parse($conn, $query);
    oci_bind_by_name($data, ':trader_id', $trader_id);
    oci_execute($data);

    // Initialize total sale variable
    $total_sale = 0;

    while ($result = oci_fetch_assoc($data)) {
        // Determine button text based on STATUS value
        $button_text = ($result['STATUS'] == 1) ? 'Pending' : 'Delivered';

        echo '
            <tr>
                <td>' . htmlspecialchars($result['FULLNAME']) . '</td>
                <td>' . htmlspecialchars($result['PRODUCT_NAME']) . '</td>
                <td>' . htmlspecialchars($result['QUANTITY_ORDERED']) . '</td>
                <td>' . htmlspecialchars($result['PRICE']) . '</td>
                <td>
                    <button class="btn btn-' . ($result['STATUS'] == 1 ? 'warning' : 'success') . '">' . $button_text . '</button>
                </td>
                <td>' . htmlspecialchars($result['ORDER_DATE']) . '</td>
            </tr>
        ';

        // Increment total sale by the price of the product
        $total_sale += $result['PRICE'];
    }

    // Store total sale value in a session variable
    $_SESSION['total_sale'] = $total_sale;
} else {
    echo "Your product is not ordered!";
}

oci_close($conn);

// Print total_sale variable outside the while loop
echo '<h1 class="text-success">Total Sale: $' . $total_sale . '</h1>';
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