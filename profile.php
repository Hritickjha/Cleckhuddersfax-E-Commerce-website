<?php
session_start();
include('inc/connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $userId = $_SESSION['user_id'];
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Update user information in the database
    $update_query = "UPDATE customer SET 
                        FIRSTNAME = :firstname, 
                        LASTNAME = :lastname, 
                        PHONE_NUMBER = :phone_number, 
                        ADDRESS = :address 
                     WHERE CUSTOMER_ID = :user_id";

    $stmt = oci_parse($conn, $update_query);

    oci_bind_by_name($stmt, ':firstname', $fname);
    oci_bind_by_name($stmt, ':lastname', $lname);
    oci_bind_by_name($stmt, ':phone_number', $phone);
    oci_bind_by_name($stmt, ':address', $address);
    oci_bind_by_name($stmt, ':user_id', $userId);

    $exec = oci_execute($stmt);

    if ($exec) {
        // Update session variables
        $_SESSION['name'] = $fname;
        $_SESSION['lastname'] = $lname;
        $_SESSION['phone'] = $phone;
        $_SESSION['address'] = $address;

        // Close statement and connection
        oci_free_statement($stmt);
        oci_close($conn);

        // Redirect to profile page after successful update
        $success = "your profile information has been successfully edit";
        header("Location: profile.php");
        exit;
    } else {
        $e = oci_error($stmt);
        echo "Execute failed: " . htmlentities($e['message']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>user profile</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce" name="keywords">
    <?php include('inc/link.php'); ?>
</head>

<body>
    <?php include('inc/header.php'); ?>
    

    <div class="container">
    <?php if (isset($success_message)) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?> 
    <div class="main-body mt-4">
    
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="<?php if(isset($_SESSION['profile_image'])){
                      echo $_SESSION['profile_image'];
                    }?>" alt="Admin" class="rounded-circle " width="150" height="150">
                    <div class="mt-3">
                      <h4><?php
                      if(isset($_SESSION['name'])){
                        echo $_SESSION['name']; 
                      }else{
                        echo "session not set";
                      }
                      ?></h4>
                      <p class="text-secondary mb-1">Customer</p>
                     
                      <button class="btn btn-primary">Follow</button>
                     <a href="user_order_details.php"><button class="btn btn-outline-primary" type="button">Order List</button></a> 
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php
                      if(isset($_SESSION['name']) AND isset($_SESSION['lastname'])){
                        echo $_SESSION['name']. "  " .$_SESSION['lastname']; 
                      }else{
                        echo "session not set";
                      }
                      ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php
                      if(isset($_SESSION['email'])){
                        echo $_SESSION['email']; 
                      }else{
                        echo "session not set";
                      }
                      ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Phone</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php
                      if(isset($_SESSION['phone'])){
                        echo $_SESSION['phone']; 
                      }else{
                        echo "session not set";
                      }
                      ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php
                      if(isset($_SESSION['address'])){
                        echo $_SESSION['address']; 
                      }else{
                        echo "session not set";
                      }
                      ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
                    <button type="button" class="btn btn-sm lg-btn-lg btn-primary shadow-none" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Edit
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Profile Edit</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
          <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" name="fname" class="form-control" id="fname" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" required>
          </div>
          <div class="mb-3">
            <label for="lname" class="form-label">Last Name</label>
            <input type="text" name="lname" class="form-control" id="lname" value="<?php echo isset($_SESSION['lastname']) ? $_SESSION['lastname'] : ''; ?>" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" id="phone" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" required>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" class="form-control" id="address" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Change</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Your remaining HTML content -->



                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
