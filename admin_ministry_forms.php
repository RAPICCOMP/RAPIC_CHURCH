<?php
require 'db_connect.php';

// Handle slideshow uploads
if (isset($_POST['upload_slideshow'])) {
    $image = $_FILES['slideshow_image']['name'];
    $target = 'uploads/' . basename($image);

    $query = "INSERT INTO slideshow (image) VALUES ('$image')";
    if (mysqli_query($conn, $query) && move_uploaded_file($_FILES['slideshow_image']['tmp_name'], $target)) {
        echo "Slideshow image uploaded successfully.";
    } else {
        echo "Error uploading slideshow image.";
    }
}

// Handle gallery uploads
if (isset($_POST['upload_gallery'])) {
    $image = $_FILES['gallery_image']['name'];
    $target = 'uploads/' . basename($image);

    $query = "INSERT INTO gallery (image) VALUES ('$image')";
    if (mysqli_query($conn, $query) && move_uploaded_file($_FILES['gallery_image']['tmp_name'], $target)) {
        echo "Gallery image uploaded successfully.";
    } else {
        echo "Error uploading gallery image.";
    }
}

// Handle meeting submissions
if (isset($_POST['add_meeting'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    
    $query = "INSERT INTO meetings (title, date, location) VALUES ('$title', '$date', '$location')";
    if (mysqli_query($conn, $query)) {
        echo "Meeting added successfully.";
    } else {
        echo "Error adding meeting.";
    }
}

// Handle video submissions
if (isset($_POST['add_video'])) {
    $video_url = $_POST['video_url'];
    
    $query = "INSERT INTO videos (url) VALUES ('$video_url')";
    if (mysqli_query($conn, $query)) {
        echo "Video added successfully.";
    } else {
        echo "Error adding video.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Upload Content</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background: #2a2a72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #1e1e5b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Slideshow Image</h2>
        <form action="upload_slideshow.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <div class="container">
        <h2>Upload Gallery Image</h2>
        <form action="upload_gallery.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit">Upload</button>
        </form>
    </div>

    <div class="container">
        <h2>Add Meeting</h2>
        <form action="add_meeting.php" method="POST">
            <input type="text" name="title" placeholder="Meeting Title" required>
            <input type="date" name="date" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit">Add Meeting</button>
        </form>
    </div>

    <div class="container">
        <h2>Upload Video</h2>
        <form action="upload_video.php" method="POST">
            <input type="text" name="video_url" placeholder="YouTube Video URL" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
