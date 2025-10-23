<?php
include 'db_connect.php';

$sql = "SELECT * FROM womens_gallery_table ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<div class="section">
    <h2>Women's Gallery</h2>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="gallery-section">
            <h3><?php echo htmlspecialchars($row['category']); ?></h3>
            <div class="gallery">
                <?php if ($row['image']) { ?>
                    <img src="<?php echo $row['image']; ?>" alt="Gallery Image">
                <?php } ?>
                
                <?php if ($row['video_link']) { ?>
                    <iframe width="560" height="315"
                            src="https://www.youtube.com/embed/<?php echo getYouTubeID($row['video_link']); ?>"
                            frameborder="0" allowfullscreen>
                    </iframe>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php
// Function to extract YouTube Video ID
function getYouTubeID($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $params);
    return isset($params['v']) ? $params['v'] : '';
}
?>
