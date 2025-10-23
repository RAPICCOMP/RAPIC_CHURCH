<?php
include 'db_connect.php'; // Include your database connection

// Form submission logic (simplified)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Insert page data into the database
    $stmt = $pdo->prepare("INSERT INTO pages (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);

    $page_id = $pdo->lastInsertId(); // Get the last inserted page ID

    // Insert slideshow images
    if (!empty($_FILES['slideshow_images']['name'][0])) {
        foreach ($_FILES['slideshow_images']['name'] as $key => $image_name) {
            $image_url = 'uploads/' . basename($image_name);
            move_uploaded_file($_FILES['slideshow_images']['tmp_name'][$key], $image_url);

            $stmt = $pdo->prepare("INSERT INTO slideshow_images (page_id, image_url) VALUES (?, ?)");
            $stmt->execute([$page_id, $image_url]);
        }
    }

    // Insert gallery images
    if (!empty($_FILES['gallery_images']['name'][0])) {
        foreach ($_FILES['gallery_images']['name'] as $key => $image_name) {
            $image_url = 'uploads/' . basename($image_name);
            move_uploaded_file($_FILES['gallery_images']['tmp_name'][$key], $image_url);

            $stmt = $pdo->prepare("INSERT INTO gallery_images (page_id, image_url) VALUES (?, ?)");
            $stmt->execute([$page_id, $image_url]);
        }
    }

    // Insert meeting information
    $meetings = $_POST['meetings'];
    foreach ($meetings as $meeting) {
        $stmt = $pdo->prepare("INSERT INTO meetings (page_id, meeting_title, meeting_date, location) VALUES (?, ?, ?, ?)");
        $stmt->execute([$page_id, $meeting['title'], $meeting['date'], $meeting['location']]);
    }

    // Insert video URL
    $video_url = $_POST['video_url'];
    if (!empty($video_url)) {
        $stmt = $pdo->prepare("INSERT INTO videos (page_id, video_url) VALUES (?, ?)");
        $stmt->execute([$page_id, $video_url]);
    }
}
?>

<form action="admin_page.php" method="POST" enctype="multipart/form-data">
    <!-- Page Title and Content -->
    <label for="title">Page Title</label>
    <input type="text" name="title" id="title" required><br><br>

    <label for="content">Content</label>
    <textarea name="content" id="content" required></textarea><br><br>

    <!-- Slideshow Images -->
    <label for="slideshow_images">Slideshow Images</label>
    <input type="file" name="slideshow_images[]" multiple><br><br>

    <!-- Gallery Images -->
    <label for="gallery_images">Gallery Images</label>
    <input type="file" name="gallery_images[]" multiple><br><br>

    <!-- Meetings -->
    <div id="meetings">
        <h3>Upcoming Meetings</h3>
        <div class="meeting">
            <label for="meeting_title">Meeting Title</label>
            <input type="text" name="meetings[0][title]" required><br><br>

            <label for="meeting_date">Meeting Date</label>
            <input type="date" name="meetings[0][date]" required><br><br>

            <label for="location">Location</label>
            <input type="text" name="meetings[0][location]" required><br><br>
        </div>
        <!-- Add more meeting sections dynamically if needed -->
    </div>

    <!-- Video URL -->
    <label for="video_url">Video URL</label>
    <input type="url" name="video_url" id="video_url"><br><br>

    <button type="submit">Submit</button>
</form>
