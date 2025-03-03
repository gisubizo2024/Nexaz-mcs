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
$success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        // Check if username or email already exists
        if (isUsernameTaken($username)) {
            $error = 'Username already taken';
        } elseif (isEmailTaken($email)) {
            $error = 'Email already registered';
        } else {
            // Register the user
            $result = registerUser($username, $email, $password);
            
            if ($result) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - WordPlace CMS</title>
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
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php" class="active">Register</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <div class="auth-form">
                    <h1>Create an Account</h1>
                    
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="success-message"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form action="register.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                        
                        <p>Already have an account? <a href="login.php">Login here</a></p>
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

