<?php
include 'db_connect.php'; // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meeting_title = $_POST['meeting_title'];
    $meeting_description = $_POST['meeting_description'];

    // Insert meeting data into the database
    $stmt = $conn->prepare("INSERT INTO women_meetings (meeting_title, meeting_description) VALUES (?, ?)");
    $stmt->bind_param("ss", $meeting_title, $meeting_description);

    if ($stmt->execute()) {
        echo "Meeting added successfully!";
    } else {
        echo "Error adding meeting.";
    }
}
?>
