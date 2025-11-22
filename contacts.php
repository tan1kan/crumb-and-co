<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты | Crumb & Co</title>
    <link rel="stylesheet" href="css/style.css">
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
    </style>
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

    <main class="contacts-page">
        <div class="container">
            <h1>Наши контакты</h1>
            
            <div class="contacts__grid">
                <div class="contacts__info">
                    <h2>Как нас найти</h2>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>г. Иркутск, ул. Ленина, 123</p>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <p>+7 (4212) 123-456</p>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <p>info@tortmaster.ru</p>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <p>Режим работы:<br>Пн-Пт: 9:00 - 20:00<br>Сб-Вс: 10:00 - 18:00</p>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-vk"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-telegram"></i></a>
                    </div>
                </div>
                
                <div class="contacts__map">
                    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A1234567890abcdef&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe>
                </div>
            </div>
            
            <!-- Блок формы обратной связи -->
            <section id="feedback" class="content-block">
                <div class="container my-5">
                    <h2>Обратная связь</h2>
                    <form id="feedbackForm" style="max-width: 600px;">
                        <div class="form-group">
                            <label for="name">Ваше имя</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Введите ваше имя">
                        </div>
                        <div class="form-group">
                            <label for="email">Ваш Email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Введите ваш Email">
                        </div>
                        <div class="form-group">
                            <label for="message">Сообщение</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Введите ваше сообщение"></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary">Отправить</button>
                    </form>
                    <p id="feedbackSuccess" class="mt-3" style="display:none; color:green;">Спасибо! Ваше сообщение отправлено.</p>
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

    <script src="js/main.js"></script>
    <script>
        // Простая обработка формы (можно отправлять на PHP-обработчик позже)
        document.getElementById('feedbackForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            // Здесь можно добавить отправку на mail.php или другой обработчик
            document.getElementById('feedbackSuccess').style.display = 'block';
            this.reset();
            setTimeout(() => {
                document.getElementById('feedbackSuccess').style.display = 'none';
            }, 3000);
        });
    </script>
</body>
</html>