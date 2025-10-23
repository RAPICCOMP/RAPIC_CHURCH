<?php
include 'db_connect.php';  // Include the database connection

// Get data from the AJAX request
$name = $_POST['name'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$joining = $_POST['joining'];
$nationality = $_POST['nationality'];
$town = $_POST['town'];
$house = $_POST['house'];

// Insert data into the database
$sql = "INSERT INTO bethel_members (name, dob, gender, joining, nationality, town, house)
        VALUES ('$name', '$dob', '$gender', '$joining', '$nationality', '$town', '$house')";

if ($conn->query($sql) === TRUE) {
    echo "New member added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
