<?php
// Include database connection
include('db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .btn {
            display: block;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Home Page</h2>
    <div class="button-container">
        <a href="Slide Show" class="btn"> Slide Show Images</a>
        <a href="admin_add_sermon.php" class="btn">Recent Sernons</a>
        <a href="admin_add_testimonial.php" class="btn">Testimonials</a>
        <a href="admin_add_contact.php" class="btn">Contacts</a>
        
    </div>
</div>

</body>
</html>
