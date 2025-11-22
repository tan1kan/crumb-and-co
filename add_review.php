<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Метод не разрешён');
}

// Получаем данные
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$reviewText = trim($_POST['reviewText'] ?? '');
$rating = (int)($_POST['rating'] ?? 0);

// Валидация
if (empty($name) || empty($email) || empty($reviewText) || !filter_var($email, FILTER_VALIDATE_EMAIL) || $rating < 1 || $rating > 5) {
    die('Ошибка: заполните все поля корректно.');
}

$photoPath = null;

// Обработка фото (опционально)
if (!empty($_FILES['photo']['name'])) {
    $uploadDir = 'uploads/reviews/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileExt = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileExt, $allowed) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid('review_', true) . '.' . $fileExt;
        $photoPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $photoPath = null; // если не удалось загрузить — игнорируем
        }
    }
}

// Сохраняем в БД
try {
    $stmt = $pdo->prepare("
        INSERT INTO reviews (user_name, email, review_text, rating, photo)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $email, $reviewText, $rating, $photoPath]);

    // Перенаправляем обратно с сообщением
    header('Location: index.php?review_success=1');
    exit;
} catch (Exception $e) {
    error_log("Ошибка при добавлении отзыва: " . $e->getMessage());
    die('Произошла ошибка при сохранении отзыва. Попробуйте позже.');
}
?>