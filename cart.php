<?php require 'config.php';
if (isset($_GET['remove'])) {
    $product_id = (int) $_GET['remove'];
    $user = getCurrentUser($pdo);
    if ($user) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user['id'], $product_id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->execute([$session_id, $product_id]);
    }
    header('Location: cart.php');
    exit;
}
$user = getCurrentUser($pdo);
$items = [];

if ($user) {
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.sale_price, p.is_sale, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$user['id']]);
    $items = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.sale_price, p.is_sale, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.session_id = ?");
    $stmt->execute([$session_id]);
    $items = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, (int) $_POST['quantity']);

    if ($user) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $user['id'], $product_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $session_id, $product_id]);
    }
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина | AquaStyle</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="top-menu">
        <div class="logo"><a href="index.php"><img src="src/logo.png" alt="Лого"></a></div>
        <nav>
            <ul>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="contacts.php">Контакты</a></li>
                <li><a href="about.php">О нас</a></li>
                <li><a href="profile.php">Профиль</a></li>
            </ul>
        </nav>
        <div class="cart"><a href="cart.php"><img src="src/cart.png" alt="Корзина"></a></div>
    </header>

    <div class="page-wrapper">
        <div class="content">
            <div class="cart-container">

                <?php if (empty($items)): ?>
                    <div class="empty-cart">
                        <h2>Ваша корзина пуста</h2>
                        <p>Перейдите в <a href="catalog.php">каталог</a>, чтобы выбрать товары</p>
                    </div>
                <?php else: ?>
                    <div class="cart-header">
                        <span>Товар</span>
                        <span>Цена</span>
                        <span>Количество</span>
                        <span>Действие</span>
                    </div>

                    <?php
                    $total = 0;
                    foreach ($items as $item):
                        $price = $item['is_sale'] ? $item['sale_price'] : $item['price'];
                        $item_total = $price * $item['quantity'];
                        $total += $item_total;
                        ?>
                        <div class="cart-item" id="cart-item-<?= $item['product_id'] ?>">
                            <div class="product-info-cart">
                                <img src="src/<?= htmlspecialchars($item['image'] ?? 'placeholder.jpg') ?>"
                                    alt="<?= htmlspecialchars($item['name']) ?>">
                                <span><strong><?= htmlspecialchars($item['name']) ?></strong></span>
                            </div>
                            <span><?= $price ?> ₽</span>
                            <form method="POST" class="cart-item-form">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1"
                                    class="quantity-input" onchange="this.form.submit()">
                                <input type="hidden" name="update_quantity" value="1">
                            </form>
                            <a href="cart.php?remove=<?= $item['product_id'] ?>" class="remove-btn"
                                onclick="return confirm('Удалить товар?')">Удалить</a>
                        </div>
                    <?php endforeach; ?>

                    <div class="cart-summary">
                        <strong>Итого: <?= $total ?> ₽</strong><br>
                        <button class="btn btn-checkout" onclick="placeOrder()">Оформить заказ</button>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        // Переносим существующие функции, если они нужны, и добавляем placeOrder
        function removeFromCart(productId) {
            console.log('Удаляем товар с ID:', productId);
            if (!confirm('Удалить товар из корзины?')) return;

            fetch('/Aquariumistique/api/remove_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.getElementById('cart-item-' + productId);
                        if (item) item.remove();
                        if (document.querySelectorAll('.cart-item').length === 0) location.reload();
                        else updateCartCount();
                    } else {
                        alert('Ошибка: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Ошибка fetch:', error);
                    alert('Ошибка соединения: ' + error);
                });
        }

        function placeOrder() {
            if (!confirm('Оформить заказ? Сумма будет списана, а товары зарезервированы.')) return;

            fetch('/Aquariumistique/api/checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Заказ №' + data.order_id + ' успешно оформлен!');
                        location.href = 'cart.php';
                    } else {
                        alert('Ошибка: ' + (data.error || 'неизвестная ошибка'));
                    }
                })
                .catch(err => {
                    console.error('Ошибка:', err);
                    alert('Ошибка соединения');
                });
        }

        function updateCartCount() {
            fetch('api/get_cart.php')
                .then(res => res.json())
                .then(data => {
                    let count = data.reduce((sum, item) => sum + item.quantity, 0);
                    let badge = document.querySelector('.cart-count');
                    if (badge) badge.innerText = count;
                })
                .catch(err => console.error('Ошибка:', err));
        }
    </script>
    <script src="script.js"></script>
</body>

</html>