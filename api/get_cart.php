    <?php
    require '../config.php';
    header('Content-Type: application/json');

    $user = getCurrentUser($pdo);
    $session_id = $_SESSION['session_id'];

    if ($user) {
        $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
        $stmt->execute([$user['id']]);
    } else {
        $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE session_id = ?");
        $stmt->execute([$session_id]);
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    ?>