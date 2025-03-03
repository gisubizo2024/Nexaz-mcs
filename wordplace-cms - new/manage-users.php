<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$users = getAllUsers();
$error = '';
$success = '';

// Handle user role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'];
    
    if (updateUserRole($userId, $newRole)) {
        $success = "User role updated successfully.";
    } else {
        $error = "Failed to update user role.";
    }
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    
    if (deleteUser($userId)) {
        $success = "User deleted successfully.";
        $users = getAllUsers(); // Refresh the user list
    } else {
        $error = "Failed to delete user.";
    }
}

$page_title = 'Manage Users - WordPlace CMS';
define('ABSPATH', __DIR__);
include 'templates/header.php';
?>

    <div class="site-container">
        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <div class="dashboard">
                    <div class="dashboard-header">
                        <h1>Manage Users</h1>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>
                    
                    <div class="dashboard-content">
                        <div class="dashboard-main">
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <form action="manage-users.php" method="post" class="inline-form">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <select name="new_role" onchange="this.form.submit()">
                                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                        <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                                                        <option value="author" <?php echo $user['role'] === 'author' ? 'selected' : ''; ?>>Author</option>
                                                    </select>
                                                    <input type="hidden" name="update_role" value="1">
                                                </form>
                                            </td>
                                            <td>
                                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                                    <form action="manage-users.php" method="post" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" name="delete_user" class="btn btn-sm btn-delete">Delete</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

<?php include 'templates/footer.php'; ?>

