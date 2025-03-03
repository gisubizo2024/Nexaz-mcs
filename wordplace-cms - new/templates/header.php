<?php
// Ensure this file is included, not accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get the current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'WordPlace CMS'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="site-container">
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="index.php">WordPlace</a>
                    </div>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php" <?php echo $current_page === 'index' ? 'class="active"' : ''; ?>>Home</a></li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li><a href="dashboard.php" <?php echo $current_page === 'dashboard' ? 'class="active"' : ''; ?>>Dashboard</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            <?php else: ?>
                                <li><a href="login.php" <?php echo $current_page === 'login' ? 'class="active"' : ''; ?>>Login</a></li>
                                <li><a href="register.php" <?php echo $current_page === 'register' ? 'class="active"' : ''; ?>>Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <main class="site-content">
            <div class="container">

