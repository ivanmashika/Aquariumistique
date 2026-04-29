// Базовый URL для API-запросов
const BASE_URL = window.location.origin + '/Aquariumistique/';

// Функция отрисовки карточек товаров в каталоге
function renderProducts(products) {
    const grid = document.getElementById('products-grid');
    if (!grid) return;
    grid.innerHTML = '';

    // Если товаров нет — показываем сообщение
    if (!products.length) {
        grid.innerHTML = '<p>Товары не найдены</p>';
        return;
    }

    // Перебираем массив товаров и создаём карточки
    products.forEach(p => {
        let finalPrice = p.is_sale ? p.sale_price : p.price;
        let priceHtml = p.is_sale ? `<span class="price">${p.sale_price} ₽</span> <span class="old-price">${p.price} ₽</span>` : `<span class="price">${p.price} ₽</span>`;
        let stockText = p.stock > 0 ? `В наличии: ${p.stock}` : 'Нет в наличии';
        let btnDisabled = p.stock === 0 ? 'disabled' : '';
        let card = document.createElement('div');
        card.className = 'product-card';
        card.innerHTML = `
            <div class="product-img" style="background-image: url('src/${p.image || 'placeholder.jpg'}'); background-size: cover;"></div>
            <div class="product-info">
                <h3>${escapeHtml(p.name)}</h3>
                <p>${escapeHtml(p.description.substring(0, 80))}...</p>
                <div>${priceHtml}</div>
                <div class="stock">${stockText}</div>
                <button class="btn ${p.stock === 0 ? 'out-of-stock' : ''}" ${btnDisabled} onclick="addToCart(${p.id})">В корзину</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Экранирование HTML-символов для защиты от XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function (m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Загрузка товаров с учётом фильтров и дополнительных параметров
function loadProducts(extraParams = {}) {
    let inStock = document.getElementById('in-stock')?.checked ? 'true' : 'false';
    let onOrder = document.getElementById('on-order')?.checked ? 'true' : 'false';
    let sale = document.getElementById('sale')?.checked ? 'true' : 'false';

    let url = `api/get_products.php?in_stock=${inStock}&on_order=${onOrder}&sale=${sale}`;
    if (extraParams.category) {
        url += `&category=${encodeURIComponent(extraParams.category)}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(products => renderProducts(products))
        .catch(err => console.error('Ошибка загрузки товаров:', err));
}

// Добавление товара в корзину
function addToCart(productId) {
    fetch('api/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                alert('Товар добавлен в корзину');
            } else alert('Ошибка: ' + (data.error || 'неизвестная'));
        })
        .catch(err => alert('Ошибка соединения'));
}

// Обновление счётчика товаров в иконке корзины
function updateCartCount() {
    fetch('api/get_cart.php')
        .then(res => res.json())
        .then(data => {
            let count = data.reduce((sum, item) => sum + item.quantity, 0);
            let badge = document.querySelector('.cart-count');
            if (badge) badge.innerText = count;
            else {
                let cartLink = document.querySelector('.cart a');
                if (cartLink) {
                    let span = document.createElement('span');
                    span.className = 'cart-count';
                    span.innerText = count;
                    cartLink.parentElement.appendChild(span);
                }
            }
        })
        .catch(err => console.error('Ошибка получения корзины:', err));
}
// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    if (document.getElementById('products-grid')) {
        loadProducts();
        const filters = document.querySelectorAll('.filter-item input');
        filters.forEach(filter => {
            filter.addEventListener('change', loadProducts);
        });
    }
});
// Работа бургер меню
document.addEventListener('DOMContentLoaded', () => {
    const burger = document.createElement('button');
    burger.className = 'burger';
    burger.innerHTML = '<span></span><span></span><span></span>';
    const nav = document.querySelector('nav');
    const header = document.querySelector('.top-menu');

    if (nav && header && !document.querySelector('.burger')) {
        // Вставляем бургер перед навигацией
        const cart = document.querySelector('.cart');
        header.insertBefore(burger, nav);

        burger.addEventListener('click', () => {
            burger.classList.toggle('active');
            nav.classList.toggle('active');
        });

        nav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                burger.classList.remove('active');
                nav.classList.remove('active');
            });
        });

        document.addEventListener('click', (e) => {
            if (!nav.contains(e.target) && !burger.contains(e.target) && nav.classList.contains('active')) {
                burger.classList.remove('active');
                nav.classList.remove('active');
            }
        });
    }
});
