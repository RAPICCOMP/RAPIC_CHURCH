<?php
include 'db_connect.php'; // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $youtube_link = $_POST['youtube_link'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO event_videos (event_name, youtube_link, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $event_name, $youtube_link, $description);

    if ($stmt->execute()) {
        echo "Video link added successfully!";
    } else {
        echo "Error adding video link.";
    }
}
?>
