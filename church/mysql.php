<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cmdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Example query to fetch all branches
$sql = "SELECT * FROM branches";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Location: " . $row["location"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
