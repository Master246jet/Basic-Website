<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

// Accept POST data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   http_response_code(405);
   echo json_encode(['success' => false, 'message' => 'Method not allowed']);
   exit;
}

// Validate Input
$input = [];
if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
   $raw = file_get_contents('php://input');
   $input = json_decode($raw, true) ?? [];
} else {
   $input = $_POST;
}

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Invalid email']);
   exit;
}

if (empty($password)) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => 'Password is required']);
   exit;
}

// Verify Password and create session
try {
   $stmt = $pdo->prepare('SELECT id, password_hash, role FROM users WHERE email = :email');
   $stmt->execute(['email' => $email]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);

   // Invalid login - don't reveal if email exists or password is wrong (security best practice)
   if (!$user) {
      http_response_code(401);
      echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
      exit;
   }

   if (!password_verify($password, $user['password_hash'])) {
      http_response_code(401);
      echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
      exit;
   }

   // Start session-based authentication
   if (session_status() === PHP_SESSION_NONE) {
      session_start();
   }
   session_regenerate_id(true);
   $_SESSION['user_id'] = (int)$user['id'];
   $_SESSION['role'] = $user['role'];

   http_response_code(200);
   echo json_encode(['success' => true, 'message' => 'Login successful']);
} catch (PDOException $e) {
   error_log('Login error: ' . $e->getMessage());
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Server error']);
}