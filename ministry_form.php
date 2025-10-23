<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Insert page title
        $stmt = $pdo->prepare("INSERT INTO pages (title) VALUES (?)");
        $stmt->execute([$_POST['title']]);
        $page_id = $pdo->lastInsertId(); // Get the last inserted page ID

        // Handle slideshow images
        if (!empty($_FILES['slideshow_images']['name'][0])) {
            foreach ($_FILES['slideshow_images']['tmp_name'] as $key => $tmp_name) {
                $image_path = 'uploads/' . basename($_FILES['slideshow_images']['name'][$key]);
                move_uploaded_file($tmp_name, $image_path);
                $stmt = $pdo->prepare("INSERT INTO slideshow_images (page_id, image_url) VALUES (?, ?)");
                $stmt->execute([$page_id, $image_path]);
            }
        }

        // Handle gallery images
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                $image_path = 'uploads/' . basename($_FILES['gallery_images']['name'][$key]);
                move_uploaded_file($tmp_name, $image_path);
                $stmt = $pdo->prepare("INSERT INTO gallery_images (page_id, image_url) VALUES (?, ?)");
                $stmt->execute([$page_id, $image_path]);
            }
        }

        // Insert meeting details
        $stmt = $pdo->prepare("INSERT INTO meetings (page_id, meeting_title, meeting_date, location) VALUES (?, ?, ?, ?)");
        $stmt->execute([$page_id, $_POST['meeting_title'], $_POST['meeting_date'], $_POST['meeting_location']]);

        // Insert video URL
        if (!empty($_POST['video_url'])) {
            $stmt = $pdo->prepare("INSERT INTO videos (page_id, video_url) VALUES (?, ?)");
            $stmt->execute([$page_id, $_POST['video_url']]);
        }

        echo "Page added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>



<form action="add_page.php" method="post" enctype="multipart/form-data">
    <label>Page Title:</label>
    <input type="text" name="title" required>

    <label>Slideshow Images:</label>
    <input type="file" name="slideshow_images[]" multiple accept="image/*">

    <label>Gallery Images:</label>
    <input type="file" name="gallery_images[]" multiple accept="image/*">

    <label>Meeting Title:</label>
    <input type="text" name="meeting_title" required>

    <label>Meeting Date:</label>
    <input type="date" name="meeting_date" required>

    <label>Meeting Location:</label>
    <input type="text" name="meeting_location" required>

    <label>Video URL:</label>
    <input type="url" name="video_url">

    <button type="submit" name="submit">Add Page</button>
</form>
