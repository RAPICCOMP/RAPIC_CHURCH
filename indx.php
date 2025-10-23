<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "church_website";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch carousel data
$carousel_sql = "SELECT * FROM carousel";
$carousel_result = $conn->query($carousel_sql);

// Fetch sermon data
$sermon_sql = "SELECT * FROM sermons";
$sermon_result = $conn->query($sermon_sql);

// Fetch testimonials
$testimonial_sql = "SELECT * FROM testimonials";
$testimonial_result = $conn->query($testimonial_sql);

// Fetch contact info
$contact_sql = "SELECT * FROM contact_info LIMIT 1";
$contact_result = $conn->query($contact_sql);
$contact = $contact_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<header>
        <nav class="navbar">
            
            <a href="index.html" class="logo">
            
                <img src="logo.jpg" alt="Church Logo" style="height: 50px;">
                
            </a>
            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
            <h2>Restoration Apostolic Pentecostal Remnant Church</h2>
            <ul class="nav-links">
                
                <li>
                    <a href="index.html">
                        
                        Home
                        <span class="tooltip">Back to homepage</span>
                    </a>
                </li>
                <li>
                    <a href="aboutu.html">
                        About Us
                        <span class="tooltip">Learn more about our church</span>
                    </a>
                </li>
                <li>
                    <a href="sermon.html">
                        Sermons
                        <span class="tooltip">Access inspiring sermons</span>
                    </a>
                </li>
                <li>
                    <a href="event.html">
                        Events
                        <span class="tooltip">See upcoming events</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="minist.html">
                        Ministries
                        <span class="tooltip">Explore our ministries</span>
                    </a>
                    <!-- Dropdown Content -->
                    <ul class="dropdown-menu">
                        <span class="tooltip">Explore our ministries</span>
                        <li><a href="youth-ministry.html">Youth Ministry</a></li>
                        <li><a href="women-ministry.html">Women Ministry</a></li>
                        <li><a href="outreach.html">Outreach Programs</a></li>
                    </ul>
                </li>
                <li>
                    <a href="gallery.html">
                        Gallery
                        <span class="tooltip">View photos and videos</span>
                    </a>
                </li>
                <li>
                    <a href="contact.html">
                        Contacts
                        <span class="tooltip">Get in touch with us</span>
                    </a>
                </li>
                <li>
                    <a href="freewillgiving.html">
                        Free will Offering
                        <span class="tooltip">Support our mission</span>
                    </a>
                </li>
            </ul>
        <div class="hamburger" id="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        </nav>
    </header>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    </script>
