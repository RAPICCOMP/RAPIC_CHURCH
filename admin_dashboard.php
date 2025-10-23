<?php
// Include your database connection file
include('db_connect.php');

// Fetch all sermons from the database
$sql = "SELECT * FROM sermons";
$result = mysqli_query($conn, $sql);

// Check if there are sermons in the database
$sermons = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sermons[] = $row;
    }
} else {
    $error_message = "No sermons found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a2e;
            color: #fff;
        }
        header {
            background-color: #16213e;
            color: #e94560;
            text-align: center;
            padding: 20px 0;
        }
        h1, h2 {
            margin: 0;
            padding: 10px;
        }
        h1 {
            font-size: 24px;
        }
        h2 {
            font-size: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #16213e;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }
        .sidebar h2 {
            color: #e94560;
            text-align: center;
            font-size: 24px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 10px 0;
            background: #0f3460;
            border-radius: 5px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #e94560;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #23395d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .button, a.button {
            background-color: #e94560;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
        }

        .button:hover, a.button:hover {
            background-color: #ff2e63;
        }

        .sermon-list {
            margin-top: 30px;
        }

        .sermon-list ul {
            list-style-type: none;
            padding: 0;
        }

        .sermon-list li {
            background-color: #23395d;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sermon-list li img {
            max-width: 150px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .sermon-list li .actions {
            font-size: 14px;
        }

        .sermon-list li .actions a {
            color: #007bff;
            text-decoration: none;
            margin-right: 15px;
        }

        .sermon-list li .actions a:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff0000;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            color: #28a745;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .sermon-list li {
                flex-direction: column;
                align-items: flex-start;
            }

            .sermon-list li img {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Sermon Library</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="sermonlibrary.php">Sermons Library</a>
    <a href="admin_add_testimonial.php">Testimonial Section</a>
    <a href="sermon_links.php">Drop Down</a>
    <a href="admin_add_sermon.php">Sermon Library</a>
    <a href="edit_sermon.php">trdcgh</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="admin_dashboard.php">Admin Dashboard</a>
    <a href="edit_sermon.php">Recent Sermons</a>
    <a href="#">Logout</a>
</div>

<div class="main-content">
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="container">
        <!-- Display Success/Error Message -->
        <?php if (isset($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>

        <a href="edit_sermon.php" class="button">Add New Sermon</a>

        <h2>Recent Sermons</h2>

        <!-- Display sermons -->
        <div class="sermon-list">
            <?php if (!empty($sermons)) { ?>
                <ul>
                    <?php foreach ($sermons as $sermon) { ?>
                        <li>
                            <strong><?php echo htmlspecialchars($sermon['title']); ?></strong>
                            <img src="<?php echo htmlspecialchars($sermon['image_url']); ?>" alt="Sermon Image">
                            <p><?php echo nl2br(htmlspecialchars($sermon['description'])); ?></p>
                            <p><a href="<?php echo htmlspecialchars($sermon['video_url']); ?>" target="_blank">Watch Video</a></p>

                            <div class="actions">
                                <a href="edit_sermon.php?id=<?php echo $sermon['id']; ?>">Edit</a> |
                                <a href="delete_sermon.php?id=<?php echo $sermon['id']; ?>" onclick="return confirm('Are you sure you want to delete this sermon?')">Delete</a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No sermons available yet.</p>
            <?php } ?>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2025 Simple Website. All rights reserved.</p>
</footer>

</body>
</html>

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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    <a href="admin_create_page.php">Create New Page</a>
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
