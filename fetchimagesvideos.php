<?php
// Include the database connection
include('db_connect.php');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch sermons from the database
$sql = "SELECT * FROM sermons";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($sermon = mysqli_fetch_assoc($result)) {
        echo '<h3>' . htmlspecialchars($sermon['title']) . '</h3>';
        echo '<p>' . htmlspecialchars($sermon['description']) . '</p>';
        
        // Display Image
        if ($sermon['image_url']) {
            echo '<img src="uploads/images/' . htmlspecialchars($sermon['image_url']) . '" alt="Sermon Image">';
        }
        
        // Display Video
        if ($sermon['video_url']) {
            echo '<video controls>';
            echo '<source src="uploads/videos/' . htmlspecialchars($sermon['video_url']) . '" type="video/mp4">';
            echo 'Your browser does not support the video tag.';
            echo '</video>';
        }
    }
} else {
    echo "Error fetching sermons: " . mysqli_error($conn);
}
?>
