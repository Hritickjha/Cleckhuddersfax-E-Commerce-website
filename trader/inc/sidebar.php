<!-- sider start -->

<div class="left-menu">
    <div class="menubar-content">
        <nav class="animated bounceInDown">
            <ul id="sidebar">
                <li class="active">
                    <a href="trader_dashboard.php" class="fs-4"><i class="fas fa-home px-2"></i>Dashboard</a>
                </li>
                <li>
                    <a href="charts.php"><i class="fas fa-chart-bar px-2"></i>Charts</a>
                </li>
                <li>
                    <a href="add_product.php"><i class="fas fa-table px-2"></i>Add Product</a>
                </li>
                <li>
                    <a href="view_product.php"><i class="bi bi-eye-fill"></i>View Product</a>
                </li>
                <li>
                    <a href="map.php"><i class="fas fa-map-marker-alt px-2"></i>Maps</a>
                </li>
                <li>
                    <a href="email.php"><i class="fas fa-envelope px-2"></i>E-mail</a>
                </li>
                <li>
                    <a href="calendar.php"><i class="fas fa-calendar-alt px-2"></i>Calendar</a>
                </li>
                <li>
                    <a href="gallery.php"><i class="bi bi-file-image-fill"></i>Gallery</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-book px-2"></i>Documentation</a>
                </li>
                <li>
                    <a href="trader_profile.php"><i class="bi bi-person-fill"></i>Profile</a>
                </li>
                <li class="mb-5">
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

        // Function to remove 'active' class from all items
        function removeActiveClass() {
            sidebarItems.forEach(function(item) {
                item.classList.remove("active");
            });
        }

        // Check current path to set the active item on page load
        sidebarItems.forEach(function(item) {
            var anchor = item.querySelector("a");
            if (anchor.getAttribute("href") === currentPath) {
                item.classList.add("active");
            }

            // Add click event listener to each anchor
            anchor.addEventListener("click", function() {
                removeActiveClass();
                item.classList.add("active");
            });
        });
    });
</script>


