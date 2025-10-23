
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remnant Women of Faith Ministry</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            overflow-x: hidden;
        }

        header {
            background: #2a2a72;
            color: white;
            text-align: center;
            padding: 20px;
            
            
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
            box-sizing: border-box;
        }

        .section {
            margin-bottom: 40px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Slideshow styles */
        .slideshow {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            border-radius: 8px;
        }

        .slideshow img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            position: absolute;
            top: 0;
            left: 0;
        }

        .slideshow img.active {
            display: block;
        }

        /* Gallery styles */
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .gallery img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .gallery-section {
            margin-bottom: 20px;
        }

        .gallery-section h3 {
            border: 2px solid #2a2a72;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .view-more {
            display: none;
        }

        .view-more-btn {
            margin-top: 10px;
            padding: 10px 20px;
            background: #2a2a72;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .view-more-btn:hover {
            background: #1e1e5b;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal img {
            max-width: 90%;
            max-height: 80%;
            border-radius: 8px;
        }

        .modal .prev, .modal .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            color: white;
            cursor: pointer;
            padding: 10px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }

        .modal .prev {
            left: 5%;
        }

        .modal .next {
            right: 5%;
        }

        /* Meetings Section */
        .meetings {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .meeting-card {
            flex: 1 1 calc(33.333% - 20px);
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Video Section */
        .video-section {
            text-align: center;
        }

        .video-section iframe {
            width: 100%;
            height: 500px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media screen and (max-width: 1024px) {
            .slideshow {
                height: 400px;
            }

            .video-section iframe {
                height: 400px;
            }
        }

        @media screen and (max-width: 768px) {
            .slideshow {
                height: 300px;
            }

            .video-section iframe {
                height: 300px;
            }

            .meeting-card {
                flex: 1 1 100%;
            }
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }

        #video-section {
    padding: 50px 20px;
    background-color: #f4f4f4;
    text-align: center;
}

.video-gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.video-item {
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.video-item video {
    width: 100%;
    border-radius: 10px 10px 0 0;
}

.video-item p {
    padding: 10px;
    background-color: #fff;
    font-size: 16px;
    color: #333;
}

.hidden {
    display: none;
}

.fullscreen-btn {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

.fullscreen-btn:hover {
    background-color: #0056b3;
}

#view-more-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
}

#view-more-btn:hover {
    background-color: #218838;
}

.brief-message {
    font-size: 14px;
    color: #777;
    margin-top: 10px;
}


/* Navbar Styles */
/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 10px 0; /* Set padding to 0 on left and right for full width */
    position: fixed;
    top: 0;
    left: 0; /* Ensure navbar starts at the left edge */
    right: 0; /* Ensure navbar spans the full width */
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
        

    </style>
</head>
<body>
    <header>
        <h1>Remnant Women of Faith Ministry</h1>
        <header>
          <nav class="navbar">
              <div class="logo">
                  <!-- Replace with your smaller logo file -->
                  <img src="logo.jpg" alt="Church Logo">
              </div>
              <div class="church-name">
                  Restoration Apostolic Pentecostal Remnant Church
              </div>
              <ul class="nav-links">
                  <li><a href="index.html">Home</a></li>
                  <li><a href="about.html">About</a></li>
                  <li><a href="#">Services</a></li>
                  <li><a href="#">Contact</a></li>
              </ul>
              <div class="hamburger" id="hamburger">
                  <div class="bar"></div>
                  <div class="bar"></div>
                  <div class="bar"></div>
              </div>
          </nav>
      </header>
  
    </header>


    <div class="container">
        <!-- Slideshow Section -->
        <div class="section slideshow" id="slideshow">
            <img src="all2.jpg" alt="Slide 1" class="active">
            <img src="bible.jpg" alt="Slide 2">
            <img src="chieftainess2.jpg" alt="Slide 3">
        </div>



      
        <!-- Gallery Section -->

        



        <?php
include 'db_connect.php'; // Connect to the database

$events = $conn->query("SELECT DISTINCT event_name FROM event_gallery");

while ($event = $events->fetch_assoc()) {
    echo "<div class='gallery-section'>";
    echo "<h3>" . htmlspecialchars($event['event_name']) . "</h3>";
    echo "<div class='gallery'>";

    // Fetch all images for this event
    $images = $conn->query("SELECT image_path FROM event_gallery WHERE event_name='" . $event['event_name'] . "'");
    
    $count = 0;
    while ($image = $images->fetch_assoc()) {
        // First 3 images are visible, others are hidden
        $display = ($count < 3) ? "block" : "none";
        echo "<img src='" . htmlspecialchars($image['image_path']) . "' alt='Gallery Image' class='gallery-image' style='display: $display;'>";
        $count++;
    }

    echo "</div>";

    // Show "View More" button only if there are more than 3 images
    if ($count > 3) {
        echo "<button class='view-more-btn'>View More</button>";
    }

    echo "</div>";
}
?>

<!-- JavaScript for View More / View Less -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.gallery-section').forEach(section => {
        const images = section.querySelectorAll('.gallery-image');
        const button = section.querySelector('.view-more-btn');

        if (button) {
            button.addEventListener('click', () => {
                let isExpanded = button.textContent === 'View Less';

                images.forEach((img, index) => {
                    if (index >= 3) {
                        img.style.display = isExpanded ? 'none' : 'block';
                    }
                });

                button.textContent = isExpanded ? 'View More' : 'View Less';
            });
        }
    });
});
</script>

<?php
include 'db_connect.php'; // Connect to the database

// Fetch all meetings
$meetings = $conn->query("SELECT * FROM women_meetings ORDER BY meeting_date DESC");

echo '<div class="section">';
echo '<h2>Upcoming Meetings</h2>';
echo '<div class="meetings">';

// Loop through meetings and display them
while ($meeting = $meetings->fetch_assoc()) {
    echo "<div class='meeting-card'>";
    echo "<h3>" . htmlspecialchars($meeting['meeting_title']) . "</h3>";
    echo "<p>" . htmlspecialchars($meeting['meeting_description']) . "</p>";
    echo "</div>";
}

echo '</div>';
echo '</div>';
?>

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
            slides[currentSlide].classList.remove('active');
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        setInterval(() => showSlide(currentSlide + 1), 3000);

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
        } else if (video.mozRequestFullScreen) { // Firefox
            video.mozRequestFullScreen();
        } else if (video.webkitRequestFullscreen) { // Chrome, Safari
            video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) { // IE/Edge
            video.msRequestFullscreen();
        }
    });
});

// Toggle "View More Videos" section
document.getElementById('view-more-btn').addEventListener('click', () => {
    const moreVideos = document.getElementById('more-videos');
    moreVideos.classList.toggle('hidden');
    const btnText = moreVideos.classList.contains('hidden') ? 'View More Videos' : 'View Less Videos';
    document.getElementById('view-more-btn').textContent = btnText;
});



const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    </script>
</body>
</html>
