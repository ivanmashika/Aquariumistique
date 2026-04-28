<?php
session_start();
$host = 'localhost';
$dbname = 'aquarium_shop';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}
$session_id = $_SESSION['session_id'];

function getCurrentUser($pdo)
{
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
function isAdmin() {
    $user = getCurrentUser($GLOBALS['pdo']);
    return $user && $user['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /profile.php');
        exit;
    }
}
?>