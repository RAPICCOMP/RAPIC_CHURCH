<?php
include('db_connect.php');

// Check if an ID is provided in the URL query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the sermon from the database
    $sql = "DELETE FROM sermons WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect to admin dashboard after successful deletion
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting sermon: " . mysqli_error($conn);
    }
} else {
    echo "No sermon ID specified.";
}
?>
