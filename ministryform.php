<?php
// REMOVE or comment out the admin check for development
session_start();
include('db_connect.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add Slideshow Images (multiple) - now using ministry_slideshow table
if (isset($_POST['add_slideshow_image']) && isset($_FILES['slideshow_image'])) {
    $ministry_id = intval($_POST['ministry_id_slideshow']);
    $targetDir = 'uploads/ministry_slideshow/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $files = $_FILES['slideshow_image'];
    $count = is_array($files['name']) ? count($files['name']) : 0;
    $success = false;
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] == 0) {
            $filename = basename($files['name'][$i]);
            $targetFile = $targetDir . time() . '_' . $filename;
            if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                $conn->query("INSERT INTO ministry_slideshow (ministry_id, image) VALUES ($ministry_id, '$targetFile')");
                $success = true;
            }
        }
    }
    if ($success) {
        echo '<div style="color:green;text-align:center;">Slideshow images uploaded successfully.</div>';
    } else {
        echo '<div style="color:red;text-align:center;">Slideshow image upload failed.</div>';
    }
}

// Add Gallery Images (multiple)
if (isset($_POST['add_gallery_image']) && isset($_FILES['gallery_image'])) {
    $ministry_id = intval($_POST['ministry_id_gallery']);
    $targetDir = 'uploads/ministry_gallery/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $files = $_FILES['gallery_image'];
    $count = is_array($files['name']) ? count($files['name']) : 0;
    $success = false;
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] == 0) {
            $filename = basename($files['name'][$i]);
            $targetFile = $targetDir . time() . '_' . $filename;
            if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                $conn->query("INSERT INTO ministry_gallery (ministry_id, image) VALUES ($ministry_id, '$targetFile')");
                $success = true;
            }
        }
    }
    if ($success) {
        echo '<div style="color:green;text-align:center;">Gallery images uploaded successfully.</div>';
    } else {
        echo '<div style="color:red;text-align:center;">Gallery image upload failed.</div>';
    }
}

// Add Video (single)
if (isset($_POST['add_video'])) {
    $ministry_id = intval($_POST['ministry_id_video']);
    $title = $conn->real_escape_string($_POST['video_title']);
    $url = $conn->real_escape_string($_POST['video_url']);
    $desc = $conn->real_escape_string($_POST['video_desc']);

    $check = $conn->query("SELECT id FROM ministry_videos WHERE ministry_id=$ministry_id AND video_url='$url' LIMIT 1");
    if ($check && $check->num_rows > 0) {
        echo '<div style="color:red;text-align:center;">This video already exists for the selected ministry.</div>';
    } else {
        $conn->query("INSERT INTO ministry_videos (ministry_id, title, video_url, description) VALUES ($ministry_id, '$title', '$url', '$desc')");
        echo '<div style="color:green;text-align:center;">Video added successfully.</div>';
    }
}

// Add Meeting (single)
if (isset($_POST['add_meeting'])) {
    $ministry_id = intval($_POST['ministry_id_meeting']);
    $title = $conn->real_escape_string($_POST['meeting_title']);
    $desc = $conn->real_escape_string($_POST['meeting_desc']);
    $date = $conn->real_escape_string($_POST['meeting_date']);
    $conn->query("INSERT INTO ministry_meetings (ministry_id, title, description, meeting_date) VALUES ($ministry_id, '$title', '$desc', '$date')");
    echo '<div style="color:green;text-align:center;">Meeting added successfully.</div>';
}

