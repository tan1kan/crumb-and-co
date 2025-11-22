<?php
// services.php

// Подключение к БД
$pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получаем все категории для фильтров
$stmt = $pdo->query("SELECT id, name, slug FROM categories ORDER BY name");
$allCategories = $stmt->fetchAll();

// Определяем текущую категорию из URL
$categorySlug = $_GET['category'] ?? 'all';
$products = [];

if ($categorySlug === 'all') {
    // Все товары
    $stmt = $pdo->query("SELECT id, name, description, price_min, image FROM products ORDER BY name");
    $products = $stmt->fetchAll();
} else {
    // Товары по категории
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.description, p.price_min, p.image
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE c.slug = ?
        ORDER BY p.name
    ");
    $stmt->execute([$categorySlug]);
    $products = $stmt->fetchAll();
}
?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Услуги | Торт Мастер</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <style>
        /* === Подвал === */
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
.categories__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 30px;
}
.product-card {
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    height: 100%; /* ← важно! */
}
.product-card__info {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
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
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Авторизованный пользователь -->
            <li class="header__user">
                <a href="account.php" class="auth-button" title="Личный кабинет">
                    <i class="fas fa-user"></i> 
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Профиль') ?></span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="auth-button">
                    <i class="fas fa-sign-out-alt"></i> Выйти
                </a>
            </li>
        <?php else: ?>
            <!-- Не авторизован -->
            <li>
                <a href="auth.php" class="auth-button">
                    <i class="fas fa-sign-in-alt"></i> Вход
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
        </div>
    </header>

    <main class="services-page">
        <div class="container">
            
            
           <!-- Фильтры -->
<!-- Фильтры -->
<div class="services__categories">
    <a href="services.php?category=all" class="category-btn <?= $categorySlug === 'all' ? 'active' : '' ?>">Все</a>
    <?php foreach ($allCategories as $cat): ?>
        <a href="services.php?category=<?= htmlspecialchars($cat['slug']) ?>" 
           class="category-btn <?= $categorySlug === $cat['slug'] ? 'active' : '' ?>">
            <?= htmlspecialchars($cat['name']) ?>
        </a>
    <?php endforeach; ?>
</div>
            
            <section class="categories">
                <div class="container">
                    <div class="categories__grid">
    <?php if (empty($products)): ?>
        <p style="grid-column: 1 / -1; text-align: center; color: #777;">Товары не найдены.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="img/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" 
                 alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="product-card__info">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description'] ?? 'Без описания') ?></p>
                <div class="product-card__price">от <?= number_format($product['price_min'], 0, '', ' ') ?>₽</div>
                <button class="btn btn--primary add-to-cart" 
                        data-id="<?= $product['id'] ?>" 
                        data-name="<?= htmlspecialchars($product['name']) ?>" 
                        data-price="<?= $product['price_min'] ?>">
                    Заказать
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
                    
                       
                    
                </div>
            </div>
        </section>

            <section class="custom-order">
                <h2>Индивидуальный заказ</h2>
                <p>Не нашли то, что искали? Мы можем создать торт или десерт специально для вас по вашему дизайну!</p>
                <div class="custom-order__steps">
                    <div class="step">
                        <div class="step__number">1</div>
                        <h3>Консультация</h3>
                        <p>Обсудите ваши идеи с нашим кондитером</p>
                    </div>
                    <div class="step">
                        <div class="step__number">2</div>
                        <h3>Дизайн</h3>
                        <p>Мы создадим эскиз будущего изделия</p>
                    </div>
                    <div class="step">
                        <div class="step__number">3</div>
                        <h3>Подтверждение</h3>
                        <p>Утвердите дизайн и состав десерта</p>
                    </div>
                    <div class="step">
                        <div class="step__number">4</div>
                        <h3>Изготовление</h3>
                        <p>Мы приготовим ваш идеальный десерт</p>
                    </div>
                </div>
        
            </section>
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
    <script>
        // js/main.js

document.addEventListener('DOMContentLoaded', function () {
    // === Кнопка "В корзину" — переход на оформление заказа ===
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = encodeURIComponent(this.dataset.name);
            const price = this.dataset.price;
            window.location.href = `order.php?id=${id}&name=${name}&price=${price}`;
        });
    });
});
    </script>
</body>
</html>