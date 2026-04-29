
<?php
/* Данный скрипт содержит логику добавления товара в корзину (SQL-запросы) */
require '../config.php';
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'];
$quantity = $data['quantity'] ?? 1;
$user = getCurrentUser($pdo);
if($user){
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
    $stmt->execute([$user['id'], $product_id, $quantity, $quantity]);
} else {
    $stmt = $pdo->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?,?,?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
    $stmt->execute([$session_id, $product_id, $quantity, $quantity]);
}
echo json_encode(['success'=>true]);
?>