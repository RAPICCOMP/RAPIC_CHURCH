<?php
// Include database connection
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $id = $_POST["id"]; // ID of the record to update
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $video_url = trim($_POST["video_url"]);

    // Validate inputs
    if (!empty($id) && !empty($name) && !empty($description) && !empty($video_url)) {
        // Prepare UPDATE SQL statement
        $sql = "UPDATE women_ministry SET name=?, description=?, video_url=? WHERE id=?";
        
        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("sssi", $name, $description, $video_url, $id);

            // Execute statement
            if ($stmt->execute()) {
                echo "Record updated successfully!";
            } else {
                echo "Error updating record: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required!";
    }
}

// Fetch the existing data for editing (assuming you're using `GET` to load the record)
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    echo "Fetching data for ID: " . $id . "<br>";  // Debugging line

    $query = "SELECT * FROM women_ministry WHERE id=?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Debugging: Check if a record was found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row["name"];
            $description = $row["description"];
            $video_url = $row["video_url"];
        } else {
            echo "Record not found!<br>";
            exit;
        }

        $stmt->close();
    }
} else {
    echo "No ID provided in URL.<br>";
}

// Close the connection
$conn->close();
?>

<!-- HTML Form for Updating Data -->
<form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">

    <label>Name:</label>
    <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required><br>

    <label>Description:</label>
    <textarea name="description" required><?php echo isset($description) ? $description : ''; ?></textarea><br>

    <label>Video URL:</label>
    <input type="text" name="video_url" value="<?php echo isset($video_url) ? $video_url : ''; ?>" required><br>

    <button type="submit">Update</button>
</form>