</body>
</html>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Remnant Church Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Church Website</title>
    <style>
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding-top: 70px;
            animation: fadeIn 1s ease-in-out;
        }

        /* Fade In animation for the body */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 20px; /* Reduced padding for a smaller navbar */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            opacity: 0;
            animation: slideDown 0.7s forwards 0.3s;
        }

        /* Slide Down animation for the navbar */
        @keyframes slideDown {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .navbar:hover {
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        /* Logo Styles */
        .navbar .logo img {
            width: 80px; /* Reduced logo size */
            height: auto;
            transition: transform 0.3s ease;
        }

        .navbar .logo:hover img {
            transform: scale(1.1);
        }

        .navbar .church-name {
            font-size: 16px; /* Reduced font size */
            color: #333;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 2px;
            transition: color 0.3s ease;
        }

        .navbar .church-name:hover {
            color: #f4d03f;
        }

        .nav-links {
            display: flex;
            list-style-type: none;
            transition: transform 0.5s ease-in-out;
            opacity: 0;
            animation: fadeInLinks 1s ease-in-out 1.2s forwards;
        }

        /* Fade In animation for nav links */
        @keyframes fadeInLinks {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .nav-links li {
            margin: 0 15px; /* Reduced margin between nav links */
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-size: 16px; /* Adjusted font size */
            text-transform: uppercase;
            position: relative;
            padding-bottom: 5px;
            transition: all 0.3s ease, transform 0.3s ease;
            animation: slideIn 0.6s ease-in-out;
        }

        /* Slide In animation for links */
        @keyframes slideIn {
            0% { transform: translateX(-50px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #f4d03f;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }

        .nav-links a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .nav-links a:hover {
            color: #f4d03f;
            transform: translateY(-3px);
        }

        /* Hamburger Menu Styles */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .hamburger .bar {
            width: 30px;
            height: 3px;
            margin: 6px 0;
            background-color: #333;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .hamburger.active .bar:nth-child(1) {
            transform: translateY(9px) rotate(45deg);
        }

        .hamburger.active .bar:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active .bar:nth-child(3) {
            transform: translateY(-9px) rotate(-45deg);
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                position: absolute;
                top: 70px;
                left: 0;
                background-color: #fff;
                padding: 20px 0;
                text-align: center;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .hamburger {
                display: flex;
            }
        }

        .tooltip {
          position: absolute;
          top: 110%;
          left: 50%;
          transform: translateX(-50%);
          background: #000;
          color: #fff;
          padding: 5px 10px;
          border-radius: 5px;
          font-size: 0.9rem;
          white-space: nowrap;
          opacity: 0;
          visibility: hidden;
          transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        .nav-links a:hover .tooltip {
          opacity: 1;
          visibility: visible;
        }

        /* Dropdown Menu Styling */
.dropdown {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    background-color: #444;
    list-style: none;
    padding: 10px 15px;
    border-radius: 5px;
    top: 100%;
    left: 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease;
}

.dropdown-menu a {
    color: white;
    display: block;
    padding: 8px 10px;
    text-decoration: none;
    font-size: 0.95rem;
}

.dropdown-menu a:hover {
    background-color: #555;
    border-radius: 5px;
}

/* Show Dropdown on Hover */
.dropdown:hover .dropdown-menu {
    display: block;
}

/* Mobile Menu Toggle */
#mobile-menu {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

#mobile-menu .bar {
    background-color: white;
    height: 3px;
    width: 25px;
    margin: 5px 0;
    transition: transform 0.3s ease;
}/* Global styles */
* {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Playfair Display', serif;
            line-height: 1.6;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Carousel Section */
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            border-bottom: 2px solid #ddd;
            background-color: #000;
            margin-top: 100px;
        }

        .carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .carousel-item {
            position: absolute;
            width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
            transform: translateX(100%);
            transition: transform 1s ease;
        }

        .carousel-item.active {
            transform: translateX(0);
        }

        .carousel-item.previous {
            transform: translateX(-100%);
        }

        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            text-align: center;
            text-transform: uppercase;
        }

        .carousel-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            padding: 0 20px;
        }

        .carousel-controls span {
            font-size: 2rem;
            color: white;
            cursor: pointer;
            user-select: none;
            transition: transform 0.3s;
        }

        .carousel-controls span:hover {
            transform: scale(1.2);
        }

        /* Sermon Cards Section */
        .sermon-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 60px 0;
            background-color: #f4f4f4;
        }

        .sermon-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .sermon-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .sermon-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .sermon-card img:hover {
            transform: scale(1.1);
        }

        .sermon-card .content {
            padding: 15px;
            text-align: center;
        }

        .sermon-card .title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .sermon-card .description {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 15px;
        }

        .sermon-card .btn {
            padding: 10px 25px;
            background-color: #3498db;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            transition: background-color 0.3s;
        }

        .sermon-card .btn:hover {
            background-color: #2980b9;
        }

        /* Contact Section */
        .contact-section {
            background-color: #2c3e50;
            color: white;
            padding: 50px 20px;
            text-align: center;
            width: 100%;
        }

        .contact-section h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 600;
        }

        .contact-section .social-icons a {
            margin: 0 20px;
            color: white;
            font-size: 1.8rem;
            transition: transform 0.3s;
        }

        .contact-section .social-icons a:hover {
            transform: scale(1.2);
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .slideshow-container {
                height: 50vh;
            }

            .sermon-section {
                grid-template-columns: 1fr;
            }
        }

        .footer {
  background-color: #333;
  color: #fff;
  padding: 20px 0;
  text-align: center;
}

.footer-container {
  width: 960px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
}

.footer-column {
  flex: 1;
  margin: 0 20px;
}

.footer-column h3 {
  margin-bottom: 10px;
}

.footer-column p {
  margin: 5px 0;
}

.footer-column ul {
  list-style: none;
  padding: 0;
}

.footer-column ul li {
  margin-bottom: 5px;
}

.social-icons {
  display: flex;
}

.social-icons a {
  display: inline-block;
  width: 30px;
  height: 30px;
  margin: 0 5px;
  border-radius: 50%;
  text-align: center;
  line-height: 30px;
  color: #fff;
  background-color: #3b5998; /* Facebook */
}

.social-icons a:nth-child(2) {
  background-color: #1da1f2; /* Twitter */
}

.social-icons a:nth-child(3) {
  background-color: #e1306c; /* Instagram */
}

.social-icons a:nth-child(4) {
  background-color: #ff0000; /* YouTube */
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: #343a40;
    display: none;
    flex-direction: column;
    gap: 10px;
    padding: 10px 20px;
    border-radius: 5px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.dropdown:hover .dropdown-menu {
    display: flex;
}

.dropdown-menu a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    padding: 5px 10px;
    border-radius: 5px;
}

.dropdown-menu a:hover {
    background: #f9c;
    color: #343a40;
}


.map-container{
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center ;
    justify-content: center;
    flex-direction: column;
    align-items: center; 


}

.iframe{
    width: 80%; 
    height: 500px; 
    border: 0;
    border-radius: 8px; 
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    align-items: center; 
}

.h1{
         font-size: 3rem;
            margin: 0;
            letter-spacing: 2px;
        }





        .footer {
      background-color: #333;
      color: #fff;
      padding: 40px 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center; /* Ensures text is centered within the footer */
    }
  
    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      max-width: 1200px;
      width: 100%;
    }
  
    .footer-column {
      flex: 1;
      min-width: 250px;
      margin: 10px;
      text-align: left; /* Default left-align for content */
    }
  
    .footer-column h3 {
      margin-bottom: 15px;
      font-size: 18px;
      text-transform: uppercase;
    }
  
    .footer-column p {
      margin-bottom: 10px;
      font-size: 14px;
    }
  
    .social-icons {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }
  
    .social-icons a {
      color: #fff;
      margin-right: 15px;
      font-size: 20px;
      text-decoration: none;
    }
  
    .social-icons a:hover {
      color: #1e90ff;
    }
  
    /* Responsiveness */
    @media (max-width: 768px) {
      .footer-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
  
      .footer-column {
        text-align: center;
        margin-bottom: 20px;
        min-width: unset;
      }
  
      .social-icons {
        justify-content: center;
      }
  
      .social-icons a {
        margin-right: 10px;
      }
    }
  
    @media (max-width: 480px) {
      .footer-column h3 {
        font-size: 16px;
      }
  
      .footer-column p {
        font-size: 12px;
      }
  
      .social-icons a {
        font-size: 18px;
      }
    }
    .footer {
      background-color: #333;
      color: #fff;
      padding: 40px 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center; /* Ensures text is centered within the footer */
    }
  
    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      max-width: 1200px;
      width: 100%;
    }
  
    .footer-column {
      flex: 1;
      min-width: 250px;
      margin: 10px;
      text-align: left; /* Default left-align for content */
    }
  
    .footer-column h3 {
      margin-bottom: 15px;
      font-size: 18px;
      text-transform: uppercase;
    }
  
    .footer-column p {
      margin-bottom: 10px;
      font-size: 14px;
    }
  
    .social-icons {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }
  
    .social-icons a {
      color: #fff;
      margin-right: 15px;
      font-size: 20px;
      text-decoration: none;
    }
  
    .social-icons a:hover {
      color: #1e90ff;
    }
  
    /* Responsiveness */
    @media (max-width: 768px) {
      .footer-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
  
      .footer-column {
        text-align: center;
        margin-bottom: 20px;
        min-width: unset;
      }
  
      .social-icons {
        justify-content: center;
      }
  
      .social-icons a {
        margin-right: 10px;
      }
    }
  
    @media (max-width: 480px) {
      .footer-column h3 {
        font-size: 16px;
      }
  
      .footer-column p {
        font-size: 12px;
      }
  
      .social-icons a {
        font-size: 18px;
      }
    }


    <style>
    /* Slideshow Container */
    .slideshow-container {
        position: relative;
        max-width: 100%;
        margin: auto;
        overflow: hidden;
    }

    /* Individual Slide */
    .carousel-item {
        display: none;
        width: 100%;
        text-align: center;
        position: relative;
    }

    /* Image inside the carousel */
    .carousel-item img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
    }

    /* Overlay Text */
    .overlay-text {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 10px;
        font-size: 20px;
        border-radius: 5px;
    }

    /* Navigation Buttons */
    .prev, .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }
