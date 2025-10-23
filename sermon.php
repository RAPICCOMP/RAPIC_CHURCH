<?php
// Security headers and session settings
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $params['lifetime'],
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Force HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit;
}
// Content Security Policy header
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; frame-src https://www.google.com https://www.youtube.com https://youtube.com;");
// Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
include('db_connect.php');

// Securely fetch the data for Series, Speakers, and Topics from the database
$series_result = false;
if ($stmt = $conn->prepare("SELECT url, title FROM sermon_links WHERE category = 'series'")) {
    $stmt->execute();
    $series_result = $stmt->get_result();
    $stmt->close();
}
$speakers_result = false;
if ($stmt = $conn->prepare("SELECT url, title FROM sermon_links WHERE category = 'speaker'")) {
    $stmt->execute();
    $speakers_result = $stmt->get_result();
    $stmt->close();
}
$topics_result = false;
if ($stmt = $conn->prepare("SELECT url, title FROM sermon_links WHERE category = 'topic'")) {
    $stmt->execute();
    $topics_result = $stmt->get_result();
    $stmt->close();
}
// Securely fetch sermons
$result = false;
if ($stmt = $conn->prepare("SELECT image, title, date, preacher, link FROM church_sermons ORDER BY date DESC")) {
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoration Apostolic Pentecostal Remnant Church</title>
    <style>
        /* Global Styles */
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

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        @media (max-width: 480px) {
            .sermons-container,
            .sermons-container.container {
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow-x: visible !important;
            }
            .container {
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .row {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                overflow-x: auto;
                margin-top: 20px;
                scroll-snap-type: x mandatory;
                direction: ltr;
                list-style: none;
                padding: 0;
                gap: 20px;
                width: 100vw;
                justify-content: center;
            }
            .card {
                flex: 0 0 auto;
                min-width: 300px;
                max-width: 320px;
                margin-right: 0;
                scroll-snap-align: start;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                transition: transform 0.3s, box-shadow 0.3s;
                overflow: hidden;
                padding: 15px;
            }
            .card img {
                width: 100%;
                height: 180px;
                object-fit: contain;
                background: #fff;
                border-radius: 8px;
                display: block;
            }
        }
        @media (min-width: 481px) and (max-width: 992px) {
            .row {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            .column {
                min-width: 0;
                max-width: 100%;
                margin: 0;
            }
        }
        @media (min-width: 993px) {
            .row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }
            .column {
                flex: 33.33%;
                min-width: 0;
                max-width: 100%;
                margin: 0;
            }
        }

        .column {
            flex: 33.33%;
            padding: 10px;
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
            background: #fff;
            padding: 10px 20px;
            min-height: 70px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
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

        /* Remove old .church-name styles, replaced by marquee below */

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
            .card {
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .card h2 {
            font-size: 1.6rem;
            color: #333;
        }

        .card p {
            font-size: 1rem;
            color: #777;
        }

        .card a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
        }

        .card a:hover {
            background-color: #555;
        }
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .column {
            flex: 33.33%;
            padding: 10px;
        }

        .card {
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .card h2 {
            font-size: 1.6rem;
            color: #333;
        }

        .card p {
            font-size: 1rem;
            color: #777;
        }

        .card a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
        }

        .card a:hover {
            background-color: #555;
        }
        .dropdown {
  position: relative;
  display: inline-block;
  margin: 0 10px;
}

.dropbtn {
  background-color: #23295e;
  color: white;
  padding: 12px 20px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dropbtn:hover {
  background-color: darkblue;
  transform: translateY(-2px);
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #ffffff;
  min-width: 200px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  animation: fadeIn 0.4s ease-in-out;
}

.dropdown-content a {
  color: #333;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
  color: blue;
}

.show {
  display: block;
  animation: slideDown 0.5s ease-in-out;
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideDown {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
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

      /* Styling for the dropdowns */
      .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }
        .dropdown-content.show {
            display: block;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }

    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <!-- Replace with your smaller logo file -->
                <img src="logo.jpg" alt="Church Logo">
            </div>
            <div class="church-name-marquee">
                <span>Restoration Apostolic Pentecostal Remnant Church</span>
            </div>
            <style>
            .church-name-marquee {
                flex: 1;
                margin: 0 10px;
                overflow: hidden;
                white-space: nowrap;
                position: relative;
                min-width: 0;
                text-align: left;
                display: flex;
                align-items: center;
                height: 50px;
            }
            .church-name-marquee span {
                display: inline-block;
                padding-left: 100%;
                animation: marquee 16s linear infinite;
                font-size: 16px;
                color: #333;
                font-weight: 700;
                letter-spacing: 2px;
                text-transform: uppercase;
                line-height: 50px;
            }
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-100%); }
            }
            @media (max-width: 768px) {
                .navbar { min-height: 40px; padding: 2px 5px; }
                .church-name-marquee {
                    height: 32px;
                }
                .church-name-marquee span {
                    font-size: 0.95rem;
                    line-height: 32px;
                }
            }
            @media (max-width: 480px) {
                .navbar { min-height: 24px; padding: 1px 5px; }
                .church-name-marquee {
                    height: 24px;
                }
                .church-name-marquee span {
                    font-size: 0.8rem;
                    line-height: 24px;
                }
            }
            </style>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="mission.html">mission</a></li>
                <li><a href="vision.html">vision</a></li>
                <li><a href="history.html">history</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </nav>
    </header>




    <div class="container">
        <h1 style="font-size: 3rem; text-align: left; margin-top: 30px;"></h1>

        

</div>

</head>
<body>

<div class="container">
    <h1></h1>

    <div class="browse-dropdowns">
        <div class="dropdown" onmouseleave="closeDropdown('myDropdown1')">
            <button type="button" onclick="toggleDropdown('myDropdown1')" class="dropbtn">Browse Series ▾</button>
            <div id="myDropdown1" class="dropdown-content">
                <?php while ($row = $series_result->fetch_assoc()) { ?>
                    <a href="<?php echo $row['url']; ?>" tabindex="0"><?php echo $row['title']; ?></a>
                <?php } ?>
            </div>
        </div>
        <div class="dropdown" onmouseleave="closeDropdown('myDropdown2')">
            <button type="button" onclick="toggleDropdown('myDropdown2')" class="dropbtn">Browse Speakers ▾</button>
            <div id="myDropdown2" class="dropdown-content">
                <?php while ($row = $speakers_result->fetch_assoc()) { ?>
                    <a href="<?php echo $row['url']; ?>" tabindex="0"><?php echo $row['title']; ?></a>
                <?php } ?>
            </div>
        </div>
        <div class="dropdown" onmouseleave="closeDropdown('myDropdown3')">
            <button type="button" onclick="toggleDropdown('myDropdown3')" class="dropbtn">Browse Topics ▾</button>
            <div id="myDropdown3" class="dropdown-content">
                <?php while ($row = $topics_result->fetch_assoc()) { ?>
                    <a href="<?php echo $row['url']; ?>" tabindex="0"><?php echo $row['title']; ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <style>
    .browse-dropdowns {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 32px;
        margin: 30px 0 20px 0;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    .browse-dropdowns .dropdown {
        flex: 1 1 320px;
        max-width: 320px;
        min-width: 220px;
        margin: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    @media (max-width: 768px) {
        .browse-dropdowns {
            gap: 16px;
            margin: 20px 0 10px 0;
            max-width: 100vw;
            flex-wrap: wrap;
        }
        .browse-dropdowns .dropdown {
            flex: 1 1 45%;
            max-width: 48vw;
            min-width: 140px;
            margin: 0 auto 0 auto;
        }
        .browse-dropdowns .dropdown:nth-child(3) {
            flex-basis: 100%;
            max-width: 90vw;
            margin-top: 12px;
            margin-left: auto;
            margin-right: auto;
        }
    }
    </style>
</div>


<div class="sermons-container container">
    <h2></h2>
    <div class="row">
        <?php
        $sermon_count = 0;
        function getYoutubeId($url) {
            // Handles various YouTube URL formats
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?|shorts)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                return $matches[1];
            }
            return false;
        }
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Remove the limit so all sermons are shown
                echo '<div class="card">';
                $youtubeId = getYoutubeId($row['link']);
                if ($youtubeId) {
                    echo '<div style="width:100%;height:180px;margin-bottom:10px;">';
                    echo '<iframe width="100%" height="180" src="https://www.youtube.com/embed/' . htmlspecialchars($youtubeId) . '" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe>';
                    echo '</div>';
                } else {
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width:100%;height:180px;object-fit:contain;background:#fff;border-radius:8px;display:block;">';
                }
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<p>' . htmlspecialchars(date('F j, Y', strtotime($row['date']))) . '</p>';
                echo '<p>' . htmlspecialchars($row['preacher']) . '</p>';
                if (!$youtubeId) {
                    echo '<a href="' . htmlspecialchars($row['link']) . '">View Sermon</a>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>No sermons available.</p>';
        }
        ?>
    </div>
</div>


        
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-column">
          <h3>CONTACT US</h3>
          <p>+260 979 888555 | +260 964 888555</p>
          <p>Emergency Line for members only:</p>
          <p>+260 967 or 977 521929</p>
          <p>Email: info@mlfc.org</p>
        </div>
        <div class="footer-column">
          <h3>ADDRESS</h3>
          <p>Plot 256 Foxdale, Zambezi Road</p>
          <p>Chamba Valley, PO Box 32275</p>
          <p>Lusaka, Zambia</p>
        </div>
        <div class="footer-column">
          <h3>FOLLOW US</h3>
          <div class="social-icons">
            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
    </footer>
    
</body>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        
        function toggleDropdown(id) {
        document.getElementById(id).classList.toggle("show");
        }

        function closeDropdown(id) {
  
            document.getElementById(id).classList.remove("show");
            }

    </script>

<script>
    // Function to toggle dropdown visibility
    function toggleDropdown(dropdownId) {
        document.getElementById(dropdownId).classList.toggle("show");
    }

    // Function to close dropdown when mouse leaves
    function closeDropdown(dropdownId) {
        document.getElementById(dropdownId).classList.remove("show");
    }

</script>

</body>
</html>


<?php
// Close the database connection
$conn->close();
?>
