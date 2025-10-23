<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Contact Info</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h2>Update Contact Information</h2>
    <form action="add_contact.php" method="POST">
        <label for="phone">Phone Number</label>
        <input type="text" name="phone" required>

        <label for="email">Email Address</label>
        <input type="email" name="email" required>

        <label for="address">Address</label>
        <textarea name="address" required></textarea>

        <button type="submit" name="submit">Update Contact Info</button>
    </form>
</body>
</html>
