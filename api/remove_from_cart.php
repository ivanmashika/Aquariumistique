<?php
require '../config.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? 0;

if (!$product_id) {
    echo json_encode(['success' => false, 'error' => 'ID товара не указан']);
    exit;
}

$user = getCurrentUser($pdo);
$session_id = $_SESSION['session_id'] ?? session_id();

try {
    if ($user) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $result = $stmt->execute([$user['id'], $product_id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
        $result = $stmt->execute([$session_id, $product_id]);
    }
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Не удалось удалить товар']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>