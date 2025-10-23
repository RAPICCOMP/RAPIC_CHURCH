<?php
include('db_connect.php');

// Check if we're editing an existing sermon
$sermon = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM sermons WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $sermon = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video_url = $_POST['video_url'];

    // Handle image upload
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $image_name = $_FILES['image_url']['name'];
        $image_tmp = $_FILES['image_url']['tmp_name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Validate image extension (optional)
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_ext), $allowed_ext)) {
            $image_new_name = uniqid() . '.' . $image_ext;
            $image_path = 'uploads/' . $image_new_name;
            move_uploaded_file($image_tmp, $image_path);
        } else {
            echo "Invalid image format.";
            exit();
        }
    }

    // Insert or update sermon in the database
    if ($sermon) {
        // Update existing sermon
        $update_sql = "UPDATE sermons SET title = '$title', description = '$description', image_url = '$image_path', video_url = '$video_url' WHERE id = $id";
        if (mysqli_query($conn, $update_sql)) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error updating sermon: " . mysqli_error($conn);
        }
    } else {
        // Insert new sermon
        $insert_sql = "INSERT INTO sermons (title, description, image_url, video_url) VALUES ('$title', '$description', '$image_path', '$video_url')";
        if (mysqli_query($conn, $insert_sql)) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error adding sermon: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Add Sermon</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a2e;
            color: #fff;
        }

        header {
            background-color: #16213e;
            color: #e94560;
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            margin: 0;
            padding: 10px;
            font-size: 24px;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #16213e;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }

        .sidebar h2 {
            color: #e94560;
            text-align: center;
            font-size: 24px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 10px 0;
            background: #0f3460;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #e94560;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #23395d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form input[type="text"],
        form textarea,
        form input[type="file"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form textarea {
            resize: vertical;
            height: 100px;
        }

        form button {
            align-self: flex-start;
            width: 200px;
            font-size: 16px;
            background-color: #e94560;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #ff2e63;
        }

        .error {
            color: #ff0000;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Sermon Library</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="sermonlibrary.php">Sermons Library</a>
    <a href="admin_add_testimonial.php">Testimonial Section</a>
    <a href="sermon_links.php">Drop Down</a>
    <a href="admin_add_sermon.php">Sermon Library</a>
    <a href="edit_sermon.php">trdcgh</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="edit_sermon.php">Recent Sermons</a>
</div>

<div class="main-content">
    <header>
        <h1><?php echo $sermon ? 'Edit Sermon' : 'Add New Sermon'; ?></h1>
    </header>

    <div class="container">
        <form action="edit_sermon.php<?php echo $sermon ? '?id=' . $sermon['id'] : ''; ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Sermon Title</label>
            <input type="text" name="title" value="<?php echo $sermon ? htmlspecialchars($sermon['title']) : ''; ?>" required>

            <label for="description">Description</label>
            <textarea name="description" required><?php echo $sermon ? htmlspecialchars($sermon['description']) : ''; ?></textarea>

            <label for="image_url">Image</label>
            <input type="file" name="image_url" <?php echo $sermon ? '' : 'required'; ?>>

            <label for="video_url">Video URL</label>
            <input type="text" name="video_url" value="<?php echo $sermon ? htmlspecialchars($sermon['video_url']) : ''; ?>" required>

            <button type="submit"><?php echo $sermon ? 'Update Sermon' : 'Add Sermon'; ?></button>
        </form>
    </div>

    <div class="actions">
    <a href="edit_sermon.php?id=<?php echo $sermon['id']; ?>">Edit</a> |
    <a href="delete_sermon.php?id=<?php echo $sermon['id']; ?>" 
       onclick="return confirm('Are you sure you want to delete this sermon?')">Delete</a>
</div>

</div>

</body>
</html>
