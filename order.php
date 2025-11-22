<?php
session_start();

$id = $_GET['id'] ?? null;
$raw_name = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$price = $_GET['price'] ?? 0;

// Защита от XSS
$product_name = htmlspecialchars($raw_name);
$price = (float)$price;
$id = (int)$id;

// Если пользователь авторизован — получаем его данные
$user_name = '';
$user_email = '';
$user_phone = ''; // ← добавим телефон тоже
if (isset($_SESSION['user_id'])) {
    $pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if ($user) {
        $user_name = htmlspecialchars($user['name']);
        $user_email = htmlspecialchars($user['email']);
        $user_phone = htmlspecialchars($user['phone'] ?? '');
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказать <?= $product_name ?> | Crumb & Co</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <style>
        /* === Стили для страницы оформления заказа === */
        .order-page {
            padding-top: 100px;
            padding-bottom: 60px;
        }

        .order-product {
            background: white;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            text-align: center;
            border: 1px solid #f0f0f0;
        }

        .order-product h2 {
            margin-bottom: 16px;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .order-product h3 {
            font-size: 1.8rem;
            margin-bottom: 12px;
            color: var(--dark-color);
        }

        .order-product .price {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Форма */
        .order-form {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #f5f3f1;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 1.05rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fdfdfd;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(168, 159, 150, 0.15);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group small {
            color: #888;
            font-weight: 400;
            font-size: 0.9rem;
        }

        /* Кнопка */
        .btn--primary {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background: var(--primary-color);
            color: white;
            transition: all 0.3s ease;
        }

        .btn--primary:hover {
            background: #958a7e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(168, 159, 150, 0.3);
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .order-form {
                padding: 24px 20px;
            }
            .order-product {
                padding: 20px;
            }
        }

        .footer {
            background-color: #a89f96;
            color: #f5f0ec;
            padding: 30px 0;
            margin-top: auto;
        }

        .footer .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .footer__logo {
            height: 50px;
            margin-bottom: 15px;
        }

        .footer__contacts h3,
        .footer__social h3,
        .footer__info p {
            color: #f5f0ec;
        }

        .footer__contacts p,
        .footer__info p {
            display: flex;
            align-items: center;
        }

        .footer__contacts i,
        .footer__contacts p {
            margin-right: 10px;
        }

        .footer__social a {
            color: #f5f0ec;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: var(--transition);
        }

        .footer__social a:hover {
            color: #f5f0ec;
            opacity: 0.8;
        }
    </style>
    <header class="header">
        <div class="container">
            <div class="header__logo">
                <a href="index.php">
                    <img src="img/logo.jpg" alt="Crumb & Co">
                </a>
            </div>
            <nav class="header__nav">
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="services.php">Категории</a></li>
                    <li><a href="about.php">Обо мне</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                    <li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="account.php">Личный кабинет</a>
                        <?php else: ?>
                            <a href="auth.php">Вход</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="order-page">
        <div class="container">
            <?php if ($id): ?>
            <div class="order-product">
                <h2>Вы заказываете:</h2>
                <div class="product-info">
                    <h3><?= $product_name ?></h3>
                    <p class="price">Стоимость: <strong><?= number_format($price, 0, '', ' ') ?> ₽</strong></p>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="send_order.php" class="order-form">
                <input type="hidden" name="product_id" value="<?= $id ?>">
                <input type="hidden" name="product_name" value="<?= $product_name ?>">
                <input type="hidden" name="price" value="<?= $price ?>">

                <div class="form-group">
                    <label for="name">Ваше имя *</label>
                    <input type="text" id="name" name="name" value="<?= $user_name ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Телефон *</label>
                    <input type="tel" id="phone" name="phone" value="<?= $user_phone ?>" required placeholder="+7 (___) ___-__-__">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= $user_email ?>" placeholder="Ваш email">
                </div>

                <div class="form-group">
                    <label for="delivery_date">Дата доставки *</label>
                    <input type="date" id="delivery_date" name="delivery_date" required min="<?= date('Y-m-d') ?>">
                    <small>Минимальная дата — сегодня</small>
                </div>

                <div class="form-group">
                    <label for="delivery_time">Время доставки *</label>
                    <select id="delivery_time" name="delivery_time" required>
                        <option value="">Выберите время</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                    </select>
                    <small>Доставка с 10:00 до 19:00</small>
                </div>

                <div class="form-group">
                    <label for="address">Адрес доставки *</label>
                    <textarea id="address" name="address" placeholder="Укажите полный адрес: улица, дом, подъезд, этаж, код..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="comment">Дополнительные пожелания<br><small>(надпись на торте, тематика, украшения и т.д.)</small></label>
                    <textarea id="comment" name="comment" rows="4" placeholder="Например: «С Днём Рождения, Маша!» или «Торт в стиле Минни»"></textarea>
                </div>

                <button type="submit" class="btn btn--primary">Отправить заказ</button>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__info">
                <img src="img/.jpg" alt="Crumb & Co" class="footer__logo">
                <p>Кондитерская в Иркутске. Торты на заказ с 2025 года.</p>
            </div>
            <div class="footer__contacts">
                <h3>Контакты</h3>
                <p><i class="fas fa-phone"></i> +7 (4212) 123-456</p>
                <p><i class="fas fa-map-marker-alt"></i> г. Иркутск, ул. Ленина, 123</p>
            </div>
            <div class="footer__social">
                <h3>Мы в соцсетях</h3>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-vk"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>