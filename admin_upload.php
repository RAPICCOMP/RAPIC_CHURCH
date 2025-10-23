<?php
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ministryName = $_POST['ministryName'];
    $videoLink = $_POST['videoLink'];

    // Handle slideshow images
    $slideshowImages = [];
    foreach ($_FILES['slideshow']['name'] as $key => $name) {
        $targetDir = "uploads/slideshow/";
        $targetFile = $targetDir . basename($name);
        move_uploaded_file($_FILES['slideshow']['tmp_name'][$key], $targetFile);
        $slideshowImages[] = $targetFile;
    }
    $slideshowImagesStr = implode(',', $slideshowImages); // Convert array to string

    // Handle gallery images
    $galleryImages = [];
    foreach ($_FILES['gallery']['name'] as $key => $name) {
        $targetDir = "uploads/gallery/";
        $targetFile = $targetDir . basename($name);
        move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $targetFile);
        $galleryImages[] = $targetFile;
    }
    $galleryImagesStr = implode(',', $galleryImages); // Convert array to string

    // Insert into database
    $query = "INSERT INTO ministries (name, slideshow_images, event_gallery, video_link) 
              VALUES ('$ministryName', '$slideshowImagesStr', '$galleryImagesStr', '$videoLink')";
    
    if ($conn->query($query) === TRUE) {
        echo "New ministry page created successfully";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!-- HTML Form for Creating Ministry Page (reused from earlier) -->
<form action="your_php_file.php" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="ministryName">Ministry Name</label>
    <input type="text" id="ministryName" name="ministryName" required placeholder="e.g. Youth Ministry">
  </div>

  <!-- Slideshow Section -->
  <div class="form-group">
    <label for="slideshow">Upload Slideshow Images</label>
    <input type="file" id="slideshow" name="slideshow[]" accept="image/*" multiple required>
  </div>

  <!-- Gallery Section -->
  <div class="form-group">
    <label for="gallery">Upload Event Pictures</label>
    <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple required>
  </div>

  <!-- Video Section -->
  <div class="form-group">
    <label for="videoLink">YouTube Video Link</label>
    <input type="url" id="videoLink" name="videoLink" placeholder="https://youtube.com/..." required>
  </div>

  <button type="submit" class="submit-btn">Create Ministry Page</button>
</form>
