<?php
#Страница каталога, в которую подгружаются товары из базы данных
$pageTitle = 'AquaStyle | Каталог'; 
require 'header.php';
?>

    <div class="main-content">

        <aside class="sidebar">
            <h3>Фильтры</h3>
            <div class="filter-item"><input type="checkbox" id="in-stock"><label>В наличии</label></div>
            <div class="filter-item"><input type="checkbox" id="on-order"><label>Под заказ</label></div>
            <div class="filter-item"><input type="checkbox" id="sale"><label>Со скидкой</label></div>
        </aside>
        <main class="content">
            <h1>Каталог товаров</h1>
            <div class="products-grid" id="products-grid"></div>
        </main>

    </div>
    <?php include 'footer.php'; ?>
    <script src="script.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('category')) {
                if (typeof loadProducts === 'function') {
                    loadProducts({ category: urlParams.get('category') });
                }
            } else if (typeof loadProducts === 'function') {
                loadProducts();
            }
            // Если есть параметр sale, отмечаем чекбокс "Со скидкой"
            if (urlParams.get('sale') === 'true') {
                document.getElementById('sale').checked = true;
                loadProducts();
            }
            // free_delivery пока игнорируем
        });
</script>
    
</body>

</html>