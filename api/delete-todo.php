<?php
// api/delete-todo.php - Delete a todo
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    sendResponse(["error" => "Todo ID required"], 400);
}

try {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() === 0) {
        sendResponse(["error" => "Todo not found"], 404);
    }
    
    sendResponse(["message" => "Todo deleted", "id" => $id]);
} catch (PDOException $e) {
    sendResponse(["error" => "Failed to delete todo"], 500);
}
?>