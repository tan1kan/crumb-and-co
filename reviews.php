<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$db = 'crumb_and_co';
$user = 'root';     // ← ЗАМЕНИТЕ
$pass = '';   // ← ЗАМЕНИТЕ

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT user_name, review_text, rating, photo 
        FROM reviews 
        WHERE is_approved = 1 
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($data as $row) {
        // Формат, который ожидает ваш JS: name, text, rating, photo
        $result[] = [
            'name' => htmlspecialchars($row['user_name']),
            'text' => htmlspecialchars($row['review_text']),
            'rating' => (int)$row['rating'],
            // Путь к фото: у вас файлы в /uploads/reviews/
            'photo' => $row['photo'] 
                ? '/saitik/uploads/reviews/' . basename($row['photo'])
                : '/saitik/img/default-avatar.jpg' // опционально
        ];
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка: ' . $e->getMessage()]);
}