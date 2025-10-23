<?php
include 'db_connect.php'; // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $uploadDir = 'uploads/event_gallery/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $file_path = $uploadDir . $file_name;

        if (move_uploaded_file($tmp_name, $file_path)) {
            $stmt = $conn->prepare("INSERT INTO event_gallery (event_name, image_path) VALUES (?, ?)");
            $stmt->bind_param("ss", $event_name, $file_path);
            $stmt->execute();
        }
    }
    echo "Images uploaded successfully!";
}
?>
