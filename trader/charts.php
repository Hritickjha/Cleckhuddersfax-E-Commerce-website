<?php session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Economicks</title>
  <?php include('inc/link.php'); ?>
  <style>
    .left-menu .active {
        background-color: #285ED1; /* Change this to your preferred background color */
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
                        <div class="col-lg-12 col-md-10">
                            <div class="bg-white top-chart-earn">
                                <div class="row">
                                    <div class="col-sm-4 my-2 pe-0">
                                        <div class="last-month">
                                            <h5>Your Dashboard</h5>
                                            <p>Overview of profit and lose</p>
                                            <div class="earn">
                                                <h2>$3367.98</h2>
                                                <p>Current Month Sales</p>
                                            </div>
                                            <div class="sale mb-3">
                                               <h2>95</h2>
                                               <p>Current monh Sale</p> 
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
                    <div class="tab-pane fade show active" id="pills-week" role="tabpanel" aria-labelledby="pills-week-tab">
                        <div class="chart-container" id="container-week">
                            <canvas id="chart-week"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-month" role="tabpanel" aria-labelledby="pills-month-tab">
                        <div class="chart-container" id="container-month">
                            <canvas id="chart-month"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-year" role="tabpanel" aria-labelledby="pills-year-tab">
                        <div class="chart-container" id="container-year">
                            <canvas id="chart-year"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Fetch sales data
    fetch('fetch_trader_sales.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            // Function to prepare data for the chart
            function prepareChartData(salesData, labels) {
                let datasets = {};
                labels.forEach(label => {
                    datasets[label] = {
                        label: label,
                        data: [],
                        backgroundColor: getRandomColor(),
                        stack: 'Stack 0'
                    };
                });

                for (let period in salesData) {
                    let sales = salesData[period];
                    sales.forEach(sale => {
                        if (datasets[sale.PRODUCT_NAME]) {
                            datasets[sale.PRODUCT_NAME].data.push(parseInt(sale.TOTAL_QUANTITY));
                        } else {
                            datasets[sale.PRODUCT_NAME] = {
                                label: sale.PRODUCT_NAME,
                                data: [parseInt(sale.TOTAL_QUANTITY)],
                                backgroundColor: getRandomColor(),
                                stack: 'Stack 0'
                            };
                        }
                    });
                }
                
                return Object.values(datasets);
            }

            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            // Prepare data for weekly chart
            let weekLabels = Object.keys(data.weekly);
            let weekDatasets = prepareChartData(data.weekly, weekLabels);

            // Render weekly chart
            new Chart(document.getElementById('chart-week').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: weekLabels,
                    datasets: weekDatasets
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Weekly Sales'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    },
                    responsive: true
                }
            });

            // Prepare data for monthly chart
            let monthLabels = Object.keys(data.monthly);
            let monthDatasets = prepareChartData(data.monthly, monthLabels);

            // Render monthly chart
            new Chart(document.getElementById('chart-month').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: monthDatasets
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Sales'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    },
                    responsive: true
                }
            });

            // Prepare data for yearly chart
            let yearLabels = Object.keys(data.yearly);
            let yearDatasets = prepareChartData(data.yearly, yearLabels);

            // Render yearly chart
            new Chart(document.getElementById('chart-year').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: yearLabels,
                    datasets: yearDatasets
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Yearly Sales'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    },
                    responsive: true
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
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
                                            <div class="sm-chart-text ">
                                                <p class="w-value">31.9K</p>
                                                <h5>Followers</h5>
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
                                            <div class="sm-chart-text">
                                                <p class="w-value">Page View</p>
                                                <h5>654322</h5>
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
          
            <!--admin show and order satatus table end-->

        </div>
    </div>
<?php include('inc/footer.php'); ?>
</body>
</html>