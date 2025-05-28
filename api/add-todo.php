<?php
// api/add-todo.php - Add a new todo
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['task']) || empty(trim($data['task']))) {
    sendResponse(["error" => "Task is required"], 400);
}

$task = sanitizeInput($data['task']);

try {
    $stmt = $pdo->prepare("INSERT INTO todos (task, status) VALUES (?, 'pending')");
    $stmt->execute([$task]);
    $todoId = $pdo->lastInsertId();
    
    $stmt = $pdo->prepare("SELECT id, task, status FROM todos WHERE id = ?");
    $stmt->execute([$todoId]);
    $newTodo = $stmt->fetch();
    
    sendResponse(["message" => "Todo added", "todo" => $newTodo]);
} catch (PDOException $e) {
    sendResponse(["error" => "Failed to add todo"], 500);
}
?>