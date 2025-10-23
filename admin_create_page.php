<?php
// db_connect.php should already be included to connect to your MySQL database.
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $ministry_name = $_POST['ministry_name'];
    $image = $_FILES['ministry_image'];
    $video_url = $_POST['video_url'];
    $description = $_POST['description'];

    // Handle Image Upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    move_uploaded_file($image["tmp_name"], $target_file);

    // SQL query to insert ministry page data into the database
    $sql = "INSERT INTO ministries (name, image, video_url, description) 
            VALUES ('$ministry_name', '$target_file', '$video_url', '$description')";
    
    if (mysqli_query($conn, $sql)) {
        echo "New ministry page created successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ministry Page</title>
    <style>
        /* Add styles similar to the ones used in the ministry pages */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: #2a2a72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1e1e5b;
        }
    </style>
</head>
<body>
    <h1>Create Ministry Page</h1>
    <form action="admin_create_page.php" method="POST" enctype="multipart/form-data">
        <label for="ministry_name">Ministry Name:</label>
        <input type="text" name="ministry_name" id="ministry_name" required>

        <label for="ministry_image">Ministry Image:</label>
        <input type="file" name="ministry_image" id="ministry_image" required>

        <label for="video_url">Video URL (e.g., YouTube):</label>
        <input type="text" name="video_url" id="video_url">

        <label for="description">Ministry Description:</label>
        <textarea name="description" id="description" rows="5" required></textarea>

        <button type="submit">Create Ministry Page</button>
    </form>
</body>
</html>
