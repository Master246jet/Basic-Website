<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Method not allowed']);
	exit;
}

$input = []; 
if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) { // Handle JSON input
	$raw = file_get_contents('php://input'); 
	$input = json_decode($raw, true) ?? [];
} else {
	$input = $_POST;
}

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$role = trim($input['role'] ?? 'student');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => 'Invalid email']);
	exit;
}

if (strlen($password) < 8) { // Basic password length check
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
	exit;
} 

try {
	$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
	$stmt->execute(['email' => $email]);
	if ($stmt->fetch()) {
		http_response_code(409);
		echo json_encode(['success' => false, 'message' => 'Email already registered']);
		exit;
	}

	$passwordHash = password_hash($password, PASSWORD_DEFAULT);

	$insert = $pdo->prepare('INSERT INTO users (email, password_hash, role, created_at) VALUES (:email, :password_hash, :role, NOW())');
	$insert->execute([
		'email' => $email,
		'password_hash' => $passwordHash,
		'role' => $role
	]);

	http_response_code(201);
	echo json_encode(['success' => true, 'message' => 'User registered']);
} catch (PDOException $e) {
	error_log('Register error: ' . $e->getMessage());
	http_response_code(500);
	echo json_encode(['success' => false, 'message' => 'Server error']);
}