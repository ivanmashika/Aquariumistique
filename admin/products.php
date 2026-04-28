<?php include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $is_sale = isset($_POST['is_sale']) ? 1 : 0;
    $sale_price = $is_sale ? (float) $_POST['sale_price'] : null;
    $image = $_POST['image'] ?? 'product-placeholder.png'; // или загрузка файла

    $stmt = $pdo->prepare("INSERT INTO products (name, category, description, price, stock, is_sale, sale_price, image) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$name, $category, $description, $price, $stock, $is_sale, $sale_price, $image]);
}

$products = $pdo->query("SELECT * FROM products ORDER BY name")->fetchAll();
$categories = $pdo->query("SELECT name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
?>

<h2>Товары</h2>

<div class="admin-form">
    <h3>Добавить товар</h3>
    <form method="POST">
        <label>Название</label>
        <input type="text" name="name" required>

        <label>Категория</label>
        <select name="category" required>
            <option value="">-- Выберите --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Описание</label>
        <textarea name="description" rows="3"></textarea>

        <label>Цена</label>
        <input type="number" step="0.01" name="price" required>

        <label>Количество на складе</label>
        <input type="number" name="stock" value="0" required>

        <label>
            <input type="checkbox" name="is_sale"
                onchange="document.getElementById('sale_price').style.display=this.checked?'block':'none'">
            Товар со скидкой
        </label>
        <input type="number" step="0.01" name="sale_price" id="sale_price" style="display:none;"
            placeholder="Цена со скидкой">

        <label>Изображение (имя файла в папке src/)</label>
        <input type="text" name="image" value="product-placeholder.png">

        <button type="submit" name="add_product" class="btn-admin">Добавить</button>
    </form>
</div>
<div class="admin-table-container">
    <table class="admin-table" style="margin-top:30px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>В наличии</th>
                <th>Скидка</th>
                <!-- Действия (редактирование/удаление) можно добавить позже -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <?= $p['id'] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($p['name']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($p['category']) ?>
                    </td>
                    <td>
                        <?= $p['is_sale'] ? number_format($p['sale_price'], 2) : number_format($p['price'], 2) ?> ₽
                    </td>
                    <td>
                        <?= $p['stock'] ?>
                    </td>
                    <td>
                        <?= $p['is_sale'] ? 'Да' : 'Нет' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>