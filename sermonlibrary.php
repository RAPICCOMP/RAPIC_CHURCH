<?php
include 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $preacher = $_POST['preacher'];
    $link = $_POST['link'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $image = $target_file;

    $sql = "INSERT INTO church_sermons (title, date, preacher, image, link) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $date, $preacher, $image, $link);

    if ($stmt->execute()) {
        echo "<p class='success'>Sermon added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }
}

// Fetch data from database
$sql = "SELECT * FROM church_sermons ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Church Sermons</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .form-container, .sermons-container {
            background: white;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .sermons-container {
            max-width: 800px;
            margin-top: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .column {
            flex: 1;
            max-width: 300px;
            margin: 10px;
        }
        .card {
            background: white;
            padding: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: left;
        }
        .card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="file"] {
            border: none;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add a New Sermon</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required><br>
            <label>Date:</label>
            <input type="date" name="date" required><br>
            <label>Preacher:</label>
            <input type="text" name="preacher" required><br>
            <label>Upload Image:</label>
            <input type="file" name="image" required><br>
            <label>Link to Sermon:</label>
            <input type="text" name="link" required><br>
            <button type="submit">Add Sermon</button>
        </form>
    </div>

    <div class="sermons-container">
        <h2>Latest Sermons</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="column">
                    <div class="card">
                        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                        <h2><?php echo $row['title']; ?></h2>
                        <p><?php echo date('F j, Y', strtotime($row['date'])); ?></p>
                        <p><?php echo $row['preacher']; ?></p>
                        <a href="<?php echo $row['link']; ?>">View Sermon</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
