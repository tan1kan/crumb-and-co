<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    exit('Доступ запрещён');
}

$pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$id]);
}

header('Location: index.php');
exit;
?>