<?php
include 'db_connect.php';
$stmt = $pdo->query("SELECT * FROM church_pages ORDER BY created_at DESC");
$pages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Pages</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>All Church Pages</h2>
    <ul>
        <?php foreach ($pages as $page): ?>
            <li>
                <a href="display_page.php?id=<?php echo $page['id']; ?>">
                    <?php echo htmlspecialchars($page['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
