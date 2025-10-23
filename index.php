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
$host = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = (bool)preg_match('/^(localhost|127\.0\.0\.1)(:\\d+)?$/', $host);
if ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') && !$isLocal) {
    $redirect = 'https://' . $host . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit;
}
// Content Security Policy header
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; frame-src https://www.google.com https://www.youtube.com;");
// Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoration Apostolic Pentecostal Remnant Church</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f5f5; padding-top: 70px; animation: fadeIn 1s ease-in-out; }
        @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }

        .navbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px 20px; position: fixed; top: 0; width: 100%; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s; opacity: 0; animation: slideDown 0.7s forwards 0.3s; }
        @keyframes slideDown { 0% { transform: translateY(-50px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .navbar:hover { box-shadow: 0 8px 12px rgba(0,0,0,0.2); transform: translateY(-5px); }
        .navbar .logo img { width: 80px; height: auto; transition: transform 0.3s; }
        .navbar .logo:hover img { transform: scale(1.1); }
        .navbar h2 { font-size: 16px; color: #333; text-transform: uppercase; font-weight: 700; letter-spacing: 2px; transition: color 0.3s; }
        .navbar h2:hover { color: #f4d03f; }
        .nav-links { display: flex; list-style: none; transition: transform 0.5s; opacity: 0; animation: fadeInLinks 1s 1.2s forwards; }
        @keyframes fadeInLinks { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .nav-links li { margin: 0 15px; }
        .nav-links a { color: #333; text-decoration: none; font-size: 16px; text-transform: uppercase; position: relative; padding-bottom: 5px; transition: all 0.3s, transform 0.3s; animation: slideIn 0.6s; }
        @keyframes slideIn { 0% { transform: translateX(-50px); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        .nav-links a::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: #f4d03f; transform: scaleX(0); transform-origin: bottom right; transition: transform 0.25s; }
        .nav-links a:hover::after { transform: scaleX(1); transform-origin: bottom left; }
        .nav-links a:hover { color: #f4d03f; transform: translateY(-3px); }
        .tooltip { position: absolute; top: 110%; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 0.9rem; white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; }
        .nav-links a:hover .tooltip { opacity: 1; visibility: visible; }
        .dropdown { position: relative; }
        .dropdown-menu { display: none; position: absolute; background: #444; list-style: none; padding: 10px 15px; border-radius: 5px; top: 100%; left: 0; box-shadow: 0 4px 8px rgba(0,0,0,0.2); animation: fadeIn 0.3s; }
        .dropdown-menu a { color: #fff; display: block; padding: 8px 10px; text-decoration: none; font-size: 0.95rem; }
        .dropdown-menu a:hover { background: #555; border-radius: 5px; }
        .dropdown:hover .dropdown-menu { display: block; }
        .hamburger { display: none; flex-direction: column; cursor: pointer; transition: transform 0.3s; }
        .hamburger .bar { width: 30px; height: 3px; margin: 6px 0; background: #333; border-radius: 5px; transition: all 0.3s; }
        .hamburger.active .bar:nth-child(1) { transform: translateY(9px) rotate(45deg); }
        .hamburger.active .bar:nth-child(2) { opacity: 0; }
        .hamburger.active .bar:nth-child(3) { transform: translateY(-9px) rotate(-45deg); }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .slideshow-container, .carousel { position: relative; width: 100%; height: 500px; overflow: hidden; border-bottom: 2px solid #ddd; background: #fff; margin-top: 10px; }
        .carousel-item { position: absolute; width: 100%; height: 100%; background-position: center; background-size: cover; background-repeat: no-repeat; transform: translateX(100%); transition: transform 1s; display: flex; align-items: center; justify-content: center; background-color: transparent; }
        .carousel-item.active { transform: translateX(0); }
        .carousel-item.previous { transform: translateX(-100%); }
        .overlay-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 3rem; font-weight: bold; color: white; text-shadow: 2px 2px 10px rgba(0,0,0,0.7); text-align: center; text-transform: uppercase; }
        .carousel-controls { position: absolute; top: 50%; width: 100%; display: flex; justify-content: space-between; transform: translateY(-50%); padding: 0 20px; }
        .carousel-controls span { font-size: 2rem; color: white; cursor: pointer; user-select: none; transition: transform 0.3s; }
        .carousel-controls span:hover { transform: scale(1.2); }
        .sermon-section {
            display: flex;
            flex-direction: row;
            gap: 12px;
            padding: 40px 0 10px 0;
            background: #f4f4f4;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            flex-wrap: nowrap;
            white-space: nowrap;
            width: 100%;
            scrollbar-width: thin;
            scrollbar-color: #3498db #f4f4f4;
        }
        .sermon-card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            padding: 12px;
            min-width: 220px;
            max-width: 220px;
            scroll-snap-align: start;
            flex: 0 0 auto;
            margin: 0;
        }
        .sermon-card img {
            width: 100%;
            height: 120px;
            object-fit: contain;
            background: #fff;
            transition: transform 0.3s;
            border-radius: 8px;
        }
        .sermon-card img:hover {
            transform: scale(1.1);
        }
        .sermon-card .content {
            flex: 1;
            padding: 0;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
            align-self: flex-start;
            padding: 10px 25px;
            background: #3498db;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            transition: background 0.3s;
        }
        .sermon-card .btn:hover {
            background: #2980b9;
        }
        .contact-section { background: #2c3e50; color: white; padding: 50px 20px; text-align: center; width: 100%; }
        .contact-section {
            background: linear-gradient(135deg, #2c3e50 80%, #34495e 100%);
            color: white;
            padding: 50px 20px;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .contact-section h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 600;
            color: #f4d03f;
            letter-spacing: 1px;
        }
        .contact-section .social-icons {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin-top: 10px;
        }
        .contact-section .social-icons a {
            margin: 0 10px;
            color: #f4d03f;
            font-size: 1.8rem;
            transition: transform 0.3s, color 0.3s;
        }
        .contact-section .social-icons a:hover {
            transform: scale(1.2);
            color: #fff;
        }
        .footer { background: #333; color: #fff; padding: 40px 0; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; text-align: center; }
        .footer-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
            max-width: 1200px;
            width: 100%;
            gap: 40px;
            margin: 0 auto;
        }
        }
        .footer-column {
            width: 320px;
            min-width: 250px;
            max-width: 320px;
            margin: 10px;
            text-align: center;
            align-items: center;
            justify-content: center;
            display: flex;
            flex-direction: column;
        }
        .footer-column h3 { margin-bottom: 15px; font-size: 18px; text-transform: uppercase; }
        .footer-column p { margin-bottom: 10px; font-size: 14px; }
        .social-icons { display: flex; justify-content: center; margin-top: 10px; }
        .social-icons a { color: #fff; margin-right: 15px; font-size: 20px; text-decoration: none; }
        .social-icons a:hover { color: #1e90ff; }
        @media (max-width: 992px) {
            .container, .footer-container { max-width: 100%; padding: 0 10px; }
        }
        @media (max-width: 768px) {
            .navbar { flex-direction: row; padding: 2px 5px; min-height: 32px; }
            .navbar h2 { font-size: 0.95rem; text-align: center; line-height: 1.1; padding: 2px 0; margin: 0 10px; word-break: break-word; white-space: normal; }
            .navbar > div { flex: 1; display: flex; align-items: center; justify-content: space-between; }
            .nav-links {
                flex-direction: column;
                width: 100%;
                position: absolute;
                top: 70px;
                left: 0;
                background: #fff;
                padding: 20px 0;
                text-align: center;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                display: none;
            }
            .nav-links.active {
                display: flex;
            }
            .nav-links li { margin: 10px 0; }
            .hamburger { display: flex; }
            .slideshow-container, .carousel { height: 180px; margin-top: 8px; }
            .sermon-section {
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                gap: 15px;
                padding: 30px 0;
                max-width: 100vw;
                justify-content: center;
            }
            .sermon-card {
                min-width: 240px;
                max-width: 260px;
                padding: 10px;
                margin-left: auto;
                margin-right: auto;
            }
            .footer-container { flex-direction: column; align-items: center; text-align: center; width: 100%; justify-content: center; }
            .footer-column { text-align: center; margin-bottom: 20px; min-width: unset; width: 90vw; max-width: 320px; margin-left: auto; margin-right: auto; }
            .social-icons { justify-content: center; }
            .social-icons a { margin-right: 10px; }
            .map-container { height: 300px; }
            .iframe { height: 200px; }
        }
        @media (max-width: 480px) {
            .navbar { flex-direction: row; padding: 1px 5px; min-height: 24px; }
            .navbar h2 { font-size: 0.8rem; line-height: 1.1; padding: 1px 0; margin: 0 6px; word-break: break-word; white-space: normal; text-align: center; }
            .navbar h2 {
                animation: marquee 16s linear infinite;
                overflow: hidden;
                white-space: nowrap;
                display: block;
                width: 100%;
            }
            @keyframes marquee {
                0% { transform: translateX(100%); }
                100% { transform: translateX(-100%); }
            }
            .navbar > div { flex: 1; display: flex; align-items: center; justify-content: space-between; }
            .footer-column h3 { font-size: 16px; }
            .footer-column p { font-size: 12px; }
            .social-icons a { font-size: 18px; }
            .overlay-text { font-size: 1.2rem; }
            .sermon-card img { height: 120px; object-fit: contain; background: #fff; }
            .sermon-card .title { font-size: 1rem; }
            .sermon-card .description { font-size: 0.8rem; }
            .sermon-card .btn { padding: 8px 15px; font-size: 0.9rem; }
            .map-container { height: 200px; }
            .iframe { height: 120px; }
            h1.recent-sermons-title {
                font-size: 1.3rem !important;
                text-align: center !important;
                margin-bottom: 16px !important;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
                <a href="index.php" class="logo" style="flex:0 0 auto;"><img src="logo.jpg" alt="Church Logo" style="height: 50px;"></a>
                <div class="church-name-marquee">
                    <span>Restoration Apostolic Pentecostal Remnant Church</span>
                </div>
                <style>
                .church-name-marquee {
                    flex:1;
                    margin:0 10px;
                    overflow:hidden;
                    white-space:nowrap;
                    position:relative;
                    min-width:0;
                    text-align:left;
                }
                .church-name-marquee span {
                    display:inline-block;
                    padding-left:100%;
                    animation: marquee 16s linear infinite;
                    font-size: 16px;
                    color: #333;
                    font-weight: 700;
                    letter-spacing: 2px;
                    text-transform: uppercase;
                }
                @keyframes marquee {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-100%); }
                }
                @media (max-width: 768px) {
                    .church-name-marquee span {
                        font-size: 0.95rem;
                        animation: marquee 16s linear infinite;
                    }
                }
                @media (max-width: 480px) {
                    .church-name-marquee span {
                        font-size: 0.8rem;
                        animation: marquee 16s linear infinite;
                    }
                }
                </style>
                <div class="hamburger" id="hamburger" style="flex:0 0 auto; margin-left:10px;">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home<span class="tooltip">Back to homepage</span></a></li>
                <li><a href="aboutu.php">About Us<span class="tooltip">Learn more about our church</span></a></li>
                <li><a href="sermon.php">Sermons<span class="tooltip">Access inspiring sermons</span></a></li>
                <li><a href="event.php">Events<span class="tooltip">See upcoming events</span></a></li>
                <li class="dropdown">
                    <a href="minist.php">Ministries<span class="tooltip">Explore our ministries</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="youth-ministry.php">Youth Ministry</a></li>
                        <li><a href="women-ministry.php">Women Ministry</a></li>
                        <li><a href="outreach.php">Outreach Programs</a></li>
                    </ul>
                </li>
                <li><a href="contact.php">Contacts<span class="tooltip">Get in touch with us</span></a></li>
                <li>
                    <a href="freewillgiving.php">Free will Offering<span class="tooltip">Support our mission</span></a>
                    <a href="admin_dashboard.php">login<span class="tooltip">Admins Only</span></a>
                </li>
            </ul>
        </nav>
    </header>

    <script src="app.js"></script>

    <!-- Carousel Section -->
    <div class="slideshow-container">
        <div class="carousel">
            <div class="carousel-item active" style="background-image: url('youth.jpg');"><div class="overlay-text">Welcome to Our Church</div></div>
            <div class="carousel-item" style="background-image: url('chieftainess.jpg');"><div class="overlay-text">Thank You For Coming</div></div>
            <div class="carousel-item" style="background-image: url('womenoffaith5.jpg');"><div class="overlay-text">Feel the Holy Spirit</div></div>
            <div class="carousel-item active" style="background-image: url('director2.jpg');"><div class="overlay-text">Pray Until Something</div></div>
            <div class="carousel-item" style="background-image: url('power.jpeg');"><div class="overlay-text">Experience The Power Of God</div></div>
            <div class="carousel-item" style="background-image: url('womenoffaith10.jpeg');"><div class="overlay-text"> Praise His Holy Name </div></div>
        </div>
        <div class="carousel-controls">
            <span class="prev">&#10094;</span>
            <span class="next">&#10095;</span>
        </div>
    </div>

    <!-- Recent Sermons Section -->
    <div style="margin-top: 40px;">
        <h1 class="recent-sermons-title" style="font-family: 'Playfair Display', serif; font-size: 3rem; text-align: center; color: #0c0808; margin-bottom: 20px;"><strong>Recent Sermons</strong></h1>
        <?php
        // Securely fetch 5 recent sermons from the correct table
        $sermon_result = false;
        if ($stmt = $conn->prepare("SELECT image, title, date, preacher, link FROM church_sermons ORDER BY date DESC")) {
            $stmt->execute();
            $sermon_result = $stmt->get_result();
            $stmt->close();
        }
        ?>
        <section class="sermon-section">
            <?php
            // Make sermon-section container larger and more prominent
            echo '<style>
                .sermon-section {
                    padding-left: 0 !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                    width: 99vw !important;
                    max-width: 1800px !important;
                    min-width: 320px !important;
                    box-sizing: border-box;
                    background: #f8f9fa;
                    border-radius: 18px;
                    box-shadow: 0 8px 32px rgba(44,62,80,0.10);
                    border: 2px solid #e1e4ea;
                }
                .sermon-card {
                    min-width: 270px !important;
                    max-width: 270px !important;
                    padding: 18px !important;
                    font-size: 1.1rem !important;
                    box-shadow: 0 6px 24px rgba(44,62,80,0.13);
                }
                .sermon-card img {
                    height: 160px !important;
                }
                .sermon-card .title {
                    font-size: 1.35rem !important;
                }
                .sermon-card .description {
                    font-size: 1rem !important;
                }
                @media (max-width: 1200px) {
                    .sermon-section {
                        max-width: 99vw !important;
                    }
                }
                @media (max-width: 768px) {
                    .sermon-section {
                        width: 99vw !important;
                        max-width: 100vw !important;
                        min-width: 220px !important;
                        margin-left: auto !important;
                        margin-right: auto !important;
                    }
                    .sermon-card {
                        min-width: 220px !important;
                        max-width: 240px !important;
                        padding: 12px !important;
                        font-size: 1rem !important;
                    }
                    .sermon-card img {
                        height: 120px !important;
                    }
                    .sermon-card .title {
                        font-size: 1.1rem !important;
                    }
                    .sermon-card .description {
                        font-size: 0.9rem !important;
                    }
                }
            </style>';
            function getYoutubeId($url) {
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?|shorts)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                    return $matches[1];
                }
                return false;
            }
            if ($sermon_result && $sermon_result->num_rows > 0) {
                $hasYoutube = false;
                while($row = $sermon_result->fetch_assoc()) {
                    $youtubeId = getYoutubeId($row["link"]);
                    if ($youtubeId) {
                        $hasYoutube = true;
                        echo '<div class="sermon-card">';
                        echo '<iframe width="100%" height="120" src="https://www.youtube.com/embed/' . htmlspecialchars($youtubeId) . '" frameborder="0" allowfullscreen style="border-radius:8px; margin-bottom:10px;"></iframe>';
                        echo '<div class="content">';
                        echo '<div class="title">' . htmlspecialchars($row["title"]) . '</div>';
                        echo '<div class="description">' . htmlspecialchars(date('F j, Y', strtotime($row["date"]))) . ' | ' . htmlspecialchars($row["preacher"]) . '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                if (!$hasYoutube) {
                    echo "<p>No sermons with YouTube videos available.</p>";
                }
            } else {
                echo "<p>No sermons available.</p>";
            }
            ?>
        </section>
    </div>

    <!-- Contact Section & Testimonials -->
    <section id="contact" class="contact-section">
        <div class="container" style="max-width: 800px; margin: 0 auto; text-align: center;">
            <!-- Testimonial Section -->
            <div class="testimonial-slider" style="margin: 0 auto; max-width: 500px; margin-top: 30px; text-align: left;">
                <?php
                // CSRF token generation
                if (session_status() === PHP_SESSION_NONE) session_start();
                if (empty($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }

                // Handle form submission to add testimonials
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $csrf_token = $_POST['csrf_token'] ?? '';
                    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
                        echo "<p style='color: red;'>Invalid CSRF token.</p>";
                    } else {
                        $name = trim($_POST['name'] ?? '');
                        $testimonial = trim($_POST['testimonial'] ?? '');
                        if (strlen($name) < 2 || strlen($testimonial) < 5) {
                            echo "<p style='color: red;'>Please enter a valid name and testimonial.</p>";
                        } else {
                            // Prepared statement to prevent SQL injection
                            $stmt = $conn->prepare("INSERT INTO testimonials (name, testimonial) VALUES (?, ?)");
                            if ($stmt) {
                                $stmt->bind_param("ss", $name, $testimonial);
                                if ($stmt->execute()) {
                                    echo "<p style='color: green;'>Thank you for your testimonial!</p>";
                                } else {
                                    echo "<p style='color: red;'>Error submitting testimonial.</p>";
                                }
                                $stmt->close();
                            } else {
                                echo "<p style='color: red;'>Error preparing statement.</p>";
                            }
                        }
                    }
                }
                // Fetch testimonials from the database using prepared statement
                $result = $conn->query("SELECT * FROM testimonials ORDER BY id DESC LIMIT 5");
                ?>
                <!-- Testimonial Form -->
                <div style="text-align:center; margin-bottom:20px;">
                    <button id="showTestimonialForm" style="margin-bottom: 0; padding:10px 24px; border-radius:6px; background:#2980b9; color:#fff; border:none; font-weight:700; font-size:1rem; cursor:pointer; display:inline-block;">Add Your Testimonial</button>
                </div>
                <form id="testimonialForm" method="POST" style="margin-bottom: 30px; display:none;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="text" name="name" placeholder="Your Name" required minlength="2" style="padding:8px; border-radius:5px; border:1px solid #ccc; margin-right:10px;">
                    <input type="text" name="testimonial" placeholder="Your Testimonial" required minlength="5" style="padding:8px; border-radius:5px; border:1px solid #ccc; width:300px;">
                    <button type="submit" style="padding:8px 16px; border-radius:5px; background:#3498db; color:#fff; border:none; font-weight:600;">Submit</button>
                    <button type="button" id="closeTestimonialForm" style="padding:8px 16px; border-radius:5px; background:#ccc; color:#333; border:none; font-weight:600; margin-left:10px;">Cancel</button>
                </form>
                <script>
                document.getElementById('showTestimonialForm').addEventListener('click', function() {
                    var form = document.getElementById('testimonialForm');
                    if (form.style.display === 'none') {
                        form.style.display = 'block';
                        this.style.display = 'none';
                    }
                });
                document.getElementById('closeTestimonialForm').addEventListener('click', function() {
                    var form = document.getElementById('testimonialForm');
                    var showBtn = document.getElementById('showTestimonialForm');
                    form.style.display = 'none';
                    showBtn.style.display = 'inline-block';
                });
                </script>
                <h3 style="text-align: center; font-size: 2rem; margin-bottom: 20px; color: #c2cbd3;">What Our Members Say</h3>
                <div style="overflow: hidden; max-width: 100%; position: relative;">
                    <div class="slider-track" style="display: flex; gap: 20px; transition: transform 0.5s; width: 100%;">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<div style="flex: 0 0 300px; background: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1); color: #222;">';
                                echo '<p style="font-style: italic; color: #222;">"' . htmlspecialchars($row["testimonial"]) . '"</p>';
                                echo '<p style="font-weight: bold; text-align: right; color: #007bff;">- ' . htmlspecialchars($row["name"]) . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p style='text-align: center; color:#222;'>No testimonials available.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Map -->
    <section>
        <div class="map-container" style="display: flex; flex-direction: column; align-items: center; margin-top: 40px;">
            <h3 style="text-align: center; color: #0c0808; font-size: 1.5rem; margin-bottom: 20px;">Find Us Here</h3>
            <iframe class="iframe"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3511.1604259214064!2d28.644494411008253!3d-12.981880892503028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x196cb5000996118b%3A0xd10ca0067fcdb915!2sRestoration%20Apostolic%20Pentecostal%20Remnant%20Church!5e1!3m2!1sen!2szm!4v1737616202748!5m2!1sen!2szm"
                width="100%" height="500px" style="border:0; max-width: 1200px;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container" style="padding: 0 20px;">
            <div class="footer-column" style="flex:1; min-width:220px; background:rgba(255,255,255,0.03); border-radius:10px; padding:20px; margin:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; color:#f4d03f;">Contact Us</h3>
                <p style="margin-bottom: 10px; font-size: 15px; color:#fff;"><strong>Phone:</strong> +260 979 888555 | +260 964 888555</p>
                <p style="margin-bottom: 10px; font-size: 15px; color:#fff;"><strong>Emergency (members):</strong> +260 967 or 977 521929</p>
                <p style="margin-bottom: 10px; font-size: 15px; color:#fff;"><strong>Email:</strong> info@mlfc.org</p>
            </div>
            <div class="footer-column" style="background:rgba(255,255,255,0.03); border-radius:10px; padding:20px; margin:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; color:#f4d03f;">Address</h3>
                <p style="margin-bottom: 10px; font-size: 15px; color:#fff;">Plot 256 Foxdale, Zambezi Road</p>
                <p style="margin-bottom: 10px; font-size: 15px; color:#fff;">Chamba Valley, PO Box 32275</p>
                <p style="margin-bottom: 10px; font-size: 15px; color:#fff;">Lusaka, Zambia</p>
            </div>
            <div class="footer-column" style="flex:1; min-width:220px; background:rgba(255,255,255,0.03); border-radius:10px; padding:20px; margin:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05); text-align:center;">
                <h3 style="margin-bottom: 15px; font-size: 18px; text-transform: uppercase; color:#f4d03f;">Follow Us</h3>
                <div class="social-icons" style="display:flex; justify-content:center; gap:18px; margin-top:10px;">
                    <a href="#" target="_blank" style="color:#fff; font-size:22px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" style="color:#fff; font-size:22px;"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank" style="color:#fff; font-size:22px;"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank" style="color:#fff; font-size:22px;"><i class="fab fa-youtube"></i></a>
                </div>
                <hr style="border:none; border-top:1px solid #444; margin:30px 0 10px 0; width:80%;">
                <p style="color:#f4d03f; font-size:1rem; letter-spacing:1px; margin-bottom:10px; text-align:center; font-weight:600;">&copy; 2024 Remnant Church. All Rights Reserved.</p>
            </div>
        </div>
        <div style="width:100%; text-align:center; margin-top:30px;">
            <!-- Copyright moved to Follow Us column above -->
        </div>
    </footer>

    <script>
        // Carousel Functionality
        let currentIndex = 0;
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

        // Testimonial slider (autoplay)
        let testimonialIndex = 0;
        const testimonialTrack = document.querySelector('.slider-track');
        function moveSlider(direction) {
            const itemWidth = 300 + 20;
            testimonialIndex = (testimonialIndex + direction + 5) % 5;
            testimonialTrack.style.transform = `translateX(-${testimonialIndex * itemWidth}px)`;
        }
        setInterval(() => moveSlider(1), 5000);
    </script>
</body>
</html>
