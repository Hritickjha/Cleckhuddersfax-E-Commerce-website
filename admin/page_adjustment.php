<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>page adjustment</title>
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
        <!-- setting start -->


        <h3 class="text-left text-dark ">Slider </h3>
        <div class="mt-2">
          <div class="text-start">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
          </div>
        </div>
        <div class="mt-4">
          <div class="row">
          <?php
// Establish Oracle database connection
include('inc/connection.php');

// Query to select ID and image from the Slider table
$query = "SELECT ID, image FROM slider";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);

// Display images using a while loop
while ($row = oci_fetch_assoc($stmt)) {
    $image_name = $row['IMAGE'];
    $image_path = "../images/" . $image_name; // Assuming images are stored in 'images' folder
    $id = $row['ID']; // Access the ID correctly

    echo '<div class="col-lg-3 col-md-3 mt-3 position-relative">';
    echo '<div class="position-absolute top-0 end-0">';
    echo '<a href="delete_slider.php?id=' . $id . '" class="btn btn-danger btn-sm shadow-none" onclick="return confirm(\'Are you sure you want to delete this product?\');">';
    echo '<i class="bi bi-trash"></i> Delete';
    echo '</a>';
    echo '</div>';
    echo '<img src="' . $image_path . '" alt="" class="w-100 img-fluid">';
    echo '</div>';
}

// Close database connection
oci_close($conn);
?>


          </div>
        </div>



        <!-- setting end -->
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Slider Images</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="fullname">choose Image</label><br>
                <input type="file" id="fullname" name="file" required>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="file" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded successfully
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $image_name = $_FILES["file"]["name"];
        $image_tmp = $_FILES["file"]["tmp_name"];

        // Move uploaded file to images folder
        $upload_directory = "../images/";
        move_uploaded_file($image_tmp, $upload_directory . $image_name);

        // Insert image name into Oracle database
        $conn = oci_connect('root1', 'root1', 'localhost/XE');
        $query = "INSERT INTO slider (image) VALUES (:image)";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':image', $image_name);
        oci_execute($stmt);

        // Close connection
        oci_close($conn);

        // Redirect or display success message
        // header("Location: success.php");
         echo "<script>alert('Image uploaded and saved successfully!')</script>";
        echo '<script>window.location.href = "page_adjustment.php";</script>';
    } else {
        // Handle file upload error
        // echo "Error uploading file.";
    }
}
?>
    <!-- modal end -->

    <?php include('inc/footer.php'); ?>
</body>

</html>