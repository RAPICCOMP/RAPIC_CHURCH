<?php
// Fetch ministry pages from the database
$query = "SELECT * FROM ministries";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo "<h2>" . $row['name'] . "</h2>";

    // Display Slideshow
    $slideshowImages = explode(',', $row['slideshow_images']);
    foreach ($slideshowImages as $image) {
        echo "<img src='$image' alt='Slideshow Image' style='width:100%; height:auto;'>";
    }

    // Display Gallery
    $galleryImages = explode(',', $row['event_gallery']);
    foreach ($galleryImages as $image) {
        echo "<img src='$image' alt='Gallery Image' style='width:100%; height:auto;'>";
    }

    // Display Video
    echo "<div class='video-section'>
            <iframe width='100%' height='500px' src='https://www.youtube.com/embed/" . extract_video_id($row['video_link']) . "' frameborder='0' allowfullscreen></iframe>
          </div>";
}

// Function to extract video ID from YouTube URL
function extract_video_id($url) {
    preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
    return $matches[1];
}
?>
