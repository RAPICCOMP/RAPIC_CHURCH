<?php
// Include your database connection file
include('db_connect.php'); // Assuming this file contains your database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $url = $_POST['url'];

    // Insert a new media link into the database
    if (isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO sermon_links (category, title, url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $category, $title, $url);
        if ($stmt->execute()) {
            $message = "New link added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch data for Series, Speakers, and Topics
$series = $conn->query("SELECT * FROM sermon_links WHERE category = 'series'");
$speakers = $conn->query("SELECT * FROM sermon_links WHERE category = 'speaker'");
$topics = $conn->query("SELECT * FROM sermon_links WHERE category = 'topic'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - RAPIC Sermons</title>
    <style>
        /* Styling for form, buttons, and dropdowns */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 3rem;
            text-align: left;
            margin-top: 30px;
            color: #333;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #555;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="url"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }


        
    </style>
</head>
<body>

<div class="container">
    <h1>RAPIC Sermons</h1>
    <h2>Add New Sermon Link</h2>

    <!-- Display Message After Adding Link -->
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

    <!-- Form to Add a New Link -->
    <form method="POST" action="">
        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="series">Series</option>
            <option value="speaker">Speaker</option>
            <option value="topic">Topic</option>
        </select><br><br>

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" placeholder="Enter the title" required><br><br>

        <label for="url">URL:</label>
        <input type="url" name="url" id="url" placeholder="Enter the URL" required><br><br>

        <input type="submit" name="add" value="Add Link" class="submit-btn">
    </form>

    <!-- Display Dropdowns for Series, Speakers, and Topics -->
    <h2>Browse Sermons</h2>

    <div class="dropdown">
        <button class="dropbtn">Browse Series ▾</button>
        <div class="dropdown-content">
            <?php while ($row = $series->fetch_assoc()) { ?>
                <a href="<?php echo $row['url']; ?>"><?php echo $row['title']; ?></a>
            <?php } ?>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn">Browse Speakers ▾</button>
        <div class="dropdown-content">
            <?php while ($row = $speakers->fetch_assoc()) { ?>
                <a href="<?php echo $row['url']; ?>"><?php echo $row['title']; ?></a>
            <?php } ?>
        </div>
    </div>

    <div class="dropdown">
        <button class="dropbtn">Browse Topics ▾</button>
        <div class="dropdown-content">
            <?php while ($row = $topics->fetch_assoc()) { ?>
                <a href="<?php echo $row['url']; ?>"><?php echo $row['title']; ?></a>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
