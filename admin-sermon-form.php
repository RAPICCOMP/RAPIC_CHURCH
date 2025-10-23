<?php
session_start();
// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { background: #f5f5f5; font-family: Arial, sans-serif; }
        .admin-login-container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            padding: 30px;
        }
        h2 { text-align: center; margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type=text], input[type=password] {
            width: 100%; padding: 10px; margin-bottom: 18px; border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            width: 100%; padding: 12px; background: #23295e; color: #fff; border: none; border-radius: 5px; font-size: 16px;
            cursor: pointer; font-weight: bold;
        }
        button:hover { background: #1a1f47; }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <h2>Admin Login</h2>
        <form method="post" action="admin_login.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>