<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    exit('Доступ запрещён');
}

$pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$order_id = (int)($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? 'new';

// Допустимые статусы
$allowed = ['new', 'processing', 'completed'];
if (!in_array($status, $allowed)) {
    exit('Недопустимый статус');
}

if ($order_id) {
    $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")
        ->execute([$status, $order_id]);
}

header('Location: index.php');
exit;
?>