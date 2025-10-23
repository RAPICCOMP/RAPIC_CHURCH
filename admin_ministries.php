<?php
// Security and session
session_start();
$_SESSION['is_admin'] = true;
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit;
}
include('db_connect.php');

// Handle form submission for adding/updating ministries
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_card'])) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';
    $image = '';
    if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0) {
        $targetDir = 'uploads/ministry_images/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $filename = basename($_FILES['image_upload']['name']);
        $targetFile = $targetDir . time() . '_' . $filename;
        if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        } else {
            $error = 'Image upload failed.';
        }
    }
    if (!empty($name) && !empty($image)) {
        $stmt = $conn->prepare("INSERT INTO ministries (name, image, description, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $image, $description, $content);
        $stmt->execute();
        $stmt->close();
        $success = 'Ministry card created!';
    } else {
        $error = 'Ministry name and image are required.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Ministries</title>
    <style>
        body { background: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); padding: 30px; }
        h1 { text-align: center; color: #23295e; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; gap: 18px; }
        label { font-weight: 600; color: #23295e; }
        input, textarea { padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; }
        textarea { resize: vertical; min-height: 60px; }
        button { background: #23295e; color: #fff; border: none; border-radius: 6px; padding: 12px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #f4d03f; color: #23295e; }
        .success { color: green; text-align: center; margin-bottom: 10px; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .cards-section { display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start; margin-top: 30px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); overflow: hidden; padding: 15px; min-width: 220px; max-width: 340px; flex: 1 1 220px; display: flex; flex-direction: column; align-items: center; margin-bottom: 10px; }
        .card img { width: 100%; height: 120px; object-fit: cover; border-radius: 8px; background: #fff; }
        .card h3 { font-size: 1.15rem; color: #23295e; margin: 10px 0 5px 0; text-align: center; }
        .card p { font-size: 0.98rem; color: #555; margin: 5px 0; text-align: center; }
        .card a { display: inline-block; margin-top: 10px; padding: 8px 18px; background-color: #23295e; color: white; text-decoration: none; border-radius: 4px; font-size: 1rem; }
        .card a:hover { background-color: #f4d03f; color: #23295e; }
        @media (max-width: 768px) { .container { max-width: 99vw; padding: 10px; } .cards-section { flex-direction: column; gap: 10px; } .card { min-width: 180px; max-width: 98vw; padding: 10px; } .card img { height: 80px; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin - Manage Ministries</h1>
        <?php if (!empty($success)) echo '<div class="success">' . htmlspecialchars($success) . '</div>'; ?>
        <?php if (!empty($error)) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>
        <form method="post" enctype="multipart/form-data" action="">
            <label for="name">Ministry Name *</label>
            <input type="text" id="name" name="name" required>
            <label for="image_upload">Pick an image from your device gallery:</label><br>
            <input type="file" name="image_upload" id="image_upload" accept="image/*" capture="environment" required>
            <label for="description">Short Description</label>
            <textarea id="description" name="description"></textarea>
            <label for="content">Full Content (for ministry page)</label>
            <textarea id="content" name="content"></textarea>
            <button type="submit" name="create_card">Create Ministry Card</button>
        </form>
        <!-- Admin Forms for updating ministry sections -->
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <div class="section" style="background:#f4f4f4; margin-top:30px; border-radius:10px; padding:20px;">
            <h2>Admin: Update Ministry Content</h2>
            <?php
            $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC");
            ?>
            <form method="post" enctype="multipart/form-data" style="margin-bottom:18px;">
                <h3>Add Gallery Image</h3>
                <label for="ministry_select_gallery">Select Ministry:</label>
                <select name="ministry_id_gallery" id="ministry_select_gallery" required>
                    <option value="">-- Select Ministry --</option>
                    <?php while ($m = $ministries_result->fetch_assoc()): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                    <?php endwhile; ?>
                </select><br>
                <label for="gallery_image">Pick an image from your device gallery:</label><br>
                <input type="file" name="gallery_image" id="gallery_image" accept="image/*" capture="environment" required>
                <button type="submit" name="add_gallery">Add Image</button>
            </form>
            <?php $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC"); ?>
            <form method="post" style="margin-bottom:18px;">
                <h3>Add YouTube or MP4 Video</h3>
                <label for="ministry_select_video">Select Ministry:</label>
                <select name="ministry_id_video" id="ministry_select_video" required>
                    <option value="">-- Select Ministry --</option>
                    <?php while ($m = $ministries_result->fetch_assoc()): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                    <?php endwhile; ?>
                </select><br>
                <label for="video_title">Video Title:</label><br>
                <input type="text" name="video_title" id="video_title" placeholder="Video Title" required><br>
                <label for="video_url">YouTube Link or MP4 URL:</label><br>
                <input type="text" name="video_url" id="video_url" placeholder="Paste YouTube link or MP4 URL here" required><br>
                <label for="video_desc">Description (optional):</label><br>
                <textarea name="video_desc" id="video_desc" rows="2" placeholder="Video Description"></textarea><br>
                <button type="submit" name="add_video">Add Video</button>
            </form>
            <?php $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC"); ?>
            <form method="post" enctype="multipart/form-data" style="margin-bottom:18px;">
                <h3>Update Description</h3>
                <label for="ministry_select_desc">Select Ministry:</label>
                <select name="ministry_id_desc" id="ministry_select_desc" required>
                    <option value="">-- Select Ministry --</option>
                    <?php while ($m = $ministries_result->fetch_assoc()): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                    <?php endwhile; ?>
                </select><br>
                <textarea name="description" rows="4" style="width:100%;"></textarea>
                <h3>Update Full Content</h3>
                <textarea name="content" rows="6" style="width:100%;"></textarea>
                <button type="submit" name="update_ministry">Update Ministry</button>
            </form>
            <?php $ministries_result = $conn->query("SELECT id, name FROM ministries ORDER BY name ASC"); ?>
            <form method="post">
                <h3>Add Meeting</h3>
                <label for="ministry_select_meeting">Select Ministry:</label>
                <select name="ministry_id_meeting" id="ministry_select_meeting" required>
                    <option value="">-- Select Ministry --</option>
                    <?php while ($m = $ministries_result->fetch_assoc()): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                    <?php endwhile; ?>
                </select><br>
                <input type="text" name="meeting_title" placeholder="Meeting Title" required>
                <textarea name="meeting_desc" rows="2" placeholder="Meeting Description" required></textarea>
                <input type="date" name="meeting_date" required>
                <button type="submit" name="add_meeting">Add Meeting</button>
            </form>
        </div>
        <?php endif; ?>
        <hr style="margin:40px 0;">
        <h2>Existing Ministries</h2>
        <div class="cards-section">
            <?php
            $result = $conn->query("SELECT * FROM ministries ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card">';
                if (!empty($row['image'])) {
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                }
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<a href="ministry_page.php?id=' . $row['id'] . '">View Ministry Page</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
