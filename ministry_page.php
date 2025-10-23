<?php
include('db_connect.php');
session_start();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT * FROM ministries WHERE id = $id LIMIT 1");
if ($result && $result->num_rows > 0) {
    $ministry = $result->fetch_assoc();
} else {
    die('Ministry not found.');
}

// --- Improved YouTube ID extraction function ---
function getYouTubeId($url) {
    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([A-Za-z0-9_-]{11})%i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return '';
}

// Fetch content for this ministry card only
$gallery = [];
$gallery_result = $conn->query("SELECT * FROM ministry_gallery WHERE ministry_id = $id");
if ($gallery_result && $gallery_result->num_rows > 0) {
    while ($row = $gallery_result->fetch_assoc()) {
        $gallery[] = $row['image'];
    }
}

$meetings = [];
$meetings_result = $conn->query("SELECT * FROM ministry_meetings WHERE ministry_id = $id ORDER BY meeting_date DESC");
if ($meetings_result && $meetings_result->num_rows > 0) {
    while ($row = $meetings_result->fetch_assoc()) {
        $meetings[] = $row;
    }
}

$videos = [];
$videos_result = $conn->query("SELECT * FROM ministry_videos WHERE ministry_id = $id");
if ($videos_result && $videos_result->num_rows > 0) {
    while ($row = $videos_result->fetch_assoc()) {
        $videos[] = $row;
    }
}

