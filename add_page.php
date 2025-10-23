<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Add a New Page</h2>
    <form action="process_page.php" method="POST">
        <label>Page Title:</label>
        <input type="text" name="title" required><br>

        <label>Content:</label>
        <textarea name="content" required></textarea><br>

        <label>Video URL (optional):</label>
        <input type="text" name="video_url"><br>

        <button type="submit">Save Page</button>
    </form>
</body>
</html>
