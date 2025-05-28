<?php
// api/update-status.php - Update todo status
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !is_numeric($data['id']) || !isset($data['status'])) {
    sendResponse(["error" => "Invalid request"], 400);
}

$id = (int)$data['id'];
$status = $data['status'] === 'completed' ? 'completed' : 'pending';

try {
    $stmt = $pdo->prepare("UPDATE todos SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    if ($stmt->rowCount() === 0) {
        sendResponse(["error" => "Todo not found"], 404);
    }
    
    sendResponse(["message" => "Status updated", "id" => $id, "status" => $status]);
} catch (PDOException $e) {
    sendResponse(["error" => "Failed to update status"], 500);
}
?>