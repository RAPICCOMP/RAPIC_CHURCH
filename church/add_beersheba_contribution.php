<?php
// add_contribution.php
include 'db_connection.php';  // Assuming you have a db connection file

if (isset($_POST['memberId']) && isset($_POST['year']) && isset($_POST['month'])) {
    $memberId = $_POST['memberId'];
    $year = $_POST['year'];
    $month = $_POST['month'];

    // Insert into beersheba_date table (assuming it has columns: member_id, year, and month)
    $stmt = $conn->prepare("INSERT INTO beersheba_date (member_id, year, month) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $memberId, $year, $month);

    if ($stmt->execute()) {
        echo "Contribution added.";
    } else {
        echo "Error adding contribution.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid data.";
}
?>
