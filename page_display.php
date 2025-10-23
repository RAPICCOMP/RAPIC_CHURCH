<?php
// Fetch page data
$page_id = $_GET['page_id'];
$stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
$stmt->execute([$page_id]);
$page = $stmt->fetch();

// Fetch slideshow images
$stmt = $pdo->prepare("SELECT * FROM slideshow_images WHERE page_id = ?");
$stmt->execute([$page_id]);
$slideshow_images = $stmt->fetchAll();

// Fetch gallery images
$stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE page_id = ?");
$stmt->execute([$page_id]);
$gallery_images = $stmt->fetchAll();

// Fetch meetings
$stmt = $pdo->prepare("SELECT * FROM meetings WHERE page_id = ?");
$stmt->execute([$page_id]);
$meetings = $stmt->fetchAll();

// Fetch video URL
$stmt = $pdo->prepare("SELECT * FROM videos WHERE page_id = ?");
$stmt->execute([$page_id]);
$video = $stmt->fetch();

// Render the page dynamically (use similar HTML structure as in your previous code)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page['title']); ?></title>
    <!-- Add your CSS styling here -->
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($page['title']); ?></h1>
    </header>

    <div class="container">
        <!-- Slideshow Section -->
        <div class="section slideshow">
            <?php foreach ($slideshow_images as $image): ?>
                <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Slideshow Image">
            <?php endforeach; ?>
        </div>

        <!-- Gallery Section -->
        <div class="section">
            <h2>Gallery</h2>
            <div class="gallery">
                <?php foreach ($gallery_images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Gallery Image">
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Meetings Section -->
        <div class="section">
            <h2>Upcoming Meetings</h2>
            <?php foreach ($meetings as $meeting): ?>
                <div class="meeting-card">
                    <h3><?php echo htmlspecialchars($meeting['meeting_title']); ?></h3>
                    <p>Date: <?php echo htmlspecialchars($meeting['meeting_date']); ?></p>
                    <p>Location: <?php echo htmlspecialchars($meeting['location']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Video Section -->
        <div class="section">
            <h2>Videos</h2>
            <iframe src="<?php echo htmlspecialchars($video['video_url']); ?>" frameborder="0"></iframe>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Remnant Women of Faith Ministry. All rights reserved.</p>
    </footer>
</body>
</html>
