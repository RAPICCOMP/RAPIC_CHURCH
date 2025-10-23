<?php
include 'db_connect.php'; // Include your database connection

$memberId = $_GET['memberId'];

$query = "SELECT month, year FROM mountolive_date WHERE member_id = ? AND is_checked = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $memberId);
$stmt->execute();
$result = $stmt->get_result();
$contributions = [];

while ($row = $result->fetch_assoc()) {
    $contributions[] = $row;
}

echo json_encode($contributions);
?>
