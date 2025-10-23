<?php
// Include your database connection
include('db_connect.php');

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Handling Image Upload
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $image_name = $_FILES['image_url']['name'];
        $image_tmp_name = $_FILES['image_url']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid() . '.' . $image_extension;
        $image_upload_path = 'uploads/images/' . $image_new_name;

        // Move the uploaded image to the 'uploads/images/' folder
        move_uploaded_file($image_tmp_name, $image_upload_path);
    }

    // Handling Video Upload
    if (isset($_FILES['video_url']) && $_FILES['video_url']['error'] == 0) {
        $video_name = $_FILES['video_url']['name'];
        $video_tmp_name = $_FILES['video_url']['tmp_name'];
        $video_extension = pathinfo($video_name, PATHINFO_EXTENSION);
        $video_new_name = uniqid() . '.' . $video_extension;
        $video_upload_path = 'uploads/videos/' . $video_new_name;

        // Move the uploaded video to the 'uploads/videos/' folder
        move_uploaded_file($video_tmp_name, $video_upload_path);
    }

    // Insert sermon data into the database
    $sql = "INSERT INTO sermons (title, description, image_url, video_url) VALUES ('$title', '$description', '$image_new_name', '$video_new_name')";
    if (mysqli_query($conn, $sql)) {
        echo "Sermon added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
