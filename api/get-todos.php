<?php
// api/get-todos.php - GET all todos
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require 'db.php';

$status = isset($_GET['status']) ? $_GET['status'] : 'all';

try {
    $sql = "SELECT id, task, status FROM todos";
    
    if ($status === 'pending') {
        $sql .= " WHERE status = 'pending'";
    } else if ($status === 'completed') {
        $sql .= " WHERE status = 'completed'";
    }
    
    $sql .= " ORDER BY id DESC";
    
    $stmt = $pdo->query($sql);
    $todos = $stmt->fetchAll();
    sendResponse($todos);
} catch (PDOException $e) {
    sendResponse(["error" => "Failed to fetch todos"], 500);
}
