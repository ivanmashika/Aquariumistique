<?php
$pageTitle = 'AquaStyle | Главная';
require 'header.php';
?>
<!-- Главная страница -->
<div class="main-content no-sidebar">

    <main class="content">
        <h1>Главная страница</h1>
        <section class="slider">
            <h3>Акции и новости</h3>
            <div class="news-grid">
                <a href="catalog.php?sale=true" class="news-link">
                    <div class="news-item">
                        <div class="news-img">🐳</div>
                        <p>Скидка 20% на аквариумы</p>
                    </div>
                </a>
                <a href="catalog.php?category=Оборудование" class="news-link">
                    <div class="news-item">
                        <div class="news-img">🗑️</div>
                        <p>Новые фильтры Eheim</p>
                    </div>
                </a>
                <a href="catalog.php?free_delivery=true" class="news-link">
                    <div class="news-item">
                        <div class="news-img">🚚</div>
                        <p>Бесплатная доставка от 3000₽</p>
                    </div>
                </a>
                <a href="about.php" class="news-link">
                    <div class="news-item">
                        <div class="news-img">🎨</div>
                        <p>Конкурс «Аквадизайн»</p>
                    </div>
                </a>
            </div>
        </section>
        <section class="catalogs">
            <div class="catalogs-grid">
                <?php
                #Отображение категорий
                $categories = [
                    'Аквариумы',
                    'Оборудование',
                    'Корм и добавки',
                    'Украшения',
                    'Рыбки и растения',
                    'Книги и аксессуары'
                ];
                foreach ($categories as $cat) {
                    echo "<a href='catalog.php?category=" . urlencode($cat) . "' class='catalog-link'>";
                    echo "<div class='catalog-item'>";
                    echo "<div class='catalog-img'></div>";
                    echo "<h4>$cat</h4>";
                    echo "</div>";
                    echo "</a>";
                }
                ?>
            </div>
        </section>
        <section class="featured-article">
            <div class="article-card">
                <div class="article-img">📘</div>
                <div class="article-info readable-text">
                    <h2>Как выбрать аквариум: 5 важных шагов</h2>
                    <p>Узнайте, на что обратить внимание при покупке первого аквариума: размер, форма, материал и
                        совместимость с будущими обитателями.</p>
                    <a href="about.php#article" class="btn article-btn">Читать далее</a>
                </div>
            </div>
        </section>
    </main>
</div>
<?php include 'footer.php'; ?>
<script src="script.js"></script>

</body>

</html>