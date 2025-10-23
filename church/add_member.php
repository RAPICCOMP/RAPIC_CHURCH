<?php
// Database connection (replace with your own credentials)
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password (empty)
$dbname = "churchdb"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data from the POST request
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'] ?? ''; // Optional
    $dob = $_POST['dob'] ?? ''; // Optional
    $marital_status = $_POST['marital_status'];
    $cell_group = $_POST['cell_group'] ?? ''; // Optional

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO members (name, address, phone, email, dob, marital_status, cell_group) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $address, $phone, $email, $dob, $marital_status, $cell_group);

    if ($stmt->execute()) {
        // Send a JSON response to indicate success
        echo json_encode(["success" => true]);
    } else {
        // Send a JSON response for error
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>
