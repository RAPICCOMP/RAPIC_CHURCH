<?php
include 'db_connect.php';

// Fetch Women Ministries
$ministries = $conn->query("SELECT * FROM women_ministries");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Women Ministries</title>
</head>
<body>

<?php while ($ministry = $ministries->fetch_assoc()): ?>
    <h2><?php echo $ministry['name']; ?></h2>
    <p><?php echo $ministry['description']; ?></p>

    <!-- Slideshow -->
    <div class="slideshow">
        <?php
        $images = $conn->query("SELECT * FROM women_gallery_section WHERE ministry_id = " . $ministry['id']);
        while ($img = $images->fetch_assoc()) {
            echo "<img src='{$img['image_path']}' alt='Image'>";
        }
        ?>
    </div>

    <!-- Gallery -->
    <div class="gallery">
        <?php
        $images = $conn->query("SELECT * FROM women_gallery_section WHERE ministry_id = " . $ministry['id']);
        while ($img = $images->fetch_assoc()) {
            echo "<img src='{$img['image_path']}' alt='Image'>";
        }
        ?>
    </div>

    <!-- Videos -->
    <div class="videos">
        <?php
        $videos = $conn->query("SELECT * FROM women_video_section WHERE ministry_id = " . $ministry['id']);
        while ($video = $videos->fetch_assoc()) {
            echo "<iframe width='560' height='315' src='{$video['video_url']}' frameborder='0' allowfullscreen></iframe>";
        }
        ?>
    </div>

<?php endwhile; ?>

</body>
</html>
