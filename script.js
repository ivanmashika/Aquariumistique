const BASE_URL = window.location.origin + '/Aquariumistique/';
function renderProducts(products) {
    const grid = document.getElementById('products-grid');
    if (!grid) return;
    grid.innerHTML = '';
    if (!products.length) {
        grid.innerHTML = '<p>Товары не найдены</p>';
        return;
    }
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

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

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
// === Карусель на главной ===
document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');
    const slides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.carousel-btn.prev');
    const nextBtn = document.querySelector('.carousel-btn.next');
    const dotsContainer = document.querySelector('.carousel-dots');
    
    if (!track || slides.length === 0) return;
    
    let currentIndex = 0;
    let slidesPerView = 4;
    let totalSlides = slides.length;
    
    // Определяем количество видимых слайдов
    function updateSlidesPerView() {
        if (window.innerWidth <= 480) slidesPerView = 1;
        else if (window.innerWidth <= 768) slidesPerView = 2;
        else if (window.innerWidth <= 1024) slidesPerView = 3;
        else slidesPerView = 4;
        updateCarousel();
    }
    
    function updateCarousel() {
        const maxIndex = Math.max(0, totalSlides - slidesPerView);
        if (currentIndex > maxIndex) currentIndex = maxIndex;
        const offset = -currentIndex * (100 / slidesPerView);
        track.style.transform = `translateX(${offset}%)`;
        updateDots();
    }
    
    function updateDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        const dotsCount = Math.ceil(totalSlides / slidesPerView);
        for (let i = 0; i < dotsCount; i++) {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (i === Math.floor(currentIndex / (totalSlides / dotsCount))) dot.classList.add('active');
            dot.addEventListener('click', () => {
                currentIndex = i * slidesPerView;
                if (currentIndex > totalSlides - slidesPerView) currentIndex = totalSlides - slidesPerView;
                updateCarousel();
            });
            dotsContainer.appendChild(dot);
        }
    }
    
    nextBtn?.addEventListener('click', () => {
        if (currentIndex + slidesPerView < totalSlides) {
            currentIndex += slidesPerView;
        } else {
            currentIndex = 0; // зацикливание
        }
        updateCarousel();
    });
    
    prevBtn?.addEventListener('click', () => {
        if (currentIndex - slidesPerView >= 0) {
            currentIndex -= slidesPerView;
        } else {
            currentIndex = Math.max(0, totalSlides - slidesPerView); // на последние
        }
        updateCarousel();
    });
    
    window.addEventListener('resize', () => {
        updateSlidesPerView();
    });
    
    updateSlidesPerView();
});