<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Helper: send JSON and exit
function respond($data, $status = 200) {
	http_response_code($status);
	echo json_encode($data);
	exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
	if ($method === 'GET') {
		// If id provided, return single item
		if (!empty($_GET['id'])) {
			$id = (int)$_GET['id'];
			$stmt = $pdo->prepare('SELECT n.id, n.title, n.content, n.author_id, n.created_at, u.email AS author_email FROM news n JOIN users u ON n.author_id = u.id WHERE n.id = :id');
			$stmt->execute(['id' => $id]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$row) respond(['success' => false, 'message' => 'Not found'], 404);
			respond(['success' => true, 'data' => $row]);
		}

		// List all news with pagination
		$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
		$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
		
		// Get total count for pagination info
		$countStmt = $pdo->prepare('SELECT COUNT(*) as total FROM news');
		$countStmt->execute();
		$total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];
		
		$stmt = $pdo->prepare('SELECT n.id, n.title, n.content, n.author_id, n.created_at, u.email AS author_email FROM news n JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC LIMIT :limit OFFSET :offset');
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		respond(['success' => true, 'data' => $rows, 'total' => $total, 'limit' => $limit, 'offset' => $offset]);
	}

	// Require authentication for write operations
	if (empty($_SESSION['user_id'])) {
		respond(['success' => false, 'message' => 'Authentication required'], 401);
	}

	$userId = (int)$_SESSION['user_id'];
	$userRole = $_SESSION['role'] ?? 'student';

	if ($method === 'POST') {
		// Create news
		$input = [];
		if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
			$raw = file_get_contents('php://input');
			$input = json_decode($raw, true) ?? [];
		} else {
			$input = $_POST;
		}

		$title = trim($input['title'] ?? '');
		$content = trim($input['content'] ?? '');

		// Validation
		if ($title === '' || $content === '') {
			respond(['success' => false, 'message' => 'Title and content are required'], 400);
		}
		
		if (strlen($title) < 3) {
			respond(['success' => false, 'message' => 'Title must be at least 3 characters'], 400);
		}
		
		if (strlen($title) > 200) {
			respond(['success' => false, 'message' => 'Title cannot exceed 200 characters'], 400);
		}
		
		if (strlen($content) < 5) {
			respond(['success' => false, 'message' => 'Content must be at least 5 characters'], 400);
		}
		
		if (strlen($content) > 5000) {
			respond(['success' => false, 'message' => 'Content cannot exceed 5000 characters'], 400);
		}

		$insert = $pdo->prepare('INSERT INTO news (title, content, author_id, created_at) VALUES (:title, :content, :author_id, NOW())');
		$insert->execute(['title' => $title, 'content' => $content, 'author_id' => $userId]);
		$newId = (int)$pdo->lastInsertId();
		respond(['success' => true, 'message' => 'News created successfully', 'id' => $newId], 201);
	}

	if ($method === 'PUT' || $method === 'PATCH') {
		// Update news: require id in query
		$raw = file_get_contents('php://input');
		// support JSON bodies as well as form-encoded bodies
		if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
			$putVars = json_decode($raw, true) ?? [];
		} else {
			parse_str($raw, $putVars);
		}
		// Accept id from query or body
		$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($putVars['id'] ?? 0);
		if (!$id) respond(['success' => false, 'message' => 'News ID is required'], 400);

		// Fetch existing
		$stmt = $pdo->prepare('SELECT id, author_id FROM news WHERE id = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) respond(['success' => false, 'message' => 'News not found'], 404);

		// Check authorization
		if ($userRole !== 'admin' && (int)$row['author_id'] !== $userId) {
			respond(['success' => false, 'message' => 'You can only edit your own news'], 403);
		}

		// Get updated fields
		$title = isset($putVars['title']) ? trim($putVars['title']) : null;
		$content = isset($putVars['content']) ? trim($putVars['content']) : null;
		if ($title === null && $content === null) respond(['success' => false, 'message' => 'Nothing to update'], 400);

		// Validate fields if provided
		if ($title !== null && $title !== '') {
			if (strlen($title) < 3) respond(['success' => false, 'message' => 'Title must be at least 3 characters'], 400);
			if (strlen($title) > 200) respond(['success' => false, 'message' => 'Title cannot exceed 200 characters'], 400);
		}
		
		if ($content !== null && $content !== '') {
			if (strlen($content) < 5) respond(['success' => false, 'message' => 'Content must be at least 5 characters'], 400);
			if (strlen($content) > 5000) respond(['success' => false, 'message' => 'Content cannot exceed 5000 characters'], 400);
		}

		$fields = [];
		$params = ['id' => $id];
		if ($title !== null) { $fields[] = 'title = :title'; $params['title'] = $title; }
		if ($content !== null) { $fields[] = 'content = :content'; $params['content'] = $content; }

		$sql = 'UPDATE news SET ' . implode(', ', $fields) . ' WHERE id = :id';
		$upd = $pdo->prepare($sql);
		$upd->execute($params);
		respond(['success' => true, 'message' => 'News updated successfully']);
	}

	if ($method === 'DELETE') {
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		if (!$id) respond(['success' => false, 'message' => 'News ID is required'], 400);

		$stmt = $pdo->prepare('SELECT id, author_id FROM news WHERE id = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) respond(['success' => false, 'message' => 'News not found'], 404);

		// Check authorization
		if ($userRole !== 'admin' && (int)$row['author_id'] !== $userId) {
			respond(['success' => false, 'message' => 'You can only delete your own news'], 403);
		}

		$del = $pdo->prepare('DELETE FROM news WHERE id = :id');
		$del->execute(['id' => $id]);
		respond(['success' => true, 'message' => 'News deleted successfully']);
	}

	// Method not allowed
	respond(['success' => false, 'message' => 'Method not allowed'], 405);

} catch (PDOException $e) {
	error_log('News API error: ' . $e->getMessage());
	error_log('SQL State: ' . $e->getCode());
	respond(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
} catch (Exception $e) {
	error_log('News API generic error: ' . $e->getMessage());
	respond(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}

