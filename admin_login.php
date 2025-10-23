<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <form action="admin_login.php" method="POST">
        <h2>Admin Login</h2>
        <label for="username">Username</label>
        <input type="text" name="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
<form action="admin_add_sermon.php" method="POST" enctype="multipart/form-data">
    <label for="title">Sermon Title</label>
    <input type="text" name="title" required>

    <label for="description">Description</label>
    <textarea name="description" required></textarea>

    <label for="image_url">Image</label>
    <input type="file" name="image_url" accept="image/*">

    <label for="video_url">Video</label>
    <input type="file" name="video_url" accept="video/*">

    <button type="submit" name="submit">Add Sermon</button>
</form>

