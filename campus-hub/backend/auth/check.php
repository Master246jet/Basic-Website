<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authenticated = !empty($_SESSION['user_id']);

if ($authenticated) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'authenticated' => true,
        'user_id' => $_SESSION['user_id'],
        'user_email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? 'student'
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'authenticated' => false,
        'message' => 'Not authenticated'
    ]);
}
?>
