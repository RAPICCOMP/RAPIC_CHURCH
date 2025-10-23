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
            display: block;
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

        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo img {
            width: 80px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .navbar .church-name {
            font-size: 16px;
            color: #333;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            list-style-type: none;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            text-transform: uppercase;
            padding-bottom: 5px;
        }

        /* Hamburger Menu Styles */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger .bar {
            width: 30px;
            height: 3px;
            margin: 6px 0;
            background-color: #333;
            border-radius: 5px;
        }

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
        <nav class="navbar">
            <div class="logo">
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

    <div class="container">
        <!-- Slideshow Section -->
        <div class="section slideshow" id="slideshow">
            <img src="all2.jpg" alt="Slide 1" class="active">
            <img src="bible.jpg" alt="Slide 2">
            <img src="chieftainess2.jpg" alt="Slide 3">
        </div>

        <!-- Gallery Section -->
        <div class="section">
            <h2>Gallery</h2>
            <div class="gallery-section">
                <h3>Youth Ministry</h3>
                <div class="gallery">
                    <img src="everyone.jpg" alt="Gallery Image 1" data-index="0">
                    <img src="director.jpg" alt="Gallery Image 2" data-index="1">
                    <img src="everyone.jpg" alt="Gallery Image 3" data-index="2">
                    <div class="view-more">
                        <img src="book.jpg" alt="Gallery Image 4" data-index="3">
                        <img src="director2.jpg" alt="Gallery Image 5" data-index="4">
                    </div>
                </div>
                <button class="view-more-btn" id="view-more-btn">View More</button>
            </div>
        </div>

        <!-- Meetings Section -->
        <div class="section">
            <h2>Upcoming Meetings</h2>
            <div class="meetings">
                <div class="meeting-card">
                    <h3>Meeting 1</h3>
                    <p>Date: 2025-02-10</p>
                    <p>Location: Church Hall</p>
                </div>
                <div class="meeting-card">
                    <h3>Meeting 2</h3>
                    <p>Date: 2025-02-15</p>
                    <p>Location: Church Hall</p>
                </div>
                <div class="meeting-card">
                    <h3>Meeting 3</h3>
                    <p>Date: 2025-02-20</p>
                    <p>Location: Church Hall</p>
                </div>
            </div>
        </div>

        <!-- Video Section -->
        <div class="section video-section">
            <h2>Videos</h2>
            <iframe src="https://www.youtube.com/embed/tgbNymZ7vqY" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" frameborder="0"></iframe>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Remnant Women of Faith Ministry. All rights reserved.</p>
    </footer>

    <!-- Modal -->
    <div class="modal" id="modal">
        <img src="" alt="Modal Image" id="modal-img">
        <span class="prev" id="prev">&lt;</span>
        <span class="next" id="next">&gt;</span>
    </div>

    <script>
        // Slideshow functionality
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slideshow img');
        
        function showSlides() {
            slides.forEach((slide, index) => {
                slide.classList.remove('active');
                if (index === slideIndex) {
                    slide.classList.add('active');
                }
            });
            slideIndex = (slideIndex + 1) % slides.length;
        }
        
        setInterval(showSlides, 3000);
        showSlides();

        // Gallery View More functionality
        const viewMoreBtn = document.getElementById('view-more-btn');
        const viewMoreSection = document.querySelector('.view-more');

        viewMoreBtn.addEventListener('click', () => {
            viewMoreSection.style.display = 'block';
            viewMoreBtn.style.display = 'none';
        });

        // Modal functionality
        const modal = document.getElementById('modal');
        const modalImg = document.getElementById('modal-img');
        let currentImageIndex = 0;

        document.querySelectorAll('.gallery img').forEach((img, index) => {
            img.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalImg.src = img.src;
                currentImageIndex = index;
            });
        });

        document.getElementById('prev').addEventListener('click', () => {
            currentImageIndex = (currentImageIndex - 1 + document.querySelectorAll('.gallery img').length) % document.querySelectorAll('.gallery img').length;
            modalImg.src = document.querySelectorAll('.gallery img')[currentImageIndex].src;
        });

        document.getElementById('next').addEventListener('click', () => {
            currentImageIndex = (currentImageIndex + 1) % document.querySelectorAll('.gallery img').length;
            modalImg.src = document.querySelectorAll('.gallery img')[currentImageIndex].src;
        });

        // Close modal when clicking outside the image
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Hamburger Menu toggle
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>
