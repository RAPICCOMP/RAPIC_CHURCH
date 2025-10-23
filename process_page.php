<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $navbar_links = $_POST['navbar_links'];
    $hero_title = $_POST['hero_title'];
    $hero_subtitle = $_POST['hero_subtitle'];
    $hero_image = $_POST['hero_image'];
    $sections = json_encode($_POST['sections']);

    $sql = "INSERT INTO church_pages (title, navbar_links, hero_title, hero_subtitle, hero_image, sections) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $navbar_links, $hero_title, $hero_subtitle, $hero_image, $sections]);

    echo "Page created successfully! <a href='admin_dashboard.php'>Back to Dashboard</a>";
}
?>
