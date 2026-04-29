
<?php
/*Данный скрипт получает товары из базы данных */
require '../config.php';
header('Content-Type: application/json');

$in_stock = isset($_GET['in_stock']) && $_GET['in_stock'] === 'true';
$on_order = isset($_GET['on_order']) && $_GET['on_order'] === 'true';
$sale = isset($_GET['sale']) && $_GET['sale'] === 'true';

$sql = "SELECT * FROM products WHERE 1=1";
if($in_stock) $sql .= " AND stock > 0";
if($on_order) $sql .= " AND stock = 0";
if($sale) $sql .= " AND is_sale = 1";

$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);

$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM products WHERE 1=1";
if($in_stock) $sql .= " AND stock > 0";
if($on_order) $sql .= " AND stock = 0";
if($sale) $sql .= " AND is_sale = 1";
if($category) $sql .= " AND category = " . $pdo->quote($category);
?>