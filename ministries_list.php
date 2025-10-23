<?php
include('db_connect.php');
$query = "SELECT * FROM ministries";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAPRCI Ministries</title>
    <style>
        body { background-color: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-top: 70px; }
        .content-section { background-color: #fff; padding: 20px; margin-top: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 100px; }
        .section-title { font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333; }
        .cards-section { display: flex; flex-wrap: wrap; gap: 18px; justify-content: center; margin: 20px 0; padding: 20px 0; background-color: #f9f9f9; }
        .card { width: 320px; text-align: center; background: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.2s ease; margin-bottom: 18px; }
        .card:hover { transform: scale(1.05); }
        .card img { width: 100%; height: 180px; object-fit: cover; }
        .card h3 { font-size: 1.5rem; margin: 10px 0; }
        .card p { font-size: 1rem; color: #555; margin: 10px 0; }
        .card a { display: inline-block; margin: 10px 0 20px; padding: 10px 20px; background-color: #333; color: #fff; text-decoration: none; border-radius: 5px; font-size: 1rem; }
        .card a:hover { background-color: #555; }
        @media (max-width: 768px) { .cards-section { flex-direction: column; align-items: center; gap: 12px; padding: 10px 0; } .card { width: 90vw; min-width: 180px; max-width: 98vw; margin-bottom: 12px; } .card img { height: 120px; } }
        @media (max-width: 480px) { .card { width: 98vw; min-width: 120px; max-width: 99vw; margin-bottom: 8px; padding: 6px; } .card img { height: 100px; } .card h3 { font-size: 1rem; } .card a { font-size: 0.9rem; padding: 8px 12px; } }
    </style>
</head>
<body>
    <div class="content-section">
        <div class="section-title">RAPRCI MINISTRIES</div>
        <div class="cards-section">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <a href="ministry_page.php?id=<?= $row['id'] ?>">Learn More</a>
                    <button class="quick-post-btn" data-ministry-id="<?= $row['id'] ?>" data-ministry-name="<?= htmlspecialchars($row['name']) ?>">Quick Post</button>
                </div>
            <?php endwhile; ?>
    /* Modal styles for Quick Post */
    .quick-post-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.7);
        justify-content: center;
        align-items: center;
    }
    .quick-post-modal.active {
        display: flex;
    }
    .quick-post-content {
        background: #fff;
        padding: 30px 20px;
        border-radius: 10px;
        max-width: 400px;
        width: 95vw;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        position: relative;
    }
    .quick-post-content h2 {
        margin-top: 0;
        font-size: 1.3rem;
        color: #2a2a72;
        text-align: center;
    }
    .quick-post-content label {
        font-weight: 600;
        margin-top: 10px;
        display: block;
    }
    .quick-post-content input, .quick-post-content textarea, .quick-post-content select {
        width: 100%;
        margin-bottom: 10px;
        padding: 7px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 1rem;
    }
    .quick-post-content button[type="submit"] {
        background: #2a2a72;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 18px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 8px;
    }
    .quick-post-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 1.5rem;
        color: #333;
        background: none;
        border: none;
        cursor: pointer;
    }
        </div>
    </div>
</body>
    <!-- Quick Post Modal -->
    <div class="quick-post-modal" id="quickPostModal">
        <div class="quick-post-content">
            <button class="quick-post-close" id="quickPostClose">&times;</button>
            <h2 id="quickPostTitle">Quick Post to Ministry</h2>
            <form id="quickPostForm" method="post" enctype="multipart/form-data" target="_blank">
                <input type="hidden" name="ministry_id" id="quickPostMinistryId">
                <label for="quickPostType">Type:</label>
                <select name="post_type" id="quickPostType" required>
                    <option value="gallery">Gallery Image</option>
                    <option value="video">Video</option>
                    <option value="meeting">Meeting</option>
                    <option value="desc">Description/Content</option>
                </select>
                <div id="quickPostFields">
                    <!-- Dynamic fields will be inserted here -->
                </div>
                <button type="submit">Post</button>
            </form>
        </div>
    </div>
    <script>
    // Quick Post Modal logic
    const quickPostBtns = document.querySelectorAll('.quick-post-btn');
    const quickPostModal = document.getElementById('quickPostModal');
    const quickPostClose = document.getElementById('quickPostClose');
    const quickPostForm = document.getElementById('quickPostForm');
    const quickPostTitle = document.getElementById('quickPostTitle');
    const quickPostMinistryId = document.getElementById('quickPostMinistryId');
    const quickPostFields = document.getElementById('quickPostFields');
    const quickPostType = document.getElementById('quickPostType');
    let currentMinistryId = null;
    let currentMinistryName = '';
    quickPostBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentMinistryId = btn.getAttribute('data-ministry-id');
            currentMinistryName = btn.getAttribute('data-ministry-name');
            quickPostModal.classList.add('active');
            quickPostTitle.textContent = 'Quick Post to ' + currentMinistryName;
            quickPostMinistryId.value = currentMinistryId;
            quickPostForm.action = 'ministry_page.php?id=' + currentMinistryId;
            updateQuickPostFields();
        });
    });
    quickPostClose.addEventListener('click', function() {
        quickPostModal.classList.remove('active');
        quickPostForm.reset();
        quickPostFields.innerHTML = '';
    });
    quickPostModal.addEventListener('click', function(e) {
        if (e.target === quickPostModal) {
            quickPostModal.classList.remove('active');
            quickPostForm.reset();
            quickPostFields.innerHTML = '';
        }
    });
    quickPostType.addEventListener('change', updateQuickPostFields);
    function updateQuickPostFields() {
        const type = quickPostType.value;
        let html = '';
        if (type === 'gallery') {
            html += '<label for="quickGalleryImage">Gallery Image:</label>';
            html += '<input type="file" name="gallery_image" id="quickGalleryImage" accept="image/*" required>';
        } else if (type === 'video') {
            html += '<label for="quickVideoTitle">Video Title:</label>';
            html += '<input type="text" name="video_title" id="quickVideoTitle" required>';
            html += '<label for="quickVideoUrl">YouTube Link or MP4 URL:</label>';
            html += '<input type="text" name="video_url" id="quickVideoUrl" required>';
            html += '<label for="quickVideoDesc">Description (optional):</label>';
            html += '<textarea name="video_desc" id="quickVideoDesc" rows="2"></textarea>';
        } else if (type === 'meeting') {
            html += '<label for="quickMeetingTitle">Meeting Title:</label>';
            html += '<input type="text" name="meeting_title" id="quickMeetingTitle" required>';
            html += '<label for="quickMeetingDesc">Meeting Description:</label>';
            html += '<textarea name="meeting_desc" id="quickMeetingDesc" rows="2" required></textarea>';
            html += '<label for="quickMeetingDate">Meeting Date:</label>';
            html += '<input type="date" name="meeting_date" id="quickMeetingDate" required>';
        } else if (type === 'desc') {
            html += '<label for="quickDesc">Description:</label>';
            html += '<textarea name="description" id="quickDesc" rows="4"></textarea>';
            html += '<label for="quickContent">Full Content:</label>';
            html += '<textarea name="content" id="quickContent" rows="6"></textarea>';
        }
        quickPostFields.innerHTML = html;
    }
    </script>
</body>
</html>
