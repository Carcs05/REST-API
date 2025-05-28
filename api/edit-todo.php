<?php
// api/edit-todo.php - Edit a todo
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !is_numeric($data['id']) || !isset($data['task']) || empty(trim($data['task']))) {
    sendResponse(["error" => "Invalid request"], 400);
}

$id = (int)$data['id'];
$task = sanitizeInput($data['task']);

try {
    $stmt = $pdo->prepare("UPDATE todos SET task = ? WHERE id = ?");
    $stmt->execute([$task, $id]);
    
    if ($stmt->rowCount() === 0) {
        sendResponse(["error" => "Todo not found"], 404);
    }
    
    sendResponse(["message" => "Todo updated", "id" => $id, "task" => $task]);
} catch (PDOException $e) {
    sendResponse(["error" => "Failed to update todo"], 500);
}
?>