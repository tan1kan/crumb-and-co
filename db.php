<?php
// config/db.php

$host = 'localhost';       // или ваш хост (например, 127.0.0.1)
$dbname = 'crumb_and_co';
$username = 'root';   // например, 'root' (в разработке), но лучше создать отдельного пользовател€
$password = '';         // в продакшене Ч надЄжный пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("ќшибка подключени€ к базе данных: " . $e->getMessage());
}
?>