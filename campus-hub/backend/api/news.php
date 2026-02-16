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

		// List all news (basic)
		$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
		$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
		$stmt = $pdo->prepare('SELECT n.id, n.title, n.content, n.author_id, n.created_at, u.email AS author_email FROM news n JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC LIMIT :limit OFFSET :offset');
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		respond(['success' => true, 'data' => $rows]);
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

		if ($title === '' || $content === '') {
			respond(['success' => false, 'message' => 'Title and content required'], 400);
		}

		$insert = $pdo->prepare('INSERT INTO news (title, content, author_id, created_at) VALUES (:title, :content, :author_id, NOW())');
		$insert->execute(['title' => $title, 'content' => $content, 'author_id' => $userId]);
		$newId = (int)$pdo->lastInsertId();
		respond(['success' => true, 'message' => 'News created', 'id' => $newId], 201);
	}

	if ($method === 'PUT' || $method === 'PATCH') {
		// Update news: require id in query
		parse_str(file_get_contents('php://input'), $putVars);
		// Accept id from query or body
		$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($putVars['id'] ?? 0);
		if (!$id) respond(['success' => false, 'message' => 'ID required'], 400);

		// Fetch existing
		$stmt = $pdo->prepare('SELECT id, author_id FROM news WHERE id = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) respond(['success' => false, 'message' => 'Not found'], 404);

		if ($userRole !== 'admin' && (int)$row['author_id'] !== $userId) {
			respond(['success' => false, 'message' => 'Forbidden'], 403);
		}

		// Get updated fields
		$title = $putVars['title'] ?? null;
		$content = $putVars['content'] ?? null;
		if ($title === null && $content === null) respond(['success' => false, 'message' => 'Nothing to update'], 400);

		$fields = [];
		$params = ['id' => $id];
		if ($title !== null) { $fields[] = 'title = :title'; $params['title'] = $title; }
		if ($content !== null) { $fields[] = 'content = :content'; $params['content'] = $content; }

		$sql = 'UPDATE news SET ' . implode(', ', $fields) . ' WHERE id = :id';
		$upd = $pdo->prepare($sql);
		$upd->execute($params);
		respond(['success' => true, 'message' => 'Updated']);
	}

	if ($method === 'DELETE') {
		$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
		if (!$id) respond(['success' => false, 'message' => 'ID required'], 400);

		$stmt = $pdo->prepare('SELECT id, author_id FROM news WHERE id = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) respond(['success' => false, 'message' => 'Not found'], 404);

		if ($userRole !== 'admin' && (int)$row['author_id'] !== $userId) {
			respond(['success' => false, 'message' => 'Forbidden'], 403);
		}

		$del = $pdo->prepare('DELETE FROM news WHERE id = :id');
		$del->execute(['id' => $id]);
		respond(['success' => true, 'message' => 'Deleted']);
	}

	// Method not allowed
	respond(['success' => false, 'message' => 'Method not allowed'], 405);

} catch (PDOException $e) {
	error_log('News API error: ' . $e->getMessage());
	respond(['success' => false, 'message' => 'Server error'], 500);
}