</style>

    </style>

    
</head>

<div class="slideshow-container">
    <div class="carousel">
        <?php while ($row = $carousel_result->fetch_assoc()): ?>
            <div class="carousel-item">
                <img src="<?php echo $row['image_url']; ?>" alt="Slide">
                <div class="overlay-text"><?php echo $row['text']; ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Navigation Buttons -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

<body>
    <!-- Carousel Section -->
    <div class="carousel">
    <?php
    $carousel_sql = "SELECT * FROM carousel";
    $carousel_result = $conn->query($carousel_sql);

    while ($row = $carousel_result->fetch_assoc()) {
        echo '<img src="' . $row['image_url'] . '" alt="' . $row['text'] . '">';
    }
    ?>
</div>


    <!-- Sermon Section -->
    <h1>Recent Sermons</h1>
    <section class="sermon-section">
        <?php while ($row = $sermon_result->fetch_assoc()): ?>
            <div class="sermon-card">
                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>">
                <div class="content">
                    <div class="title"><?php echo $row['title']; ?></div>
                    <div class="description"><?php echo $row['description']; ?></div>
                    <a href="<?php echo $row['link']; ?>" class="btn">Watch Now</a>
                </div>
            </div>
        <?php endwhile; ?>
    </section>

    <!-- Testimonial Section -->
    <h3>What Our Members Say</h3>
    <div class="testimonial-slider">
        <?php while ($row = $testimonial_result->fetch_assoc()): ?>
            <div>
                <p>"<?php echo $row['message']; ?>"</p>
                <p>- <?php echo $row['name']; ?></p>
            </div>
        <?php endwhile; ?>

        <section class="sermon-section">
        <?php while ($row = $sermon_result->fetch_assoc()): ?>
            <div class="sermon-card">
                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['title']; ?>">
                <div class="content">
                    <div class="title"><?php echo $row['title']; ?></div>
                    <div class="description"><?php echo $row['description']; ?></div>
                    <a href="<?php echo $row['link']; ?>" class="btn">Watch Now</a>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
    </div>

    <h1>Latest Sermons</h1>
