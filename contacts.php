<?php require 'config.php';
#Логика отправки формы (заглушка)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $success = true;
}
?>
<?php
$pageTitle = 'AquaStyle | Контакты'; 
require 'header.php';
?>


    <div class="main-content no-sidebar contacts-content">
        <h1 class="contacts-title">Наши контакты</h1>

        <div class="contacts-wrapper">
            <div class="contact-info">
                <h3>Свяжитесь с нами</h3>
                <div class="contact-detail">
                    <div class="contact-icon">📍</div>
                    <div>г. Москва, ул. Аквариумная, д. 15<br>ТЦ «Подводный мир», 2 этаж</div>
                </div>
                <div class="contact-detail">
                    <div class="contact-icon">📞</div>
                    <div>+7 (495) 123-45-67<br>+7 (800) 555-33-22 (бесплатно)</div>
                </div>
                <div class="contact-detail">
                    <div class="contact-icon">✉️</div>
                    <div>info@aquastyle.ru<br>support@aquastyle.ru</div>
                </div>
                <div class="contact-detail">
                    <div class="contact-icon">💬</div>
                    <div>Telegram: <a href="https://t.me/aquastyle">@aquastyle</a><br>VK: <a
                            href="vk.com/aquastyle">vk.com/aquastyle</a></div>
                </div>
                <div class="contact-detail">
                    <div class="contact-icon">🕒</div>
                    <div>Пн-Пт: 10:00 – 20:00<br>Сб-Вс: 11:00 – 18:00</div>
                </div>
            </div>

            <div class="contact-form">
                <h3>Напишите нам</h3>
                <?php if (isset($success)): ?>
                    <div class="success-message">
                        ✅ Сообщение отправлено! Мы ответим в ближайшее время.
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="email" name="email" placeholder="Email для ответа" required>
                    <textarea name="message" rows="5" placeholder="Ваше сообщение..." required></textarea>
                    <button type="submit" name="send" class="btn btn-send">Отправить</button>
                </form>
            </div>
        </div>

        <div class="map">
            <iframe
                src="https://yandex.ru/map-widget/v1/?um=constructor%3A2fa95877855e45ac469cb7351b8f06139475e11ed4dc2e72cde938c90fda0105&amp;source=constructor"
                width="100%" height="400" frameborder="0"></iframe>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
    
</body>

</html>