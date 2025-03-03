<?php
/**
 * Helper functions for the CMS
 */

/**
 * Register a new user
 */
function registerUser($username, $email, $password) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into database
    $userId = insert('users', [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => 'author', // Default role
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    return $userId;
}

/**
 * Check if username is already taken
 */
function isUsernameTaken($username) {
    $user = fetchOne("SELECT id FROM users WHERE username = ?", [$username]);
    return $user !== false;
}

/**
 * Check if email is already registered
 */
function isEmailTaken($email) {
    $user = fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
    return $user !== false;
}

/**
 * Authenticate user
 */
function loginUser($username, $password) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $user = fetchOne($sql, [$username]);
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    
    return false;
}

/**
 * Get posts with pagination
 */
function getPosts($limit = 10, $offset = 0, $categoryId = null) {
    $params = [];
    $sql = "
        SELECT p.*, u.username, c.name as category_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.status = 'published'
    ";
    
    if ($categoryId) {
        $sql .= " AND p.category_id = ?";
        $params[] = $categoryId;
    }
    
    $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    return fetchAll($sql, $params);
}

/**
 * Get post by ID
 */
function getPostById($id) {
    $sql = "
        SELECT p.*, u.username, c.name as category_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?
    ";
    
    return fetchOne($sql, [$id]);
}

/**
 * Get posts by user ID
 */
function getUserPosts($userId) {
    $sql = "
        SELECT p.*, c.name as category_name
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ";
    
    return fetchAll($sql, [$userId]);
}

/**
 * Create a new post
 */
function createPost($data) {
    return insert('posts', array_merge($data, [
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]));
}

/**
 * Update a post
 */
function updatePost($id, $data) {
    $data['updated_at'] = date('Y-m-d H:i:s');
    return update('posts', $data, 'id = ?', [$id]);
}

/**
 * Delete a post
 */
function deletePost($id) {
    return delete('posts', 'id = ?', [$id]);
}

/**
 * Check if user is the owner of a post
 */
function isPostOwner($userId, $postId) {
    $post = fetchOne("SELECT user_id FROM posts WHERE id = ?", [$postId]);
    return $post && $post['user_id'] == $userId;
}

/**
 * Get all categories
 */
function getCategories() {
    return fetchAll("SELECT * FROM categories ORDER BY name");
}

/**
 * Get category by ID
 */
function getCategoryById($id) {
    return fetchOne("SELECT * FROM categories WHERE id = ?", [$id]);
}

/**
 * Create a new category
 */
function createCategory($name) {
    return insert('categories', ['name' => $name]);
}

/**
 * Update a category
 */
function updateCategory($id, $name) {
    return update('categories', ['name' => $name], 'id = ?', [$id]);
}

/**
 * Delete a category
 */
function deleteCategory($id) {
    return delete('categories', 'id = ?', [$id]);
}

/**
 * Get all users
 */
function getAllUsers() {
    return fetchAll("SELECT * FROM users ORDER BY username");
}

/**
 * Get user by ID
 */
function getUserById($id) {
    return fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
}

/**
 * Update user role
 */
function updateUserRole($userId, $newRole) {
    return update('users', ['role' => $newRole], 'id = ?', [$userId]);
}

/**
 * Delete user
 */
function deleteUser($userId) {
    return delete('users', 'id = ?', [$userId]);
}

/**
 * Get all settings
 */
function getSettings() {
    $settings = fetchAll("SELECT * FROM settings");
    $result = [];
    foreach ($settings as $setting) {
        $result[$setting['setting_key']] = $setting['setting_value'];
    }
    return $result;
}

/**
 * Update settings
 */
function updateSettings($newSettings) {
    $success = true;
    foreach ($newSettings as $key => $value) {
        $result = update('settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
        if (!$result) {
            $success = false;
        }
    }
    return $success;
}

/**
 * Update user
 */
function updateUser($userId, $data) {
    return update('users', $data, 'id = ?', [$userId]);
}

