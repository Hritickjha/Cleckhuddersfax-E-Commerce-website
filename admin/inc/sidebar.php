<!-- sider start -->

<div class="left-menu">
    <div class="menubar-content">
        <nav class="animated bounceInDown">
            <ul id="sidebar">
                <li class="active" id="dashboard">
                    <a href="dashboard.php" class="fs-4"><i class="fas fa-home px-2"></i>Dashboard</a>
                </li>
                <li id="customer_query">
                    <a href="customer_query.php"><i class="fas fa-box-open px-2"></i>Customer Query</a>
                </li>
                <li id="subscriber">
                    <a href="subscriber.php"><i class="bi bi-substack px-2"></i></i>Subscribers</a>
                </li>
                <li id="subscriber">
                    <a href="product_review.php"><i class="bi bi-yelp px-2"></i></i>Product Review</a>
                </li>
                <li id="manage_trader">
                    <a href="manage_trader.php"><i class="fas fa-chart-bar px-2"></i>Manage Traders</a>
                </li>
                <li id="customer">
                    <a href="customer.php"><i class="fas fa-table px-2"></i>Customers</a>
                </li>
                <li id="page_adjustment">
                    <a href="page_adjustment.php"><i class="bi bi-gear-fill px-2"></i></i>Page Adjustment</a>
                </li>
                <li id="notification">
                    <a href="#"><i class="bi bi-bell-fill color-dark px-2"></i>Notification</a>
                </li>
                <li id="map">
                    <a href="map.php"><i class="fas fa-map-marker-alt px-2"></i>Maps</a>
                </li>
                <li id="email">
                    <a href="email.php"><i class="fas fa-envelope px-2"></i>E-mail</a>
                </li>
                <li id="calendar">
                    <a href="calendar.php"><i class="fas fa-calendar-alt px-2"></i>Calendar</a>
                </li>
                <li id="gallery">
                    <a href="gallery.php"><i class="bi bi-card-image px-2"></i></i>Gallery</a>
                </li>
                <li id="documentation">
                    <a href="#"><i class="fas fa-book px-2"></i>Documentation</a>
                </li>
                <li class="mb-5" id="logout">
                    <a href="logout.php" class="mb-5 pb-5"><i class="fas fa-sign-out-alt px-2"></i>Logout</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- side bar end -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var currentPath = window.location.pathname.split("/").pop();
        var sidebarItems = document.querySelectorAll("#sidebar li");

        sidebarItems.forEach(function(item) {
            var anchor = item.querySelector("a");
            if (anchor.getAttribute("href") === currentPath) {
                item.classList.add("active");
            } else {
                item.classList.remove("active");
            }

            anchor.addEventListener("click", function() {
                sidebarItems.forEach(function(el) {
                    el.classList.remove("active");
                });
                item.classList.add("active");
            });
        });
    });
</script>

