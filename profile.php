<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php';

if (!function_exists('getCurrentUser')) {
    die('Функция getCurrentUser не определена в config.php');
}
$user = getCurrentUser($pdo);

// Обработка входа
if ($_POST && isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userData = $stmt->fetch();
    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        // перенос корзины
        $stmt = $pdo->prepare("UPDATE cart SET user_id = ? WHERE session_id = ?");
        $stmt->execute([$userData['id'], $session_id]);
        header('Location: profile.php');
        exit;
    } else {
        $error = "Неверный email или пароль";
    }
}

// Обработка регистрации
if ($_POST && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if (strlen($pass) < 6) {
        $error = "Пароль должен быть не менее 6 символов";
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
            $stmt->execute([$name, $email, $hashed]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            $error = "Пользователь с таким email уже существует";
        }
    }
}

// Получение заказов пользователя (если есть таблица orders)
$orders = [];
if ($user) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$user['id']]);
    $orders = $stmt->fetchAll();
}
?>
<?php
$pageTitle = 'AquaStyle | Главная';
require 'header.php';
?>

<div class="page-wrapper">
    <div class="profile-container">
        <?php if ($user): ?>
            <!-- АВТОРИЗОВАННЫЙ ПОЛЬЗОВАТЕЛЬ -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar">🐠</div>
                    <div class="profile-info">
                        <h2>
                            <?= htmlspecialchars($user['name']) ?>
                        </h2>
                        <p>
                            <?= htmlspecialchars($user['email']) ?>
                        </p>
                        <a href="logout.php" class="logout-btn">Выйти</a>
                    </div>
                </div>

                <?php if (!empty($orders)): ?>
                    <div class="orders-section">
                        <h3>📦 Последние заказы</h3>
                        <?php foreach ($orders as $order):
                            $statusClass = '';
                            switch ($order['status']) {
                                case 'new':
                                    $statusClass = 'status-new';
                                    break;
                                case 'paid':
                                    $statusClass = 'status-paid';
                                    break;
                                case 'shipped':
                                    $statusClass = 'status-shipped';
                                    break;
                                default:
                                    $statusClass = '';
                            }
                            ?>
                                        <div class="order-item">
                                            <span>Заказ №<?= $order['id'] ?></span>
                                            <span><?= date('d.m.Y', strtotime($order['created_at'])) ?></span>
                                            <span class="order-status <?= $statusClass ?>"><?= $order['status'] ?></span>
                                            <span class="order-total"><?= number_format($order['total'], 0, '.', ' ') ?> ₽</span>
                                        </div>
                                <?php endforeach; ?>
                            </div>
                    <?php else: ?>
                            <p class="no-orders-text">У вас пока нет заказов</p>
                    <?php endif; ?>
                </div>
        <?php else: ?>
                <!-- НЕАВТОРИЗОВАННЫЙ ПОЛЬЗОВАТЕЛЬ: ВХОД / РЕГИСТРАЦИЯ -->
                <div class="profile-card">
                    <div class="tabs">
                        <button class="tab-btn active" data-tab="login">Вход</button>
                        <button class="tab-btn" data-tab="register">Регистрация</button>
                    </div>

                    <?php if (isset($error)): ?>
                            <div class="error-message"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <!-- Форма входа -->
                    <div id="login-tab" class="tab-content active">
                        <form method="POST">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Ваш email" required>
                            </div>
                            <div class="form-group">
                                <label>Пароль</label>
                                <input type="password" name="password" placeholder="Пароль" required>
                            </div>
                            <button type="submit" name="login" class="btn-primary">Войти</button>
                        </form>
                    </div>

                    <!-- Форма регистрации -->
                    <div id="register-tab" class="tab-content">
                        <form method="POST">
                            <div class="form-group">
                                <label>Имя</label>
                                <input type="text" name="name" placeholder="Ваше имя" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <label>Пароль (мин. 6 символов)</label>
                                <input type="password" name="password" placeholder="Пароль" required minlength="6">
                            </div>
                            <button type="submit" name="register" class="btn-primary">Зарегистрироваться</button>
                        </form>
                    </div>
                </div>
        <?php endif; ?>
    </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script>
        // Переключение табов
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabId = btn.dataset.tab;
                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === tabId + '-tab') {
                        content.classList.add('active');
                    }
                });
            });
        });
    </script>
   
</body>

</html>