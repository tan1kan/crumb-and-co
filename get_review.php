<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';

$stmt = $pdo->query("
    SELECT user_name, review_text, rating, photo, created_at 
    FROM reviews 
    WHERE is_approved = 1 
    ORDER BY created_at DESC 
    LIMIT 10
");
$reviews = $stmt->fetchAll();

echo json_encode($reviews, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
?>