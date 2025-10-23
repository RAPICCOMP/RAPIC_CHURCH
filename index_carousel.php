<?php
$conn = new mysqli("localhost", "root", "", "church_website");
$result = $conn->query("SELECT * FROM carousel");

while ($row = $result->fetch_assoc()) {
    echo "<div class='carousel-item' style='background-image: url(" . $row['image'] . ");'>
              <div class='overlay-text'>" . $row['text'] . "</div>
          </div>";
}
?>
