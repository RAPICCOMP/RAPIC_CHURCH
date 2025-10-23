<?php
include 'db_connect.php'; // Include your database connection

$memberId = $_POST['memberId'];
$year = $_POST['year'];
$month = $_POST['month'];
$isChecked = $_POST['isChecked'];

// Check if the record exists
$query = "SELECT id FROM judah_date WHERE member_id = ? AND year = ? AND month = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('iis', $memberId, $year, $month);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update existing record
    $updateQuery = "UPDATE judah_date SET is_checked = ?, timestamp = CURRENT_TIMESTAMP WHERE member_id = ? AND year = ? AND month = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('iiis', $isChecked, $memberId, $year, $month);
    $updateStmt->execute();
} else {
    // Insert new record
    $insertQuery = "INSERT INTO judah_date (member_id, month, year, is_checked) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param('isii', $memberId, $month, $year, $isChecked);
    $insertStmt->execute();
}

echo "Contribution updated successfully.";
?>

