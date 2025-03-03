<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        // Attempt to login
        $user = loginUser($username, $password);
        
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WordPlace CMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="site-container">
        <!-- Header -->
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="index.php">WordPlace</a>
                    </div>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="login.php" class="active">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <div class="auth-form">
                    <h1>Login to WordPlace</h1>
                    
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> WordPlace CMS. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>

