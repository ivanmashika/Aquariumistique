<?php include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = trim($_POST['name']);
        if ($name) {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
        }
    } elseif (isset($_POST['delete'])) {
        $id = (int) $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<h2>Категории товаров</h2>

<div class="admin-form">
    <h3>Добавить категорию</h3>
    <form method="POST">
        <input type="text" name="name" placeholder="Название категории" required>
        <button type="submit" name="add" class="btn-admin">Добавить</button>
    </form>
</div>
<div class="admin-table-container">
    <table class="admin-table" style="margin-top:30px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>
                        <?= $cat['id'] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($cat['name']) ?>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Удалить категорию? Это не затронет товары.');">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                            <button type="submit" name="delete" class="btn-admin btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>