<div class="sermon-section">
    <?php
    $sermon_sql = "SELECT * FROM sermons";
    $sermon_result = $conn->query($sermon_sql);

    while ($row = $sermon_result->fetch_assoc()) {
        echo '<div class="sermon-card">
                <h3>' . $row['title'] . '</h3>
                <p>' . $row['description'] . '</p>
                <a href="' . $row['link'] . '">Watch Now</a>
              </div>';
    }
    ?>
</div>

    

    <!-- Contact Section -->
    <h3>Contact Us</h3>
    <p>Phone: <?php echo $contact['phone']; ?></p>
    <p>Email: <?php echo $contact['email']; ?></p>
    <p>Address: <?php echo $contact['address']; ?></p>

</body>
</html>

 


        <!-- Lightbox Modal -->
        <div id="lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); align-items: center; justify-content: center; z-index: 1000;">
            <img id="lightbox-img" src="" alt="Lightbox" style="max-width: 90%; max-height: 90%; border-radius: 8px;">
            <button onclick="closeLightbox()" style="position: absolute; top: 20px; right: 20px; background: #fff; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;">&times;</button>
        </div>

    </div>
</section>

        <!-- Google Map -->
<section>
    <div class="map-container" style="display: flex; flex-direction: column; align-items: center; margin-top: 40px;">
        <h3 style="text-align: center; color: #0c0808; font-size: 1.5rem; margin-bottom: 20px;">Find Us Here</h3>
        <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3511.1604259214064!2d28.644494411008253!3d-12.981880892503028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x196cb5000996118b%3A0xd10ca0067fcdb915!2sRestoration%20Apostolic%20Pentecostal%20Remnant%20Church!5e1!3m2!1sen!2szm!4v1737616202748!5m2!1sen!2szm" 
              width="100%" 
              height="500px" 
              style="border:0; max-width: 1200px;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              border-radius="20px">
              
        </iframe>
    </div>
