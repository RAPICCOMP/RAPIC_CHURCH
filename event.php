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

// Helper function to get YouTube ID from URL
function getYoutubeId($url) {
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/\S+\/|(?:v|e(?:mbed)?|shorts)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    return false;
}

// Delete past events older than 1 week from "past events" section
$delete_stmt = $conn->prepare("DELETE FROM events WHERE event_date < DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$delete_stmt->execute();
$delete_stmt->close();

// Fetch upcoming events (announcements)
$upcoming_stmt = $conn->prepare("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
$upcoming_stmt->execute();
$upcoming_events = $upcoming_stmt->get_result();
$upcoming_stmt->close();

// Fetch past events (within last week)
$past_stmt = $conn->prepare("SELECT * FROM events WHERE event_date < CURDATE() AND event_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ORDER BY event_date DESC");
$past_stmt->execute();
$past_events = $past_stmt->get_result();
$past_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Events | Restoration Apostolic Pentecostal Remnant Church</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Responsive global styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f5f5; padding-top: 70px; animation: fadeIn 1s ease-in-out; }
        .container { width: 90vw; max-width: 1200px; margin: 0 auto; }
        @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px 20px; min-height: 70px; position: fixed; top: 0; width: 100vw; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.3s; opacity: 0; animation: slideDown 0.7s forwards 0.3s; }
        @keyframes slideDown { 0% { transform: translateY(-50px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .navbar .logo img { width: 60px; height: auto; transition: transform 0.3s ease; }
        .navbar .logo:hover img { transform: scale(1.1); }
        .church-name-marquee { flex: 1; margin: 0 10px; overflow: hidden; white-space: nowrap; position: relative; min-width: 0; text-align: left; display: flex; align-items: center; height: 50px; }
        .church-name-marquee span { display: inline-block; padding-left: 100%; animation: marquee 16s linear infinite; font-size: 16px; color: #333; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; line-height: 50px; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
        @media (max-width: 768px) {
            .navbar { min-height: 40px; padding: 2px 5px; }
            .church-name-marquee { height: 32px; }
            .church-name-marquee span { font-size: 0.95rem; line-height: 32px; }
            .container { width: 98vw; max-width: 100vw; padding: 0 2vw; }
        }
        @media (max-width: 480px) {
            .navbar { min-height: 24px; padding: 1px 5px; }
            .church-name-marquee { height: 24px; }
            .church-name-marquee span { font-size: 0.8rem; line-height: 24px; }
            .container { width: 100vw; max-width: 100vw; padding: 0 1vw; }
        }
        .nav-links { display: flex; list-style-type: none; transition: transform 0.5s ease-in-out; opacity: 0; animation: fadeInLinks 1s ease-in-out 1.2s forwards; flex-wrap: wrap; }
        @keyframes fadeInLinks { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .nav-links li { margin: 0 10px; }
        .nav-links a { color: #333; text-decoration: none; font-size: 15px; text-transform: uppercase; position: relative; padding-bottom: 5px; transition: all 0.3s ease, transform 0.3s ease; animation: slideIn 0.6s ease-in-out; }
        @keyframes slideIn { 0% { transform: translateX(-50px); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        .nav-links a::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background-color: #f4d03f; transform: scaleX(0); transform-origin: bottom right; transition: transform 0.25s ease-out; }
        .nav-links a:hover::after { transform: scaleX(1); transform-origin: bottom left; }
        .nav-links a:hover { color: #f4d03f; transform: translateY(-3px); }
        .hamburger { display: none; flex-direction: column; cursor: pointer; transition: transform 0.3s ease; }
        .hamburger .bar { width: 30px; height: 3px; margin: 6px 0; background-color: #333; border-radius: 5px; transition: all 0.3s ease; }
        .hamburger.active .bar:nth-child(1) { transform: translateY(9px) rotate(45deg); }
        .hamburger.active .bar:nth-child(2) { opacity: 0; }
        .hamburger.active .bar:nth-child(3) { transform: translateY(-9px) rotate(-45deg); }
        @media screen and (max-width: 768px) {
            .nav-links { display: none; flex-direction: column; width: 100vw; position: absolute; top: 70px; left: 0; background-color: #fff; padding: 20px 0; text-align: center; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
            .nav-links.active { display: flex; }
            .hamburger { display: flex; }
        }
        /* Event Card Styles */
        .events-section { max-width: 1800px; width: 99vw; margin: 0 auto; padding: 30px 0; }
        .events-title { font-size: 2.2rem; font-weight: 700; margin-bottom: 30px; text-align: left; color: #23295e; letter-spacing: 2px; }
        .events-row { display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start; }
        .event-card { background: #fff; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); overflow: hidden; padding: 15px; min-width: 260px; max-width: 340px; flex: 1 1 260px; display: flex; flex-direction: column; align-items: center; transition: transform 0.3s, box-shadow 0.3s; }
        .event-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 8px; background: #fff; }
        .event-card h2 { font-size: 1.15rem; color: #23295e; margin: 10px 0 5px 0; text-align: center; }
        .event-card p { font-size: 0.98rem; color: #555; margin: 5px 0; text-align: center; }
        .event-card .event-date { font-size: 0.95rem; color: #888; margin-bottom: 8px; }
        .event-card a { display: inline-block; margin-top: 10px; padding: 8px 18px; background-color: #23295e; color: white; text-decoration: none; border-radius: 4px; font-size: 1rem; }
        .event-card a:hover { background-color: #f4d03f; color: #23295e; }
        @media (max-width: 992px) {
            .events-title { font-size: 1.5rem; }
            .events-row { gap: 12px; }
            .event-card { min-width: 180px; max-width: 98vw; padding: 10px; }
            .event-card img { height: 120px; }
        }
        @media (max-width: 768px) {
            .events-section { width: 100vw !important; max-width: 100vw !important; padding: 10px 0; }
            .events-title { font-size: 1.2rem; text-align: center; margin-bottom: 18px; }
            .events-row { flex-direction: row; flex-wrap: nowrap; overflow-x: auto; scroll-snap-type: x mandatory; gap: 10px; width: 100vw; justify-content: flex-start; }
            .event-card { min-width: 220px; max-width: 80vw; scroll-snap-align: start; }
            .event-card img { height: 100px; }
        }
        @media (max-width: 480px) {
            .events-section { width: 100vw !important; max-width: 100vw !important; padding: 5px 0; }
            .events-title { font-size: 1rem; text-align: center; margin-bottom: 10px; }
            .events-row { flex-direction: row; flex-wrap: nowrap; overflow-x: auto; scroll-snap-type: x mandatory; gap: 8px; width: 100vw; justify-content: flex-start; }
            .event-card { min-width: 160px; max-width: 90vw; scroll-snap-align: start; padding: 6px; }
            .event-card img { height: 70px; }
            .event-card h2 { font-size: 0.9rem; }
            .event-card p, .event-card .event-date { font-size: 0.8rem; }
        }
        /* Footer styles (copied from sermon.php) */
        .footer { background-color: #333; color: #fff; padding: 40px 0; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; text-align: center; }
        .footer-container { display: flex; justify-content: space-between; flex-wrap: wrap; max-width: 1200px; width: 100vw; }
        .footer-column { flex: 1; min-width: 180px; margin: 10px; text-align: left; }
        .footer-column h3 { margin-bottom: 15px; font-size: 18px; text-transform: uppercase; }
        .footer-column p { margin-bottom: 10px; font-size: 14px; }
        .social-icons { display: flex; justify-content: center; margin-top: 10px; }
        .social-icons a { color: #fff; margin-right: 15px; font-size: 20px; text-decoration: none; }
        .social-icons a:hover { color: #1e90ff; }
        @media (max-width: 768px) {
            .footer-container { flex-direction: column; align-items: center; text-align: center; }
            .footer-column { text-align: center; margin-bottom: 20px; min-width: unset; }
            .social-icons { justify-content: center; }
            .social-icons a { margin-right: 10px; }
        }
        @media (max-width: 480px) {
            .footer-column h3 { font-size: 16px; }
            .footer-column p { font-size: 12px; }
            .social-icons a { font-size: 18px; }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="logo.jpg" alt="Church Logo">
            </div>
            <div class="church-name-marquee">
                <span>Restoration Apostolic Pentecostal Remnant Church</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="mission.html">Mission</a></li>
                <li><a href="vision.html">Vision</a></li>
                <li><a href="history.html">History</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="event.php">Events</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </nav>
    </header>
    <div class="container">
        <section class="events-section">
            <h1 class="events-title">Announcements & Upcoming Events</h1>
            <div class="events-row">
                <?php if ($upcoming_events && $upcoming_events->num_rows > 0) {
                    while ($row = $upcoming_events->fetch_assoc()) {
                        echo '<div class="event-card">';
                        if (!empty($row['video_url']) && getYoutubeId($row['video_url'])) {
                            $youtubeId = getYoutubeId($row['video_url']);
                            echo '<div style="width:100%;height:180px;margin-bottom:10px;">';
                            echo '<iframe width="100%" height="180" src="https://www.youtube.com/embed/' . htmlspecialchars($youtubeId) . '" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe>';
                            echo '</div>';
                        } elseif (!empty($row['image'])) {
                            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                        }
                        echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                        echo '<p class="event-date">' . htmlspecialchars(date('F j, Y', strtotime($row['event_date']))) . '</p>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        if (!empty($row['link'])) {
                            echo '<a href="' . htmlspecialchars($row['link']) . '">Learn More</a>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>No upcoming events or announcements.</p>';
                }
                ?>
            </div>
        </section>

        <!-- Past Events Section -->
        <section class="events-section">
            <h1 class="events-title" style="color:#888;">Past Events</h1>
            <div class="events-row">
                <?php if ($past_events && $past_events->num_rows > 0) {
                    while ($row = $past_events->fetch_assoc()) {
                        echo '<div class="event-card">';
                        if (!empty($row['video_url']) && getYoutubeId($row['video_url'])) {
                            $youtubeId = getYoutubeId($row['video_url']);
                            echo '<div style="width:100%;height:180px;margin-bottom:10px;">';
                            echo '<iframe width="100%" height="180" src="https://www.youtube.com/embed/' . htmlspecialchars($youtubeId) . '" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe>';
                            echo '</div>';
                        } elseif (!empty($row['image'])) {
                            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                        }
                        echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                        echo '<p class="event-date">' . htmlspecialchars(date('F j, Y', strtotime($row['event_date']))) . '</p>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        if (!empty($row['link'])) {
                            echo '<a href="' . htmlspecialchars($row['link']) . '">View Details</a>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p>No past events to display.</p>';
                }
                ?>
            </div>
        </section>
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
