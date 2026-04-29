<?php
#Страница "О нас" содержащая информацию
$pageTitle = 'О нас | AquaStyle'; 
require 'header.php';
?>

    <div class="main-content about-content">
        <div class="about-hero">
            <h1>AquaStyle</h1>
            <p>Магазин аквариумистики с душой и опытом с 2015 года</p>
        </div>

        <div class="about-history">
            <h2>Наша история</h2>
            <p style="font-size: 1.1rem; line-height: 1.6;">Мы начали свой путь как небольшой семейный магазин живых
                рыбок и аквариумов. За 9 лет выросли в крупнейший интернет-магазин аквариумистики в регионе. Наша
                команда — это аквариумисты с многолетним стажем, которые лично тестируют каждый товар перед тем, как
                предложить вам. Мы любим подводный мир и хотим, чтобы ваши питомцы жили в комфорте и красоте.</p>
        </div>

        <div class="about-stats">
            <div class="stat-card">
                <div class="stat-number">5000+</div>
                <div>довольных клиентов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1200+</div>
                <div>товаров в наличии</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">9 лет</div>
                <div>на рынке</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div>поддержка</div>
            </div>
        </div>

        <h2 style="margin: 40px 0 20px;">Наша команда</h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="team-avatar"><img src="src/icon.png" alt="Аватарка пользователя" /></div>
                <h3>Анна Петрова</h3>
                <p>Основатель, ихтиолог</p>
            </div>
            <div class="team-member">
                <div class="team-avatar"><img src="src/icon.png" alt="Аватарка пользователя" /></div>
                <h3>Дмитрий Смирнов</h3>
                <p>Эксперт по оборудованию</p>
            </div>
            <div class="team-member">
                <div class="team-avatar"><img src="src/icon.png" alt="Аватарка пользователя" /></div>
                <h3>Елена Коваль</h3>
                <p>Специалист по аквадизайну</p>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
        
</body>

</html>