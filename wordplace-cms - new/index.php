<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['user_role'] === 'admin';

// Get the current page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Get posts for homepage
$posts = [];
if ($page === 'home') {
    $posts = getPosts(5);
}

// Get single post
$singlePost = null;
if ($page === 'post' && isset($_GET['id'])) {
    $singlePost = getPostById($_GET['id']);
    if (!$singlePost) {
        $page = '404';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WordPlace CMS</title>
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
                            <?php if ($isLoggedIn): ?>
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            <?php else: ?>
                                <li><a href="login.php">Login</a></li>
                                <li><a href="register.php">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <?php
                // Include the appropriate page content
                switch ($page) {
                    case 'home':
                        include 'templates/home.php';
                        break;
                    case 'post':
                        include 'templates/single-post.php';
                        break;
                    case '404':
                        include 'templates/404.php';
                        break;
                    default:
                        include 'templates/home.php';
                }
                ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> WordPlace CMS. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>

