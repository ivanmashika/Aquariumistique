
<?php
/* Данный скрипт содержит логику подсчета суммы заказа, его оформления и заполнения промежуточных таблиц. */ 
require '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Метод не разрешён']);
    exit;
}

$user = getCurrentUser($pdo);
$session_id = $_SESSION['session_id'];

if ($user) {
    $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price, p.sale_price, p.is_sale, p.name
                           FROM cart c
                           JOIN products p ON c.product_id = p.id
                           WHERE c.user_id = ?");
    $stmt->execute([$user['id']]);
} else {
    $stmt = $pdo->prepare("SELECT c.product_id, c.quantity, p.price, p.sale_price, p.is_sale, p.name
                           FROM cart c
                           JOIN products p ON c.product_id = p.id
                           WHERE c.session_id = ?");
    $stmt->execute([$session_id]);
}

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
    echo json_encode(['success' => false, 'error' => 'Корзина пуста']);
    exit;
}

$total = 0;
foreach ($items as $item) {
    $price = $item['is_sale'] && $item['sale_price'] ? $item['sale_price'] : $item['price'];
    $total += $price * $item['quantity'];
}

try {
    $pdo->beginTransaction();

    if ($user) {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, session_id, total, status, created_at) VALUES (?, ?, ?, 'new', NOW())");
        $stmt->execute([$user['id'], $session_id, $total]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, session_id, total, status, created_at) VALUES (NULL, ?, ?, 'new', NOW())");
        $stmt->execute([$session_id, $total]);
    }
    $orderId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $price = $item['is_sale'] && $item['sale_price'] ? $item['sale_price'] : $item['price'];
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $price]);
    }

    if ($user) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user['id']]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ?");
        $stmt->execute([$session_id]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'order_id' => $orderId, 'total' => $total]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Ошибка при создании заказа: ' . $e->getMessage()]);
}