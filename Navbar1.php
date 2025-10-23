<?php
// Navbar1.php: Extracted navbar from index.php with all styles and JS for reuse
?>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
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
    }
    /* Marquee styles for church name */
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

<header>
    <nav class="navbar">
        <div style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
            <a href="index.php" class="logo" style="flex:0 0 auto;"><img src="logo.jpg" alt="Church Logo" style="height: 50px;"></a>
            <div class="church-name-marquee">
                <span>Restoration Apostolic Pentecostal Remnant Church</span>
            </div>
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