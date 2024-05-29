<?php
session_start();
include('inc/connection.php');
$trader_profile = isset($_SESSION['fullname_name']) ? $_SESSION['fullname_name'] : null;
if ($trader_profile) {
    $trader_id = $_SESSION['trader_id']; // Assuming trader_id is stored in session
    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
    $street = isset($_SESSION['street']) ? $_SESSION['street'] : '';
    $city = isset($_SESSION['city']) ? $_SESSION['city'] : '';
    $state = isset($_SESSION['state']) ? $_SESSION['state'] : '';
} else {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $position = $_POST['position'];

    $update_query = "UPDATE trader SET 
                        FULL_NAME = :fullname, 
                        PHONE_NUM = :phone, 
                        STREET = :street, 
                        CITY = :city, 
                        STATE = :state, 
                        POSITION = :position 
                     WHERE TRADER_ID = :trader_id";

    $stid = oci_parse($conn, $update_query);

    oci_bind_by_name($stid, ':fullname', $fullname);
    oci_bind_by_name($stid, ':phone', $phone);
    oci_bind_by_name($stid, ':street', $street);
    oci_bind_by_name($stid, ':city', $city);
    oci_bind_by_name($stid, ':state', $state);
    oci_bind_by_name($stid, ':position', $position);
    oci_bind_by_name($stid, ':trader_id', $trader_id);

    $exec = oci_execute($stid);

    if ($exec) {
        $_SESSION['fullname_name'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['street'] = $street;
        $_SESSION['city'] = $city;
        $_SESSION['state'] = $state;
        $_SESSION['position'] = $position;

        oci_free_statement($stid);
        oci_close($conn);

        header("Location: trader_profile.php");
        exit;
    } else {
        $e = oci_error($stid);
        echo "Execute failed: " . htmlentities($e['message']);
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
  <?php include('inc/link.php'); ?>
</head>
  <body>
    <div class="main-wrapper">
        <div class="header-container fixed-top">
            
<!-- top header start -->
<?php include('inc/header.php'); ?>

<!-- top header end -->

        </div>

<!-- side bar start -->
        <?php include('inc/sidebar.php'); ?>
<!-- side bar end -->
        <div class="content-wrapper">
         
        <div class="container">
        <!-- please add map coding this comment under -->

        <div class="container">
    <div class="main-body">
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4><?php echo $_SESSION['fullname_name'];  ?></h4>
                      <p class="text-secondary mb-1"><?php echo $position; ?></p>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe mr-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Website</h6>
                    <span class="text-secondary">https://bootdey.com</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github mr-2 icon-inline"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>Github</h6>
                    <span class="text-secondary">bootdey</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter mr-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>
                    <span class="text-secondary">@bootdey</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram mr-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>
                    <span class="text-secondary">bootdey</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook mr-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                    <span class="text-secondary">bootdey</span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-1">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $_SESSION['fullname_name'];  ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $email; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Phone</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php echo $phone; ?>
                    </div>
                  </div>
                  <hr>			
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Street</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $street; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">City</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $city; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">State</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $state; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Positon</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $position; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
                      
                        <button type="button" class="btn btn-info btn-lg btn-md-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
    </div>
<!-- map end -->

        </div>
    </div>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Your Profile Editor</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="trader_profile.php">
          <div class="mb-1">
            <label for="fullname" class="form-label">Name</label>
            <input type="text" name="fullname" value="<?php echo $_SESSION['fullname_name']; ?>" class="form-control" id="fullname" required>
          </div>
          <div class="mb-1">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" value="<?php echo $phone; ?>" class="form-control" id="phone" required>
          </div>
          <div class="mb-1">
            <label for="street" class="form-label">Street</label>
            <input type="text" name="street" value="<?php echo $street; ?>" class="form-control" id="street" required>
          </div>
          <div class="mb-1">
            <label for="city" class="form-label">City</label>
            <input type="text" name="city" value="<?php echo $city; ?>" class="form-control" id="city" required>
          </div>
          <div class="mb-1">
            <label for="state" class="form-label">State</label>
            <input type="text" name="state" value="<?php echo $state; ?>" class="form-control" id="state" required>
          </div>
          <div class="mb-1">
            <label for="position" class="form-label">Position</label>
            <input type="text" name="position" value="<?php echo $position; ?>" class="form-control" id="position" required>
          </div>
          <div class="mb-1 form-check">
            <input type="checkbox" class="form-check-input" id="confirm" required>
            <label class="form-check-label" for="confirm">Are you sure you want to change profile</label>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<?php include('inc/footer.php'); ?>
</body>
</html>