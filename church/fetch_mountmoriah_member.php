<?php
include 'db_connect.php';  // Include the database connection

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $memberId = $_GET['id'];
    
    // Query to fetch the specific member based on the ID
    $sql = "SELECT * FROM mountmoriah_members WHERE id = '$memberId'";
    $result = $conn->query($sql);

    if (!$result) {
        // If the query fails, show an error message
        die("Error executing query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Fetch the member data
        $member = $result->fetch_assoc();
        echo json_encode($member);
    } else {
        // If no member found, return an error message
        echo json_encode(["error" => "Invalid member ID"]);
    }
} else {
    // If no ID is provided, return all members (or handle differently)
    $sql = "SELECT * FROM mountmoriah_members";
    $result = $conn->query($sql);

    if (!$result) {
        // If the query fails, show an error message
        die("Error executing query: " . $conn->error);
    }

    $members = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
    }
    echo json_encode($members);
}

$conn->close();
?>