// Delete Section (updated for ministry_slideshow)
if (isset($_POST['delete_section'])) {
    $ministry_id = intval($_POST['ministry_id_delete']);
    $section = $_POST['section_to_delete'];
    $item_id = intval($_POST['item_id']);
    if ($section == 'slideshow') {
        // Delete from ministry_slideshow table
        $conn->query("DELETE FROM ministry_slideshow WHERE id=$item_id AND ministry_id=$ministry_id");
    } elseif ($section == 'gallery') {
        $conn->query("DELETE FROM ministry_gallery WHERE id=$item_id AND ministry_id=$ministry_id");
    } elseif ($section == 'video') {
        $conn->query("DELETE FROM ministry_videos WHERE id=$item_id AND ministry_id=$ministry_id");
    } elseif ($section == 'meeting') {
        $conn->query("DELETE FROM ministry_meetings WHERE id=$item_id AND ministry_id=$ministry_id");
    }
    echo '<div style="color:green;text-align:center;">Item deleted successfully.</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Edit Ministry Sections</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        .section { max-width: 700px; margin: 30px auto; background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        h2, h3 { color: #333; }
        label { font-weight: bold; }
        form { margin-bottom: 32px; }
        input, select, textarea, button { margin: 8px 0 16px 0; width: 100%; padding: 8px; }
        button { width: auto; background: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #555; }
    </style>
</head>
<body>
<div class="section">
    <h2>Admin: Edit Ministry Sections</h2>
    <?php
    $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC");
    ?>

    <!-- Slideshow Images Form (multiple) -->
    <form method="post" enctype="multipart/form-data">
        <h3>Edit Slideshow Images</h3>
        <label>Select Ministry:</label>
        <select name="ministry_id_slideshow" required>
            <option value="">-- Select Ministry --</option>
            <?php while ($m = $ministries_result->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <label>Upload New Slideshow Images:</label>
        <input type="file" name="slideshow_image[]" accept="image/*" multiple required>
        <button type="submit" name="add_slideshow_image">Add Images</button>
    </form>

    <?php $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC"); ?>
    <!-- Gallery Images Form (multiple) -->
    <form method="post" enctype="multipart/form-data">
        <h3>Edit Gallery Event Pictures</h3>
        <label>Select Ministry:</label>
        <select name="ministry_id_gallery" required>
            <option value="">-- Select Ministry --</option>
            <?php while ($m = $ministries_result->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <label>Upload New Gallery Images:</label>
        <input type="file" name="gallery_image[]" accept="image/*" multiple required>
        <button type="submit" name="add_gallery_image">Add Images</button>
    </form>

    <?php $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC"); ?>
    <!-- Events Videos Form -->
    <form method="post">
        <h3>Edit Events Videos</h3>
        <label>Select Ministry:</label>
        <select name="ministry_id_video" required>
            <option value="">-- Select Ministry --</option>
            <?php while ($m = $ministries_result->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <label>Video Title:</label>
        <input type="text" name="video_title" required>
        <label>Video URL (YouTube or MP4):</label>
        <input type="text" name="video_url" required>
        <label>Description:</label>
        <textarea name="video_desc" rows="2"></textarea>
        <button type="submit" name="add_video">Add Video</button>
    </form>

    <?php $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC"); ?>
    <!-- Upcoming Meetings Form -->
    <form method="post">
        <h3>Edit Upcoming Meetings</h3>
        <label>Select Ministry:</label>
        <select name="ministry_id_meeting" required>
            <option value="">-- Select Ministry --</option>
            <?php while ($m = $ministries_result->fetch_assoc()): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <label>Meeting Title:</label>
        <input type="text" name="meeting_title" required>
        <label>Description:</label>
        <textarea name="meeting_desc" rows="2" required></textarea>
        <label>Date:</label>
        <input type="date" name="meeting_date" required>
        <button type="submit" name="add_meeting">Add Meeting</button>
    </form>

    <!-- Delete Section Form -->
    <form method="post">
        <h3>Delete Section</h3>
        <label>Select Section to Delete:</label>
        <select name="section_to_delete" required>
            <option value="">-- Select Section --</option>
            <option value="slideshow">Slideshow Image</option>
            <option value="gallery">Gallery Image</option>
            <option value="video">Event Video</option>
            <option value="meeting">Upcoming Meeting</option>
        </select>
        <label>ID of Item to Delete:</label>
        <input type="number" name="item_id" required>
        <label>Ministry ID:</label>
        <input type="number" name="ministry_id_delete" required>
        <button type="submit" name="delete_section">Delete</button>
    </form>
</div>
</body>
</html>