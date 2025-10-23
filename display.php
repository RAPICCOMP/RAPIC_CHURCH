<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "church_website");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch images from the database
$sql = "SELECT * FROM images";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<img src="'.$row['image_path'].'" width="300px" alt="Church Image">';
    }
} else {
    echo "No images found!";
}

$conn->close();
?>
