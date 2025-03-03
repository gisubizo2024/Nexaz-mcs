<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$user = getUserById($userId);
$error = '';
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = $_POST['email'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    // Validate current password
    if (!password_verify($currentPassword, $user['password'])) {
        $error = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } else {
        $updateData = ['email' => $newEmail];
        
        // Update password if a new one is provided
        if (!empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        
        if (updateUser($userId, $updateData)) {
            $success = "Profile updated successfully.";
            $user = getUserById($userId); // Refresh user data
        } else {
            $error = "Failed to update profile.";
        }
    }
}

$page_title = 'User Profile - WordPlace CMS';
define('ABSPATH', __DIR__);
include 'templates/header.php';
?>
    <div class="site-container">
        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <div class="dashboard">
                    <div class="dashboard-header">
                        <h1>User Profile</h1>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>
                    
                    <div class="dashboard-content">
                        <div class="dashboard-main">
                            <form action="profile.php" method="post" class="profile-form">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="new_password">New Password (leave blank to keep current)</label>
                                    <input type="password" id="new_password" name="new_password">
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password">
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
<?php include 'templates/footer.php'; ?>

