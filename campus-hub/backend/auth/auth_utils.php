<?php
/**
 * Authentication Utility Functions
 * Provides reusable functions for session management and route protection
 */

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is authenticated
 * @return bool
 */
function isAuthenticated() {
    return !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 * @return int|null
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 * @return string
 */
function getUserRole() {
    return $_SESSION['role'] ?? 'student';
}

/**
 * Require authentication, send JSON error and exit if not authenticated
 * @param int $statusCode HTTP status code (default 401)
 * @param string $message Error message
 */
function requireAuth($statusCode = 401, $message = 'Authentication required') {
    if (!isAuthenticated()) {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }
}

/**
 * Check if user has specific role(s)
 * @param string|array $roles Required role(s)
 * @return bool
 */
function hasRole($roles) {
    if (!isAuthenticated()) {
        return false;
    }
    $userRole = getUserRole();
    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }
    return $userRole === $roles;
}

/**
 * Require specific role, send JSON error and exit if permission denied
 * @param string|array $roles Required role(s)
 * @param string $message Error message
 */
function requireRole($roles, $message = 'Insufficient permissions') {
    if (!hasRole($roles)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }
}

/**
 * Logout user: destroy session and clear cookies
 */
function logout() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
?>
