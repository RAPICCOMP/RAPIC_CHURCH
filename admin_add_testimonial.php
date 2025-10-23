<?php
include 'db_connect.php';

// Handle form submission to add testimonials
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $testimonial = $conn->real_escape_string($_POST['testimonial']);

    $sql = "INSERT INTO testimonials (name, testimonial) VALUES ('$name', '$testimonial')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Testimonial added successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}

// Fetch testimonials from the database
$sql = "SELECT * FROM testimonials ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);
?>

<div class="testimonial-slider" style="margin-top: 50px;">
    <h3 style="text-align: center; font-size: 2rem; margin-bottom: 20px; color: #c2cbd3;">What Our Members Say</h3>
    <div style="overflow: hidden; max-width: 100%; position: relative;">
        <div class="slider-track" style="display: flex; gap: 20px; transition: transform 0.5s ease; width: 100%;">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div style="flex: 0 0 300px; background-color: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        <p style="font-style: italic;">"' . $row["testimonial"] . '"</p>
                        <p style="font-weight: bold; text-align: right; color: #007bff;">- ' . $row["name"] . '</p>
                    </div>';
                }
            } else {
                echo "<p style='text-align: center;'>No testimonials available.</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Admin Form to Add Testimonials -->
<div style="margin-top: 50px; padding: 20px; background-color: #f4f4f4; border-radius: 8px; max-width: 500px; margin: auto;">
    <h3 style="text-align: center;">Add a Testimonial</h3>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        
        <label for="testimonial">Testimonial:</label>
        <textarea name="testimonial" required style="width: 100%; padding: 8px; margin-bottom: 10px;"></textarea>
        
        <button type="submit" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Submit</button>
    </form>
</div>
