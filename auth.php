<?php
// auth.php
session_start();

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if user is an admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Protect admin pages only
function require_admin() {
    if (!is_admin()) {
        http_response_code(403);
        die("403 - Access denied. This page is for admins only.");
    }
}
?>
