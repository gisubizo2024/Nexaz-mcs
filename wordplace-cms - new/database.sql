-- WordPlace CMS Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS wordplace;
USE wordplace;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'author') NOT NULL DEFAULT 'author',
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT NULL
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Media table
CREATE TABLE IF NOT EXISTS media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    filetype VARCHAR(50) NOT NULL,
    filesize INT NOT NULL,
    uploaded_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL
);

-- Insert default categories
INSERT INTO categories (name) VALUES
('Uncategorized'),
('Technology'),
('Business'),
('Health'),
('Lifestyle');

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO users (username, email, password, role, created_at) VALUES
('admin', 'admin@example.com', '$2y$10$8tGmGzgvGmQQLgRwxJjYxuYVuN3xDqK.Tg8jcQQZJi7LrOQiOCFfO', 'admin', NOW());

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_title', 'WordPlace CMS'),
('site_description', 'A simple and powerful content management system'),
('posts_per_page', '10'),
('allow_comments', 'true'),
('theme', 'default');

