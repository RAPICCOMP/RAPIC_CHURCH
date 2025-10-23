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
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; frame-src https://www.youtube.com;");
// Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
include('db_connect.php');

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid CSRF token.';
    }
    // Validate and sanitize input
    $title = trim($_POST['title'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $preacher = trim($_POST['preacher'] ?? '');
    $link = trim($_POST['youtube'] ?? '');
    $image = '';
    if (empty($title) || empty($date) || empty($preacher)) {
        $errors[] = 'All fields except YouTube link and image are required.';
    }
    // Validate date
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = 'Date must be in YYYY-MM-DD format.';
    }
    // Validate YouTube link (optional)
    if ($link && !preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//', $link)) {
        $errors[] = 'YouTube link must be a valid YouTube URL.';
    }
    // Handle image upload (optional)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Image must be JPG, JPEG, PNG, or GIF.';
        } else {
            $imageName = uniqid('sermon_', true) . '.' . $ext;
            $imagePath = 'uploads/' . $imageName;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $image = $imagePath;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        }
    }
    // If no errors, insert into DB
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO church_sermons (image, title, date, preacher, link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $image, $title, $date, $preacher, $link);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sermon (Admin)</title>
    <style>
        body { background: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); padding: 30px; }
        h2 { text-align: center; margin-bottom: 20px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        label { font-weight: 600; margin-bottom: 5px; }
        input, textarea { padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; }
        input[type="date"] { width: 100%; }
        input[type="file"] { border: none; }
        button { background: #23295e; color: #fff; border: none; border-radius: 6px; padding: 12px; font-size: 1rem; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #1a1f4a; }
        .error { color: #c00; margin-bottom: 10px; }
        .success { color: #080; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Sermon</h2>
        <?php if (!empty($errors)) { echo '<div class="error">' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>'; } ?>
        <?php if ($success) { echo '<div class="success">Sermon posted successfully!</div>'; } ?>
        <form method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <label for="title">Sermon Title *</label>
            <input type="text" name="title" id="title" required maxlength="255">
            <label for="date">Date *</label>
            <input type="date" name="date" id="date" required>
            <label for="preacher">Preacher *</label>
            <input type="text" name="preacher" id="preacher" required maxlength="255">
            <label for="link">YouTube Link (optional)</label>
            <input type="url" name="youtube" id="link" placeholder="https://www.youtube.com/watch?v=...">
            <label for="image">Sermon Image (optional)</label>
            <input type="file" name="image" id="image" accept="image/*">
            <button type="submit">Post Sermon</button>
        </form>
    </div>
</body>
</html>
<?php if (isset($conn)) $conn->close(); ?>