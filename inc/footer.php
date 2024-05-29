<!-- Footer Start -->
<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="footer-widget">
                    <h1>Cleck E-Hub</h1>
                    <p>
                    At Cleck E-Hub, we're passionate about bringing the finest, freshest, and most delectable foods right to your doorstep. Our curated selection includes gourmet delicacies, everyday essentials, and unique treats sourced from the best producers around the world. We believe in quality, sustainability, and supporting small businesses. Join us in celebrating the joy of good food and discover new flavors every day.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Useful Pages</h3>
                    <ul>
                        <li><a href="product-list.php">Product</a></li>
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="user_register.php">Register</a></li>
                        <li><a href="profile.php">My Profile</a></li>
                    </ul>
                </div>
            </div>

            <!-- <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Quick Links</h3>
                    <ul>
                        <li><a href="product.php">Product</a></li>
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="checkout.php">Checkout</a></li>
                        <li><a href="login.php">Login & Register</a></li>
                        <li><a href="my-account.php">My Account</a></li>
                        <li><a href="wishlist.php">Wishlist</a></li>
                    </ul>
                </div>
            </div> -->

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Get in Touch</h3>
                    <div class="contact-info">
                        <a href="https://maps.app.goo.gl/vdEgX6XTgcA2sWfs5" target="_blank"
                            class="text-light m-0 p-0"><i class="fa fa-map-marker"></i>West Yorkshire</a><br>
                        <a href="mailto:cleckhuddersfaxeehub@gmail.com" target="_blank" class="text-light"><i
                                class="fa fa-envelope"></i>cleckhuddersfaxeehub@gmail.com</a><br>
                        <a href="tel:+977-9819931015" target="_blank" class="text-light"><i
                                class="fa fa-phone"></i>+977-9819931015</a>
                        <div class="social">
                            <a href="https://twitter.com/Cleckhudde58464" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.facebook.com/profile.php?id=61556992215083" target="_blank"><i class="fa fa-facebook"></i></a>
                            <!-- <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a> -->
                            <a href="https://www.instagram.com/cleckhuddersfax_2001/" target="_blank"><i class="fa fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCMrrsxGFTcAZHe4Jx7nGLNA" target="_blank"><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row payment">
            <div class="col-md-6">
                <div class="payment-method">
                    <p>We Accept:</p>
                    <img src="https://www.cdnlogo.com/logos/p/41/paypal.svg" alt="Payment Method" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="payment-security">
                    <p>Secured By:</p>
                    <img src="img/RecaptchaLogo.svg.png" alt="Payment Security" class="img-fluid" />
                    
                </div>
            </div>
        </div>

    </div>
</div>
    </div>
</div>
<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-6 copyright">
                <p>Copyright &copy; <a href="https://htmlcodex.com"></a>. All Rights Reserved
                    <?php $year = date("Y");
                    echo $year; ?>
                </p>
            </div>

            <div class="col-md-6 template-by">
                <p>Cleckhuddersfax E-Hub</p>
            </div>
        </div>
    </div>
</div>
<!-- Footer Bottom End -->




<!-- Back to Top -->
<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>



<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/slick/slick.min.js"></script>


<!-- Template Javascript -->
<script src="js/main.js"></script>


<script>
    // Get the current page URL
    var currentPage = window.location.href;

    // Function to set the active state of navbar links
    function setActiveNav() {
        // Remove 'active' class from all navbar links
        var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        navLinks.forEach(function(navLink) {
            navLink.classList.remove('active');
        });

        // Set 'active' class to the navbar link corresponding to the current page
        if (currentPage.includes("index.php")) {
            document.getElementById('home-link').classList.add('active');
        } else if (currentPage.includes("product-list.php")) {
            document.getElementById('products-link').classList.add('active');
        } else if (currentPage.includes("product-detail.php")) {
            document.getElementById('product-detail-link').classList.add('active');
        } else if (currentPage.includes("cart.php")) {
            document.getElementById('cart-link').classList.add('active');
        } else if (currentPage.includes("wishlist.php")) {
            document.getElementById('wishlist-link').classList.add('active');
        } else if (currentPage.includes("checkout.php")) {
            document.getElementById('checkout-link').classList.add('active');
        } else if (currentPage.includes("contact.php")) {
            document.getElementById('contact-us-link').classList.add('active');
        } else if (currentPage.includes("about-us.php")) {
            document.getElementById('about-link').classList.add('active');
        }
    }

    // Call setActiveNav function when the DOM content is loaded
    document.addEventListener("DOMContentLoaded", setActiveNav);
</script>



<script>
                      // Dummy data example
                      const dummyData = ['fish', 'munger', 'dummy', 'example', 'data', 'autocomplete'];

                      const searchInput = document.getElementById('searchInput');
                      const suggestionsContainer = document.getElementById('suggestions');

                      // Function to filter suggestions based on input text
                      function filterSuggestions(input) {
                          return dummyData.filter(item => item.toLowerCase().includes(input.toLowerCase()));
                      }

                      // Function to display suggestions
                      function displaySuggestions(suggestions) {
                          suggestionsContainer.innerHTML = '';
                          suggestions.forEach(suggestion => {
                              const suggestionElement = document.createElement('div');
                              suggestionElement.textContent = suggestion;
                              suggestionElement.classList.add('suggestion');
                              suggestionElement.addEventListener('click', () => {
                                  searchInput.value = suggestion;
                                  suggestionsContainer.innerHTML = '';
                              });
                              suggestionsContainer.appendChild(suggestionElement);
                          });
                      }

                      // Event listener for input changes
                      searchInput.addEventListener('input', (event) => {
                          const inputText = event.target.value;
                          const filteredSuggestions = filterSuggestions(inputText);
                          displaySuggestions(filteredSuggestions);
                      });
                  </script>
 <script>
                      document.addEventListener('DOMContentLoaded', function() {
                          // Select all nav links
                          const navLinks = document.querySelectorAll('.navbar-nav .nav-item .nav-link');

                          // Add click event listener to each nav link
                          navLinks.forEach(link => {
                              link.addEventListener('click', function() {
                                  // Remove bg-primary class from all nav links
                                  navLinks.forEach(nav => nav.classList.remove('bg-primary'));
                                  // Add bg-primary class to the clicked nav link
                                  this.classList.add('bg-primary');
                              });
                          });
                      });
                  </script>


