  <!-- Top Header Start -->
  <div class="top-header">
      <div class="container">
          <div class="row align-items-center">
              <div class="col-md-3">
                  <div class="logo">
                      <a href="index.php">
                          <img src="img/Logo_2.png" class="img-fluid" alt="Logo">
                      </a>
                  </div>
              </div>
              <div class="col-md-5">
            
              <form id="searchForm" action="search.php" method="get">
    <div class="search border border-success">
        <input type="text" name="query" id="searchInput" placeholder="Search">
        <button type="submit" class="border"><i class="fa fa-search"></i></button>
    </div>
</form>



                  <div id="suggestions" class="suggestions"></div>
              </div>

              <div class="col-md-4">
                  <div class="user">
                      <div class="dropdown">
                            <!-- Conditional menu rendering based on login status -->
                            
                            <?php
                           //echo $_SESSION['name'];
if (isset($_SESSION['name'])) {
    echo '
    <a href="my-account.php" class="dropdown-toggle text-dark" data-toggle="dropdown">Welcome ' . htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8') . '</a>
    <div class="dropdown-menu">
        <a href="profile.php" class="dropdown-item">Profile</a>
        <a href="logout.php" class="dropdown-item">Logout</a>
    </div>
    ';
} else {
    echo '
    
    
    <a href="my-account.html" class="dropdown-toggle text-dark" data-toggle="dropdown">Create Account</a>
    <div class="dropdown-menu">
        <a href="user_register.php" class="dropdown-item">Register</a>
        <a href="login.php" class="dropdown-item">Login</a>
        <a href="http://localhost/group-project/trader/index.php" class="dropdown-item">Trader Login</a>
    </div>
    ';
}
?>         

    <!-- Your existing HTML content -->

                      </div>

                      <div class="cart">
    <i class="fa fa-cart-plus position-relative">
        <span id="cart-quantity" class="position-absolute top-0 start-100 translate-middle px-1"></span>
    </i>
</div>

                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Top Header End -->


  <!-- Header Start -->
  <div class="header">
      <div class="container">
          <nav class="navbar navbar-expand-md navbar-dark m-0 p-0 py-1 fs-3">
              <a href="#" class="navbar-brand">MENU</a>
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                  <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                  <div class="navbar-nav m-auto py-2">
                      <a href="index.php" class="nav-item nav-link" id="home-link">Home</a>
                      <a href="product-list.php" class="nav-item nav-link" id="products-link">Products</a>
                      
                      <a href="cart.php" class="nav-item nav-link" id="cart-link">Cart</a>
                      <a href="wishlist.php" class="nav-item nav-link" id="wishlist-link">Wishlist</a>
                      <a href="contact.php" class="nav-item nav-link" id="contact-us-link">Contact Us</a>
                      <a href="about-us.php" class="nav-item nav-link" id="about-link">About</a>
                      <!-- <a href="login.html" class="nav-item nav-link px-3">Login & Register</a> -->
                      <!-- <a href="my-account.html" class="nav-item nav-link px-3">My Account</a> -->
                  </div>

                  <!-- <a href="login.html" class="nav-item nav-link px-3">Login & Register</a> -->
                  <!-- <a href="my-account.html" class="nav-item nav-link px-3">My Account</a> -->
               

              </div>
      </div>
      </nav>
  </div>
  </div>
  <!-- Header End -->