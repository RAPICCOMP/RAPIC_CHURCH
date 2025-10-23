<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    
    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            $image_path = null;
        }
    } else {
        $image_path = null;
    }

    // Handle YouTube Video Link
    $video_link = !empty($_POST['video_link']) ? $_POST['video_link'] : null;

    // Insert into Database
    $sql = "INSERT INTO womens_gallery_table (category, image, video_link) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $category, $image_path, $video_link);
    
    if ($stmt->execute()) {
        echo "Upload successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Category:</label>
    <input type="text" name="category" required><br>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*"><br>

    <label>YouTube Video Link:</label>
    <input type="text" name="video_link" placeholder="Paste YouTube link here"><br>

    <button type="submit">Upload</button>
</form>
