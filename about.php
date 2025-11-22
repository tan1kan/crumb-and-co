<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обо мне | Crumb & Co</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Стили для аватара/иконки профиля */
        .header__user .auth-button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
        }
        .user-name {
            display: none;
        }
        @media (min-width: 768px) {
            .user-name {
                display: inline;
            }
        }
        .footer {
  background-color: #a89f96;
  color: #f5f0ec; /* Светлый кремовый текст */
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

.footer__social a {
  color: #f5f0ec;
}

.footer__social a:hover {
  color: #a89f96; /* Цвет акцента при наведении */
}

    </style>
</head>
<body>
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

    <main class="about-page">
        <div class="container">
            
            
            <section class="about-section">
                <div class="about__content">
                    <h2>Биография</h2>
                    <p>Меня зовут Анна, я профессиональный кондитер с 10-летним опытом работы. С детства я увлекалась кулинарией, а после окончания кулинарной академии в Париже решила посвятить себя созданию красивых и вкусных десертов.</p>
                    <p>Моя философия - использовать только натуральные ингредиенты и создавать уникальные вкусовые сочетания, которые запомнятся вам надолго.</p>
                </div>
                <div class="about__image">
                    <img src="img/22.jpg" alt="Анна - кондитер">
                </div>
            </section>
            
            <section class="experience-section">
                <h2>Образование и опыт работы</h2>
                <div class="timeline">
                    <div class="timeline__item">
                        <div class="timeline__year">2010-2013</div>
                        <div class="timeline__content">
                            <h3>Кулинарная академия Le Cordon Bleu, Париж</h3>
                            <p>Специализация: кондитерское искусство и выпечка</p>
                        </div>
                    </div>
                    <div class="timeline__item">
                        <div class="timeline__year">2013-2015</div>
                        <div class="timeline__content">
                            <h3>Кондитер в ресторане "Пушкинъ", Москва</h3>
                            <p>Работа в команде мишленовских поваров</p>
                        </div>
                    </div>
                    <div class="timeline__item">
                        <div class="timeline__year">2015-2025</div>
                        <div class="timeline__content">
                            <h3>Частная кондитерская "Торт Мастер", Иркутск</h3>
                            <p>Создание авторских тортов и десертов на заказ</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="process-section">
                <h2>Мой рабочий процесс</h2>
                <div class="process__gallery">
                    <div class="process__item">
                        <img src="img/15.jpg" alt="Подготовка ингредиентов">
                        <p>Тщательный отбор ингредиентов</p>
                    </div>
                    <div class="process__item">
                        <img src="img/30.png" alt="Приготовление теста">
                        <p>Ручная работа на каждом этапе</p>
                    </div>
                    <div class="process__item">
                        <img src="img/28.jpg" alt="Украшение торта">
                        <p>Детальная проработка декора</p>
                    </div>
                    <div class="process__item">
                        <img src="img/bento.jpg" alt="Готовый торт">
                        <p>Идеальный результат</p>
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
</body>
</html>