</section>



    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Remnant Church. All Rights Reserved.</p>
        </div>
    </footer>



    <script>
        // JavaScript for the carousel
        const items = document.querySelectorAll('.carousel-item');
        const prev = document.querySelector('.prev');
        const next = document.querySelector('.next');
        
        function showSlide(index) {
            items.forEach((item, i) => {
                item.classList.remove('active', 'previous');
                if (i === index) {
                    item.classList.add('active');
                } else if (i === (index - 1 + items.length) % items.length) {
                    item.classList.add('previous');
                }
            });
        }

        prev.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            showSlide(currentIndex);
        });

        next.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % items.length;
            showSlide(currentIndex);
        });

        setInterval(() => {
            currentIndex = (currentIndex + 1) % items.length;
            showSlide(currentIndex);
        }, 5000);

        showSlide(currentIndex);


        
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        
    function openLightbox(src) {
        document.getElementById('lightbox').style.display = 'flex';
        document.getElementById('lightbox-img').src = src;
    }
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }


    
    let currentIndex = 0;
    const totalItems = document.querySelectorAll('.slider-track > div').length;

    function moveSlider(direction) {
        const track = document.querySelector('.slider-track');
        const itemWidth = 300 + 20; // Width + gap
        currentIndex = (currentIndex + direction + totalItems) % totalItems;
        track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        updateDots();
    }

    function goToSlide(index) {
        currentIndex = index;
        const track = document.querySelector('.slider-track');
        const itemWidth = 300 + 20; // Width + gap
        track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
        updateDots();
    }

    function updateDots() {
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.style.backgroundColor = index === currentIndex ? '#007bff' : '#ccc';
        });
    }

    function autoplaySlider() {
        moveSlider(1);
    }

    // Autoplay every 5 seconds
    setInterval(autoplaySlider, 5000);

    // Initialize dots
    updateDots();

    

    
        // Hamburger Menu Functionality
        const menuToggle = document.getElementById('mobile-menu');
        
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        
    
        // JavaScript for the carousel
        const items = document.querySelectorAll('.carousel-item');
        const prev = document.querySelector('.prev');
        const next = document.querySelector('.next');
        
        function showSlide(index) {
            items.forEach((item, i) => {
                item.classList.remove('active', 'previous');
                if (i === index) {
                    item.classList.add('active');
                } else if (i === (index - 1 + items.length) % items.length) {
                    item.classList.add('previous');
                }
            });
        }

        prev.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            showSlide(currentIndex);
        });

        next.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % items.length;
            showSlide(currentIndex);
        });

        setInterval(() => {
            currentIndex = (currentIndex + 1) % items.length;
            showSlide(currentIndex);
        }, 5000);

        showSlide(currentIndex);

        <script>
    let slideIndex = 0;

    function showSlides() {
        let slides = document.getElementsByClassName("carousel-item");
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1; }
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 3000); // Change slide every 3 seconds
    }

    function plusSlides(n) {
        slideIndex += n - 1;
        showSlides();
    }

    // Initialize slideshow on page load
    document.addEventListener("DOMContentLoaded", showSlides);
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    </script>
</body>

</html>

    






    </script>
</body>

</html>


<?php
$conn->close();
?>
