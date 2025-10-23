<?php
include 'db_connect.php';

// Handle form submission to add or update sermons
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $speaker = $conn->real_escape_string($_POST['speaker']);
    $series = $conn->real_escape_string($_POST['series']);
    $topic = $conn->real_escape_string($_POST['topic']);
    $content = $conn->real_escape_string($_POST['content']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $imagePath = "";  // Default to empty for image

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageDir = "uploads/";  // Directory for storing images
        $imageFileName = basename($_FILES["image"]["name"]);
        $imagePath = $imageDir . $imageFileName;

        // Move the uploaded file to the desired directory
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    if ($id > 0) {
        // Update existing sermon
        $sql = "UPDATE sermons_library SET title='$title', speaker='$speaker', series='$series', topic='$topic', content='$content', image='$imagePath' WHERE id=$id";
    } else {
        // Insert new sermon
        $sql = "INSERT INTO sermons_library (title, speaker, series, topic, content, image) VALUES ('$title', '$speaker', '$series', '$topic', '$content', '$imagePath')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Sermon saved successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch sermons
$sql = "SELECT * FROM sermons_library ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a2e;
            color: #fff;
            margin: 0;
            padding: 0;
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
        .form-container {
            background: #23395d;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            display: none;
        }
        .form-container input, .form-container button, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .form-container input, .form-container textarea {
            background: #fff;
            color: #000;
        }
        .form-container button {
            background: #e94560;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
        .buttons-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            padding: 20px;
        }
        .dashboard-button {
            background: #e94560;
            padding: 15px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            cursor: pointer;
        }
        .dashboard-button:hover {
            background: #ff2e63;
        }
        .sermon-list {
            padding: 20px;
            background-color: #23395d;
            border-radius: 10px;
            margin-top: 20px;
        }
        .sermon-item {
            background-color: #0f3460;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sermon-item button {
            background-color: #e94560;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .sermon-item button:hover {
            background-color: #ff2e63;
        }
        .sermon-item img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            margin-right: 10px;
        }
    </style>
    <script>
        function toggleForm(formId) {
            var form = document.getElementById(formId);
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

        function loadPage(page) {
            if (page === "manage") {
                toggleForm("manageSermons");
            }
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="#" onclick="loadPage('dashboard')">Dashboard</a>
        <a href="#" onclick="loadPage('add')">Add Sermon</a>
        <a href="#" onclick="loadPage('manage')">Manage Sermons</a>
        <a href="#">Logout</a>
    </div>
    
    <div class="main-content">
        <div class="dashboard-container">
            <h2>Welcome to the Admin Dashboard</h2>

            <!-- Add Sermon Form -->
            <div id="sermonForm" class="form-container">
                <h3>Add or Update Sermon</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="sermon_id">
                    <label>Title:</label>
                    <input type="text" name="title" required>

                    <label>Speaker:</label>
                    <input type="text" name="speaker" required>

                    <label>Series:</label>
                    <input type="text" name="series">

                    <label>Topic:</label>
                    <input type="text" name="topic">

                    <label>Content:</label>
                    <textarea name="content" required></textarea>

                    <label>Upload Image:</label>
                    <input type="file" name="image" accept="image/*">

                    <button type="submit">Save Sermon</button>
                </form>
            </div>

            <!-- Manage Sermons Section -->
            <div id="manageSermons" class="sermon-list">
                <h3>Manage Sermons</h3>
                <ul>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <li class="sermon-item">
            <?php if (!empty($row['image'])) { ?>
                <img src="<?php echo $row['image']; ?>" alt="Sermon Image">
            <?php } else { ?>
                <img src="default-image.jpg" alt="Default Sermon Image">
            <?php } ?>
            <span><?php echo $row['title']; ?></span>
            <a href="sermon.php?id=<?php echo $row['id']; ?>" style="color: #e94560;">View</a>
            <a href="edit_sermon.php?id=<?php echo $row['id']; ?>" style="color: #ff2e63;">[Edit]</a>
        </li>
    <?php } ?>
</ul>


<?php
include 'db_connect.php';

// Handle form submission to add or update sermons
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $speaker = $conn->real_escape_string($_POST['speaker']);
    $series = $conn->real_escape_string($_POST['series']);
    $topic = $conn->real_escape_string($_POST['topic']);
    $content = $conn->real_escape_string($_POST['content']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        // Update existing sermon
        $sql = "UPDATE sermons_library SET title='$title', speaker='$speaker', series='$series', topic='$topic', content='$content' WHERE id=$id";
    } else {
        // Insert new sermon
        $sql = "INSERT INTO sermons_library (title, speaker, series, topic, content) VALUES ('$title', '$speaker', '$series', '$topic', '$content')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Sermon saved successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch sermons
$sql = "SELECT * FROM sermons_library ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h1 style="font-size: 3rem; text-align: left; margin-top: 30px;">RAPIC Sermons</h1>
    
    <div>
        <h2>Manage Sermons</h2>
        <form method="post" action="" id="sermonForm" style="display: none;">
            <input type="hidden" name="id" id="sermon_id">
            <label>Title:</label>
            <input type="text" name="title" required>
            
            <label>Speaker:</label>
            <input type="text" name="speaker" required>
            
            <label>Series:</label>
            <input type="text" name="series">
            
            <label>Topic:</label>
            <input type="text" name="topic">
            
            <label>Content:</label>
            <textarea name="content" required></textarea>
            
            <button type="submit">Save Sermon</button>
        </form>
    </div>

    <h2>Available Sermons</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li class="sermon-item">
                <?php if (!empty($row['image'])) { ?>
                    <img src="<?php echo $row['image']; ?>" alt="Sermon Image">
                <?php } else { ?>
                    <img src="default-image.jpg" alt="Default Sermon Image">
                <?php } ?>
                <span><?php echo $row['title']; ?></span>
                
                <!-- Edit button triggers the form to appear -->
                <button onclick="editSermon(<?php echo $row['id']; ?>)">[Edit]</button>
            </li>
        <?php } ?>
    </ul>
</div>

<script>
// JavaScript to handle the editing process
function editSermon(id) {
    // Show the form
    document.getElementById('sermonForm').style.display = 'block';
    
    // Fetch the sermon data using the sermon ID
    const sermonData = <?php echo json_encode($result->fetch_all(MYSQLI_ASSOC)); ?>;
    const sermon = sermonData.find(s => s.id === id);

    if (sermon) {
        // Pre-populate the form with the sermon data
        document.getElementById('sermon_id').value = sermon.id;
        document.querySelector('input[name="title"]').value = sermon.title;
        document.querySelector('input[name="speaker"]').value = sermon.speaker;
        document.querySelector('input[name="series"]').value = sermon.series;
        document.querySelector('input[name="topic"]').value = sermon.topic;
        document.querySelector('textarea[name="content"]').value = sermon.content;
    }
}
</script>


            </div>
            
        </div>
    </div>
</body>
</html>
