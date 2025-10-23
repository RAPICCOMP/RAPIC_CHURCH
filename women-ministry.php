<?php
// Database connection
include 'db_connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_ministry'])) {
        $ministry_name = $_POST['ministry_name'];
        $query = "INSERT INTO ministries (name) VALUES ('$ministry_name')";
        mysqli_query($conn, $query);
    }
    
    if (isset($_POST['upload_image'])) {
        $ministry_id = $_POST['ministry_id'];
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $query = "INSERT INTO images (ministry_id, image_path) VALUES ('$ministry_id', '$image_path')";
        mysqli_query($conn, $query);
    }
    
    if (isset($_POST['add_video'])) {
        $ministry_id = $_POST['ministry_id'];
        $video_url = $_POST['video_url'];
        $query = "INSERT INTO videos (ministry_id, video_url) VALUES ('$ministry_id', '$video_url')";
        mysqli_query($conn, $query);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <form method="POST">
        <label>Ministry Name:</label>
        <input type="text" name="ministry_name" required>
        <button type="submit" name="add_ministry">Add Ministry</button>
    </form>
    
    <form method="POST" enctype="multipart/form-data">
        <label>Upload Image:</label>
        <select name="ministry_id">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM ministries");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <input type="file" name="image" required>
        <button type="submit" name="upload_image">Upload</button>
    </form>
    
    <form method="POST">
        <label>Video URL:</label>
        <select name="ministry_id">
            <?php
            $result = mysqli_query($conn, "SELECT * FROM ministries");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <input type="text" name="video_url" required>
        <button type="submit" name="add_video">Add Video</button>
    </form>
</body>
</html>
