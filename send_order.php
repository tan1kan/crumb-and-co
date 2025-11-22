<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Метод не поддерживается');
}

// Подключение к БД
$pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получаем данные
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$product_id = (int)($_POST['product_id'] ?? 0);
$price = (float)($_POST['price'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

// Валидация
if (!$name || !$phone || !$product_name) {
    die('Ошибка: заполните имя, телефон и выберите товар.');
}

// Сохраняем заказ БЕЗ user_id
$pdo->prepare("
    INSERT INTO orders (user_name, user_phone, user_email, product_name, product_id, price, comment)
    VALUES (?, ?, ?, ?, ?, ?, ?)
")->execute([$name, $phone, $email, $product_name, $product_id, $price, $comment]);

// Получаем ID заказа
$order_id = $pdo->lastInsertId();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказ принят | Crumb & Co</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; line-height: 1.6; }
        .success { color: #27ae60; font-size: 1.3em; margin: 20px 0; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #a89f96; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>✅ Спасибо за заказ!</h1>
    <p class="success">Ваш заказ №<?= $order_id ?> успешно оформлен.</p>
    <p>Мы свяжемся с вами по телефону: <strong><?= htmlspecialchars($phone) ?></strong></p>
    <p>Заказано: <strong><?= htmlspecialchars($product_name) ?></strong> за <?= number_format($price, 0, '', ' ') ?> ₽</p>
    
    <?php if ($comment): ?>
        <p>Ваши пожелания: <?= htmlspecialchars($comment) ?></p>
    <?php endif; ?>

    <br><br>
    <a href="index.php" class="btn">Вернуться на главную</a>
</body>
</html>