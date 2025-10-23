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
include('db_connect.php');

// Delete expired upcoming events (event_date more than 7 days ago, but not past events)
if (isset($conn)) {
    $delete_stmt = $conn->prepare("DELETE FROM events WHERE event_date < DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND event_date >= CURDATE()");
    $delete_stmt->execute();
    $delete_stmt->close();
}

// Handle form submission for adding/updating events
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_type = $_POST['form_type'] ?? '';
    $title = $_POST['title'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $description = $_POST['description'] ?? '';
    $image = $_POST['image'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $link = $_POST['link'] ?? '';
    if (!empty($title) && !empty($event_date)) {
        if ($form_type === 'upcoming') {
            // Insert as upcoming event (date in future)
            $stmt = $conn->prepare("INSERT INTO events (title, event_date, description, image, video_url, link) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $title, $event_date, $description, $image, $video_url, $link);
            $stmt->execute();
            $stmt->close();
            $success = 'Upcoming event added successfully!';
        } elseif ($form_type === 'past') {
            // Insert as past event (date in past)
            $stmt = $conn->prepare("INSERT INTO events (title, event_date, description, image, video_url, link) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $title, $event_date, $description, $image, $video_url, $link);
            $stmt->execute();
            $stmt->close();
            $success = 'Past event added successfully!';
        }
    } else {
        $error = 'Title and Event Date are required.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Event Manager</title>
    <style>
        body { background: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); padding: 30px; }
        h1 { text-align: center; color: #23295e; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; gap: 18px; }
        label { font-weight: 600; color: #23295e; }
        input, textarea { padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; }
        textarea { resize: vertical; min-height: 60px; }
        button { background: #23295e; color: #fff; border: none; border-radius: 6px; padding: 12px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #f4d03f; color: #23295e; }
        .success { color: green; text-align: center; margin-bottom: 10px; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .dropdown-content-form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 18px 12px;
            margin-top: 2px;
        }
        .dropbtn:focus {
            outline: 2px solid #23295e;
        }
        .events-section {
            max-width: 1800px;
            width: 99vw;
            margin: 0 auto;
            padding: 30px 0;
        }
        .events-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }
        .event-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 15px;
            min-width: 260px;
            max-width: 340px;
            flex: 1 1 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 10px;
        }
        .event-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            background: #fff;
        }
        .event-card h2 {
            font-size: 1.15rem;
            color: #23295e;
            margin: 10px 0 5px 0;
            text-align: center;
        }
        .event-card p {
            font-size: 0.98rem;
            color: #555;
            margin: 5px 0;
            text-align: center;
        }
        .event-card .event-date {
            font-size: 0.95rem;
            color: #888;
            margin-bottom: 8px;
        }
        .event-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 18px;
            background-color: #23295e;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
        }
        .event-card a:hover {
            background-color: #f4d03f;
            color: #23295e;
        }
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Event Manager</h1>
        <?php if (!empty($success)) echo '<div class="success">' . htmlspecialchars($success) . '</div>'; ?>
        <?php if (!empty($error)) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>
        <div style="margin-bottom:30px;">
            <button class="dropbtn" onclick="toggleDropdown('upcomingForm')" type="button" style="width:100%;background:#23295e;color:#fff;padding:14px 0;font-size:1.1rem;border:none;border-radius:6px;cursor:pointer;">Add Upcoming Event / Announcement ▼</button>
            <div id="upcomingForm" class="dropdown-content-form" style="display:none;">
                <form method="post" action="" style="margin-top:18px;">
                    <input type="hidden" name="form_type" value="upcoming">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" required>
                    <label for="event_date">Event Date *</label>
                    <input type="date" id="event_date" name="event_date" required>
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                    <label for="image">Image Filename (e.g. event1.jpg)</label>
                    <input type="text" id="image" name="image">
                    <label for="video_url">YouTube Video URL (optional)</label>
                    <input type="text" id="video_url" name="video_url">
                    <label for="link">External Link (optional)</label>
                    <input type="text" id="link" name="link">
                    <button type="submit">Save Upcoming Event</button>
                </form>
            </div>
        </div>
        <div style="margin-bottom:30px;">
            <button class="dropbtn" onclick="toggleDropdown('pastForm')" type="button" style="width:100%;background:#888;color:#fff;padding:14px 0;font-size:1.1rem;border:none;border-radius:6px;cursor:pointer;">Add Past Event (with Video) ▼</button>
            <div id="pastForm" class="dropdown-content-form" style="display:none;">
                <form method="post" action="" style="margin-top:18px;">
                    <input type="hidden" name="form_type" value="past">
                    <label for="title_past">Event Title *</label>
                    <input type="text" id="title_past" name="title" required>
                    <label for="event_date_past">Event Date *</label>
                    <input type="date" id="event_date_past" name="event_date" required>
                    <label for="description_past">Description</label>
                    <textarea id="description_past" name="description"></textarea>
                    <label for="image_past">Image Filename (e.g. event1.jpg)</label>
                    <input type="text" id="image_past" name="image">
                    <label for="video_url_past">YouTube Video URL (optional)</label>
                    <input type="text" id="video_url_past" name="video_url">
                    <label for="link_past">External Link (optional)</label>
                    <input type="text" id="link_past" name="link">
                    <button type="submit">Save Past Event</button>
                </form>
            </div>
        </div>
        <hr style="margin:40px 0;">
        <h2 style="color:#23295e;">Upcoming Events / Announcements Preview</h2>
        <div class="events-section">
            <div class="events-row">
                <?php
                $upcoming_preview = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
                if ($upcoming_preview && $upcoming_preview->num_rows > 0) {
                    function getYoutubeId($url) {
                        if (preg_match('/(?:youtube\\.com\\/(?:[^\\/]+\\/\\S+\\/|(?:v|e(?:mbed)?|shorts)\\/|\\S*?[?&]v=)|youtu\\.be\\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                            return $matches[1];
                        }
                        return false;
                    }
                    while ($row = $upcoming_preview->fetch_assoc()) {
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
        </div>
        <hr style="margin:40px 0;">
        <h2 style="color:#888;">Past Events Preview</h2>
        <div class="events-section">
            <div class="events-row">
                <?php
                $past_preview = $conn->query("SELECT * FROM events WHERE event_date < CURDATE() ORDER BY event_date DESC");
                if ($past_preview && $past_preview->num_rows > 0) {
                    while ($row = $past_preview->fetch_assoc()) {
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
        </div>
    </div>
    <script>
    function toggleDropdown(id) {
        var el = document.getElementById(id);
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
    }
    </script>
</body>
</html>