// Fetch slideshow images from the new ministry_slideshow table
$slideshow_images = [];
$slideshow_result = $conn->query("SELECT image FROM ministry_slideshow WHERE ministry_id = $id ORDER BY uploaded_at ASC, id ASC");
if ($slideshow_result && $slideshow_result->num_rows > 0) {
    while ($row = $slideshow_result->fetch_assoc()) {
        $slideshow_images[] = $row['image'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ministry['name']) ?> - Ministry</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2a2a72;
            --accent: #f4d03f;
            --bg: #f9f9f9;
            --white: #fff;
            --shadow: 0 4px 16px rgba(44,62,80,0.07);
            --radius: 14px;
        }
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: var(--bg);
            color: #222;
        }
        header {
            background: var(--primary);
            color: var(--white);
            text-align: center;
            padding: 40px 10px 60px 10px;
            border-radius: 0 0 var(--radius) var(--radius);
        }
        h1 {
            font-size: 2.2rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 8px;
        }
        h2 {
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0.5em;
        }
        .container {
            max-width: 1200px;
            margin: -40px auto 0 auto;
            padding: 0 16px 32px 16px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 32px;
        }
        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
        }
        .section {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 28px 24px 24px 24px;
            margin-bottom: 0;
        }
        .slideshow {
            position: relative;
            width: 100%;
            aspect-ratio: 16/7;
            overflow: hidden;
            border-radius: var(--radius);
            margin-bottom: 24px;
            background: #eaeaea;
        }
        .slideshow img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            position: absolute;
            top: 0; left: 0;
            border-radius: var(--radius);
            transition: opacity 0.6s;
        }
        .slideshow img.active {
            display: block;
            opacity: 1;
            z-index: 1;
        }
        .gallery-section h3 {
            font-size: 1.1rem;
            color: var(--primary);
            border-left: 4px solid var(--primary);
            padding-left: 12px;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
        }
        .gallery img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            border-radius: var(--radius);
            box-shadow: 0 2px 8px rgba(44,62,80,0.09);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .gallery img:hover {
            transform: scale(1.04);
        }
        .view-more {
            display: none;
        }
        .view-more-btn {
            margin: 18px auto 0 auto;
            display: block;
            padding: 10px 26px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            transition: background 0.2s;
        }
        .view-more-btn:hover {
            background: #1e1e5b;
        }
        .video-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 22px;
        }
        .video-item {
            background: #fafbfc;
            border-radius: var(--radius);
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            padding: 12px 12px 18px 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .video-item iframe,
        .video-item video {
            width: 100%;
            max-width: 100%;
            height: 220px;
            border-radius: 10px;
            margin-bottom: 10px;
            background: #000;
        }
        .fullscreen-btn {
            margin: 6px 0 0 0;
            padding: 7px 18px;
            background: var(--accent);
            color: #222;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.98rem;
            transition: background 0.2s;
        }
        .fullscreen-btn:hover {
            background: #ffe066;
        }
        .video-item p {
            margin: 8px 0 0 0;
            font-size: 1rem;
            font-weight: 600;
        }
        .brief-message {
            font-size: 0.97rem;
            color: #555;
            margin: 4px 0 0 0;
        }
        .meetings {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .meeting-card {
            background: #f5f7fa;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(44,62,80,0.06);
            padding: 18px 18px 12px 18px;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .meeting-card h3 {
            margin: 0 0 4px 0;
            font-size: 1.08rem;
            color: var(--primary);
            font-weight: 700;
        }
        .meeting-card p {
            margin: 0;
            font-size: 0.98rem;
        }
        .ministry-image {
            width: 100%;
            max-width: 340px;
            border-radius: var(--radius);
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.09);
            display: block;
        }
        .desc-content-wrap {
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .desc-content-wrap > div {
            flex: 1 1 320px;
        }
        @media (max-width: 900px) {
            .desc-content-wrap {
                flex-direction: column;
                gap: 18px;
            }
            .main-grid {
                gap: 18px;
            }
        }
        @media (max-width: 700px) {
            .container {
                padding: 0 4px 20px 4px;
                gap: 18px;
            }
            .section {
                padding: 14px 6px 14px 6px;
            }
            .slideshow {
                aspect-ratio: 16/10;
            }
            .video-item iframe,
            .video-item video {
                height: 170px;
            }
            .main-grid {
                gap: 12px;
            }
        }
        @media (max-width: 500px) {
            .slideshow {
                aspect-ratio: 16/13;
            }
            .video-item iframe,
            .video-item video {
                height: 120px;
            }
            .ministry-image {
                max-width: 100%;
            }
            .main-grid {
                gap: 8px;
            }
            .section {
                padding: 8px 2px 8px 2px;
            }
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.85);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal img {
            max-width: 92vw;
            max-height: 80vh;
            border-radius: var(--radius);
            box-shadow: 0 2px 12px rgba(44,62,80,0.14);
        }
        .modal .prev, .modal .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2.5rem;
            color: var(--white);
            cursor: pointer;
            padding: 12px;
            background: rgba(0, 0, 0, 0.45);
            border-radius: 50%;
            z-index: 2;
            user-select: none;
        }
        .modal .prev { left: 3vw; }
        .modal .next { right: 3vw; }
        footer {
            background: #333;
            color: var(--white);
            text-align: center;
            padding: 22px 0 18px 0;
            font-size: 1rem;
            border-radius: var(--radius) var(--radius) 0 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <?php include 'navbar1.php'; ?>
    <header style="background: var(--primary); color: var(--white); text-align: center; padding: 40px 10px 60px 10px; border-radius: 0 0 var(--radius) var(--radius);">
        <h1>Ministry Page</h1>
        <h2><?= htmlspecialchars($ministry['name']) ?></h2>
    </header>
    <div class="container">
        <div class="main-grid">
            <div>
                <!-- Slideshow Section -->
                <div class="section slideshow" id="slideshow">
                    <?php if (count($slideshow_images) > 0): ?>
                        <?php foreach ($slideshow_images as $i => $img): ?>
                            <img src="<?= htmlspecialchars($img) ?>" alt="Slide <?= $i+1 ?>" class="<?= $i === 0 ? 'active' : '' ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align:center;padding:80px 0;">No slideshow images posted yet.</div>
                    <?php endif; ?>
                </div>
                <!-- Gallery Section -->
                <div class="section">
                    <div class="gallery-section">
                        <h3><?= htmlspecialchars($ministry['name']) ?> Gallery</h3>
                        <div class="gallery">
                            <?php if (count($gallery) > 0): ?>
                                <?php foreach ($gallery as $i => $img): ?>
                                    <?php if ($i < 3): ?>
                                        <img src="<?= htmlspecialchars($img) ?>" alt="Gallery Image <?= $i+1 ?>" data-index="<?= $i ?>">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="view-more">
                                    <?php foreach ($gallery as $i => $img): ?>
                                        <?php if ($i >= 3): ?>
                                            <img src="<?= htmlspecialchars($img) ?>" alt="Gallery Image <?= $i+1 ?>" data-index="<?= $i ?>">
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div style="text-align:center;width:100%;">No gallery images posted yet.</div>
                            <?php endif; ?>
                        </div>
                        <?php if (count($gallery) > 3): ?>
                            <button class="view-more-btn">View More</button>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Video Section -->
                <div class="section">
                    <h2>Events Videos</h2>
                    <div class="video-gallery">
                        <?php if (count($videos) > 0): ?>
                            <?php foreach ($videos as $video): ?>
                                <div class="video-item">
                                    <?php
                                    $ytid = getYouTubeId($video['video_url']);
                                    if ($ytid): ?>
                                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($ytid) ?>" allowfullscreen></iframe>
                                    <?php elseif (preg_match('/\.mp4$/i', $video['video_url'])): ?>
                                        <video controls>
                                            <source src="<?= htmlspecialchars($video['video_url']) ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <button class="fullscreen-btn">View Fullscreen</button>
                                    <?php else: ?>
                                        <div style="color:red;">Invalid video URL.</div>
                                    <?php endif; ?>
                                    <p><?= htmlspecialchars($video['title'] ?? $ministry['name'].' Event') ?></p>
                                    <?php if (!empty($video['description'])): ?>
                                        <p class="brief-message"><?= htmlspecialchars($video['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;width:100%;">No videos posted yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div>
                <!-- Meetings Section -->
                <div class="section">
                    <h2>Upcoming Meetings</h2>
                    <div class="meetings">
                        <?php if (count($meetings) > 0): ?>
                            <?php foreach ($meetings as $meeting): ?>
                                <div class="meeting-card">
                                    <h3><?= htmlspecialchars($meeting['title']) ?></h3>
                                    <p><?= htmlspecialchars($meeting['description']) ?></p>
                                    <p><strong>Date:</strong> <?= htmlspecialchars(date('F j, Y', strtotime($meeting['meeting_date']))) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center;width:100%;">No meetings posted yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Ministry Description and Content -->
                <div class="section">
                    <div class="desc-content-wrap">
                        <div>
                            <?php if (!empty($ministry['image'])): ?>
                                <img src="<?= htmlspecialchars($ministry['image']) ?>" alt="<?= htmlspecialchars($ministry['name']) ?>" class="ministry-image">
                            <?php endif; ?>
                        </div>
                        <div>
                            <h2>Description</h2>
                            <p><?= nl2br(htmlspecialchars($ministry['description'])) ?></p>
                            <h2>Full Content</h2>
                            <div><?= nl2br(htmlspecialchars($ministry['content'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Our Ministry. All rights reserved.</p>
    </footer>
    <div class="modal" id="modal">
        <span class="prev">&lt;</span>
        <img src="" alt="Full Image" id="modal-image">
        <span class="next">&gt;</span>
    </div>
    <script>
        // Slideshow functionality
        const slides = document.querySelectorAll('.slideshow img');
        let currentSlide = 0;
        function showSlide(index) {
            if (!slides.length) return;
            slides[currentSlide].classList.remove('active');
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        if (slides.length > 1) {
            setInterval(() => showSlide(currentSlide + 1), 3500);
        }
        // Gallery modal functionality
        const modal = document.getElementById('modal');
        const modalImage = document.getElementById('modal-image');
        const galleryImages = document.querySelectorAll('.gallery img');
        let currentImageIndex = 0;
        galleryImages.forEach((img, index) => {
            img.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalImage.src = img.src;
                currentImageIndex = index;
            });
        });
        document.querySelector('.modal .prev').addEventListener('click', () => {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            modalImage.src = galleryImages[currentImageIndex].src;
        });
        document.querySelector('.modal .next').addEventListener('click', () => {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            modalImage.src = galleryImages[currentImageIndex].src;
        });
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        // View More functionality
        const viewMoreButtons = document.querySelectorAll('.view-more-btn');
        viewMoreButtons.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                const gallerySection = e.target.previousElementSibling;
                const viewMoreImages = gallerySection.querySelector('.view-more');
                if (viewMoreImages.style.display === 'block') {
                    viewMoreImages.style.display = 'none';
                    btn.textContent = 'View More';
                } else {
                    viewMoreImages.style.display = 'block';
                    btn.textContent = 'View Less';
                }
            });
        });
        // Fullscreen functionality
        document.querySelectorAll('.fullscreen-btn').forEach((button, index) => {
            button.addEventListener('click', () => {
                let video = button.previousElementSibling;
                if (video.requestFullscreen) {
                    video.requestFullscreen();
                } else if (video.mozRequestFullScreen) {
                    video.mozRequestFullScreen();
                } else if (video.webkitRequestFullscreen) {
                    video.webkitRequestFullscreen();
                } else if (video.msRequestFullscreen) {
                    video.msRequestFullscreen();
                }
            });
        });
    </script>
</body>
</html>
