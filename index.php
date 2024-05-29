<?php
session_start();
include('inc/connection.php');
$query = "SELECT * FROM testimony";
$data = oci_parse($conn,$query);
oci_execute($data);
$total = oci_num_rows($data);
$result = oci_fetch_assoc($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <?php include('inc/link.php'); ?>
  <style>
    .news {
  box-shadow: inset 0 -15px 30px rgba(0,0,0,0.4), 0 5px 10px rgba(0,0,0,0.5);
  width: 350px;
  height: 30px;
  margin: 20px auto;
  overflow: hidden;
  border-radius: 4px;
  padding: 3px;
  -webkit-user-select: none
} 
.full-width{
    width: 100%;
}
.news span {
  float: left;
  color: #fff;
  padding: 6px;
  position: relative;
  top: -12%;
  left: -1%;
  border-radius: 4px;
  box-shadow: inset 0 -15px 30px rgba(0,0,0,0.4);
  font: 16px 'Source Sans Pro', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -webkit-user-select: none;
  cursor: pointer
}

.news ul {
  float: left;
  padding-left: 20px;
  animation: ticker 10s cubic-bezier(1, 0, .5, 0) infinite;
  -webkit-user-select: none
}

.news ul li {line-height: 30px; list-style: none }

.news ul li a {
  color: #fff;
  text-decoration: none;
  font: 14px Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -webkit-user-select: none
}
@keyframes ticker {
	0%   {margin-top: 0}
	25%  {margin-top: -30px}
	50%  {margin-top: -60px}
	75%  {margin-top: -90px}
	100% {margin-top: 0}
}
.news ul:hover { animation-play-state: paused }
.news span:hover+ul { animation-play-state: paused }
/* OTHER COLORS */
.blue { background: #347fd0 }
.blue span { background: #2c66be }
.red { background: #d23435 }
.red span { background: #c22b2c }
.green { background: #699B67 }
.green span { background: #547d52 }
.magenta { background: #b63ace }
.magenta span { background: #842696 }
.yellow {background : yellow}
.yellow span {background : yellow}

  </style>
</head>
<body>
    <?php  include('inc/header.php'); ?>

    <!-- Main Slider Start -->
    <div class="home-slider">
        <div class="main-slider">

        <?php
// Establish Oracle database connection
$conn = oci_connect('root1', 'root1', 'localhost/XE');

// Query to select all images from the Slider table
$query = "SELECT image FROM slider";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);

// Display images using a while loop
while ($row = oci_fetch_assoc($stmt)) {
    $image_name = $row['IMAGE'];
    $image_path = "images/" . $image_name; // Assuming images are stored in 'images' folder

    echo '<div class="main-slider-item"><img class="img-fluid w-100" src="'.$image_path.'" alt="Slider Image" /></div>
    ';
}

// Close database connection
oci_close($conn);
?>
        </div>

        <!-- discount offer and new silder start -->
    </div>
    <div class="news red full-width">
	<span class="bg-success ps-4">&nbsp; . 10% Discount On All Products!!</span>
	<ul class="scrollLeft">
    <li><a href="#">A New Year discount is a promotional reduction in price offered by retailers to encourage purchases during the New Year season</a></li>
    <li><a href="#">A summer sale is a seasonal discount event where retailers offer reduced prices on products to boost sales during summer.</a></li>
    <li><a href="#">A referral discount rewards customers with a price reduction for referring new customers, incentivizing both existing and new business.</a></li>
    <li><a href="#">An opening discount is a special price reduction offered by businesses to attract customers during their initial launch period</a></li>
</ul>
    </div>
            <!-- discount offer and new silder end -->

</div>

    <!-- Feature Start-->
    <div class="feature">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 feature-col">
                    <div class="feature-content">
                        <i class="fa fa-shield"></i>
                        <h2>Trusted Shopping</h2>
                        <p>
                            <?php echo $result['TRUSTED_SHOPPING'];
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 feature-col">
                    <div class="feature-content">
                        <i class="fa fa-shopping-bag"></i>
                        <h2>Quality Product</h2>
                        <p>
                        <p>
                            <?php echo $result['QUALITY_PRODUCT'];
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 feature-col">
                    <div class="feature-content">
                        <i class="fa fa-truck"></i>
                        <h2>Worldwide Delivery</h2>
                        <p>
                        <p>
                            <?php echo $result['WORLDWIDE_DELIVERY'];
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 feature-col">
                    <div class="feature-content">
                        <i class="fa fa-phone"></i>
                        <h2>Telephone Support</h2>
                        <p>
                        <p>
                            <?php echo $result['TELEPHONE'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="featured-product">
        <div class="container">
            <div class="section-header">
                <h3>Featured Product</h3>
                <p>
                    Discover our Signature Products; Redefining Excellence.
                </p>
            </div>
            <?php
include('inc/connection.php');

$query = "SELECT * FROM (SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRICE, IMAGE FROM products) WHERE ROWNUM <= 6";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
?>

<div class="row align-items-center product-slider product-slider-4">
    <?php
    // Display data using a while loop
    while ($row = oci_fetch_assoc($stmt)) {
        $image_name = $row['IMAGE'];
        $image_path = "images/" . $image_name; // Assuming images are stored in 'images' folder

        echo '
        <div class="col-lg-3">
            <div class="product-item">
                <div class="product-image">
                    <a href="product-detail.php?id='.$row['ADD_PRODUCT_ID'].'">
                        <img src="'.$image_path.'" alt="Product Image">
                    </a>
                    <div class="product-action">
                        <a href="add-to-cart.php?id=' . $row['ADD_PRODUCT_ID'] . '"><i class="fa fa-cart-plus"></i></a>
                        <a href="#"><i class="fa fa-heart"></i></a>
                        <a href="#"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="product-content">
                    <div class="title"><a href="product-detail.php?id='.$row['ADD_PRODUCT_ID'].'">'.$row['PRODUCT_NAME'].'</a></div>
                    <div class="ratting">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="price">$'.$row['PRICE'].'</div>
                </div>
            </div>
        </div>';
    }

    // Close database connection
    oci_close($conn);
    ?>

               
            </div>
        </div>
    </div>
    <!-- Featured Product End -->

    <!-- Recent Product Start -->
    <div class="recent-product">
        <div class="container">
            <div class="section-header">
                <h3>Recent Product</h3>
                <p>
                    Recent Products that Our Customers Love.
                </p>
            </div>
            <div class="row align-items-center product-slider product-slider-4">
            
    <?php
    // Adjust the query to select the 8 most recently added products
    $query = "
        SELECT * FROM (
            SELECT ADD_PRODUCT_ID, PRODUCT_NAME, PRICE, IMAGE 
            FROM products 
            ORDER BY DATE_ADDED DESC
        ) WHERE ROWNUM <= 8";

    $stmt = oci_parse($conn, $query);
    oci_execute($stmt);

    // Display data using a while loop
    while ($row = oci_fetch_assoc($stmt)) {
        $image_name = $row['IMAGE'];
        $image_path = "images/" . $image_name; // Assuming images are stored in 'images' folder

        echo '
        <div class="col-lg-3">
            <div class="product-item">
                <div class="product-image">
                    <a href="product-detail.php?id=' . $row['ADD_PRODUCT_ID'] . '">
                        <img src="' . $image_path . '" alt="Product Image">
                    </a>
                    <div class="product-action">
                        <a href="add-to-cart.php?id=' . $row['ADD_PRODUCT_ID'] . '"><i class="fa fa-cart-plus"></i></a>
                        <a href="#"><i class="fa fa-heart"></i></a>
                        <a href="#"><i class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="product-content">
                    <div class="title"><a href="product-detail.php?id=' . $row['ADD_PRODUCT_ID'] . '">' . $row['PRODUCT_NAME'] . '</a></div>
                    <div class="ratting">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="price">$' . $row['PRICE'] . '</div>
                </div>
            </div>
        </div>';
    }

    // Close database connection
    oci_close($conn);
    ?>
</div>

            </div>
        </div>
    </div>
    <!-- Recent Product End -->


    <!-- Brand Start -->
    <?php  
$query = "SELECT * FROM our_brands";
$data = oci_parse($conn,$query);
oci_execute($data);
$total = oci_num_rows($data);
$result = oci_fetch_assoc($data);
    ?>
    <div class="brand">
        <div class="container">
            <div class="section-header">
                <h3>Our Brands</h3>
                <p>
                <p>
                            <?php echo $result['DESCRIPTION'];
                            ?>
                </p>
            </div>
            <div class="brand-slider">
                <div class="brand-item"><img src="brand/1.png" alt="" class="w-100"></div>
                <div class="brand-item"><img src="brand/2.png" alt="" class="w-100"></div>
                <div class="brand-item"><img src="brand/3.png" alt="" class="w-100"></div>
                <div class="brand-item"><img src="brand/4.png" alt="" class="w-100"></div>
                <div class="brand-item"><img src="brand/5.png" alt="" class="w-100"></div>
                <div class="brand-item"><img src="brand/6.png" alt="" class="w-100"></div>
            </div>
        </div>
    </div>
    <!-- Brand End -->

    <!-- Newsletter Start -->
    <div class="newsletter mb-4">
    <div class="container">
        <div class="section-header">
            <h3>Subscribe Our Newsletter</h3>
            <p>
                Subscribe Our Newsletter to stay up-to-date with our company!
            </p>
        </div>
        <div class="form" >
        <form action="" method="post">
        <input name="email" type="email" placeholder="Your email here" required>
        <button type="submit" name="submit">Submit</button>
    </form>

        </div>
    </div>
</div>
<?php  
include('inc/connection.php');

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $query = "INSERT INTO newsletter (email) VALUES ('$email')";
    $data = oci_parse($conn, $query);
    
    if(oci_execute($data)) {
        echo "<script>alert('Thanks for subscribing !!')</script>";
    } else {
        echo "Server down";
    }
}
?>

    <?php  include('inc/footer.php'); ?>

</body>
</html>