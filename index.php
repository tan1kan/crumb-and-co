<?php
// Настройки подключения
$host = 'localhost';
$dbname = 'crumb_and_co';
$username = 'root';      // ← замените!
$password = '';    // ← замените!
$uploadDir = __DIR__ . '/uploads/reviews/';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

// Получаем одобренные отзывы
$stmt = $pdo->prepare("SELECT user_name, review_text, rating, photo, created_at 
                       FROM reviews 
                       WHERE is_approved = 1 
                       ORDER BY created_at DESC 
                       LIMIT 10"); // максимум 10 отзывов
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crumb & Co</title>
    <link rel="stylesheet" href="css/index.css">
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
<!--Фото-->
    <main>
        <div class="hero">
            <div class="container">
                <h1>Авторские торты на заказ в Иркутске</h1>
                <p>Создадим торт вашей мечты для любого праздника</p>
                <a href="services.php" class="btn btn--primary">Выбрать торт</a>
            </div>
        </div>
    </main>

       <section class="categories">
    <div class="container">
        <h2>Категории</h2>
        <div class="categories__grid">
            <a href="services.php?category=cakes" class="category-card">
                <img src="img/cakes.jpg" alt="Торты">
                <h3>Торты</h3>
            </a>
            <a href="services.php?category=desserts" class="category-card">
                <img src="img/dessert.jpg" alt="Десерты">
                <h3>Десерты</h3>
            </a>
            <a href="services.php?category=pastries" class="category-card">
                <img src="img/pastry.jpg" alt="Пирожные">
                <h3>Пирожные</h3>
            </a>
            <a href="services.php?category=bento-cakes" class="category-card">
                <img src="img/bento.jpg" alt="Бенто-торты">
                <h3>Бенто-торты</h3>
            </a>
        </div>
    </div>
</section>
        <h2 style="text-align:center; margin-bottom:20px;">Почему стоит заказать у нас в кондитерской</h2>
<div class="cards-container" id="prem">
  <!-- Карточка 1 -->
  <div class="card">
    <div class="card-icon">🍰</div>
    <h3 class="card-title">Современное оборудование и технологии</h3>
    <p class="card-text">Мы используем новейшие технологии и оборудование для производства нашей выпечки, что позволяет обеспечивать стабильное качество и уникальный вкус нашей продукции.</p>
  </div>
  
  <!-- Карточка 2 -->
  <div class="card">
    <div class="card-icon">🔬</div>
    <h3 class="card-title">Контроль качества на каждом этапе</h3>
    <p class="card-text">В нашей кондитерской имеется собственная аттестованная лаборатория, где мы проводим тщательный контроль всех ингредиентов и готовой продукции, гарантируя соответствие самым высоким стандартам.</p>
  </div>
  
  <!-- Карточка 3 -->
  <div class="card">
    <div class="card-icon">🚚</div>
    <h3 class="card-title">Быстрая доставка с помощью современного автопарка</h3>
    <p class="card-text">Наши курьеры обеспечивают быструю и надежную доставку, чтобы вы могли наслаждаться свежей выпечкой в любое время. Мы готовы доставить ваши заказы даже на самые сложные мероприятия.</p>
  </div>
  
  <!-- Карточка 4 -->
  <div class="card">
    <div class="card-icon">📅</div>
    <h3 class="card-title">Индивидуальный подход и бесплатная консультация</h3>
    <p class="card-text">Наши специалисты помогут вам выбрать идеальные десерты для вашего мероприятия и предложат индивидуальные решения, учитывая все ваши пожелания.</p>
  </div>
  
  <!-- Карточка 5 -->
  <div class="card">
    <div class="card-icon">💳</div>
    <h3 class="card-title">Удобные способы оплаты</h3>
    <p class="card-text">Мы предлагаем различные способы оплаты: наличный и безналичный расчет, чтобы сделать процесс заказа максимально комфортным для вас.</p>
  </div>
  <!-- Карточка 6 -->
  <div class="card">
    <div class="card-icon">🕑</div>
    <h3 class="card-title">Скорость выполнения заказов</h3>
    <p class="card-text">Мы гарантируем быструю отгрузку, чтобы вы могли получать свои сладости точно в срок.</p>
  </div>
</div>
        <section class="categories">
            <div class="container">
                <h2>Популярные товары</h2>
                <div class="categories__grid">
                    <a href="services.php?category=cakes" class="category-card">
                        <img src="img/691adfa4d0b77.jpeg" >
                        <h3>Торт «Капучино»</h3>
                    </a>
                     <a href="services.php?category=desserts" class="category-card">
                        <img src="img/691acdd665361.jpg" >
                        <h3>Десерт "Брауни"</h3>
                    </a>
                     <a href="services.php?category=desserts" class="category-card">
                        <img src="img/691acd24c6686.jpg" >
                        <h3>Три шоколада</h3>
                    </a>
                </div>
            </div>
        </section>
           




<!-- Отзывы -->
<section class="section reviews" id="reviews-section">
    <h2 class="section-title">Отзывы наших клиентов</h2>
    
    <!-- Слайдер отзывов -->
    <div class="reviews-container">
        <div id="reviewSlides">
            <!-- Отзывы будут подгружены сюда -->
        </div>
        <?php if (count($reviews) > 1): ?>
        <div class="navigation">
            <button class="slider__btn prev" onclick="changeReview(-1)">❮</button>
            <button class="slider__btn next" onclick="changeReview(1)">❯</button>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Форма добавления отзыва -->
    <div class="review-form-container">
        <h3>Оставьте свой отзыв</h3>
        <form id="reviewForm" action="add_review.php" method="POST" enctype="multipart/form-data">
            <input type="text" id="name" name="name" placeholder="Ваше имя" required>
            <input type="email" id="email" name="email" placeholder="Ваш email" required>
            <textarea id="reviewText" name="reviewText" placeholder="Ваш отзыв" required></textarea>
            <input type="file" id="photo" name="photo" accept="image/*">
            <div class="rating-input">
                <label>Оценка:</label>
                <select id="rating" name="rating" required>
                    <option value="">Выберите рейтинг</option>
                    <option value="5">5 ★</option>
                    <option value="4">4 ★</option>
                    <option value="3">3 ★</option>
                    <option value="2">2 ★</option>
                    <option value="1">1 ★</option>
                </select>
            </div>
            <button type="submit" class="btn btn--primary">Добавить отзыв</button>
        </form>
    </div>
</section>
<!---->
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
        // === Кнопка "В корзину" (оставляем как есть) ===
const button = document.querySelector('.add-to-cart');
if (button) {
    button.addEventListener('click', function() {
        window.location.href = 'checkout.html';
    });
}

// === Загрузка отзывов из БД ===
let currentReview = 0;

function loadReviews() {
    const reviewsWrapper = document.getElementById('reviewSlides');
    if (!reviewsWrapper) return;

    // Покажем "Загрузка..." пока ждём данные
    reviewsWrapper.innerHTML = '<div class="review-slide"><p style="text-align:center; color:#777;">Загрузка отзывов...</p></div>';

    // Запрос к API
    fetch('/saitik/api/reviews.php') // ← важно: путь от корня!
        .then(response => {
            if (!response.ok) throw new Error('Сервер не ответил');
            return response.json();
        })
        .then(reviews => {
            // Очищаем обёртку
            reviewsWrapper.innerHTML = '';

            if (reviews.length === 0) {
                reviewsWrapper.innerHTML = '<div class="review-slide"><p style="text-align:center; color:#777;">Пока нет отзывов.</p></div>';
                return;
            }

            // Рендерим каждый отзыв — ТОЧНО как у вас было!
            reviews.forEach(review => {
                const reviewSlide = document.createElement('div');
                reviewSlide.className = 'review-slide';
                // ★★★★★☆☆☆☆☆
                const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                reviewSlide.innerHTML = `
                    <img src="${review.photo}" alt="${review.name}" class="reviewer-photo">
                    <div class="review-content">
                        <div class="reviewer-name">${review.name}</div>
                        <div class="review-rating">${stars}</div>
                        <div class="review-text">${review.text}</div>
                    </div>
                `;
                reviewsWrapper.appendChild(reviewSlide);
            });

            // Сбрасываем индекс слайдера
            currentReview = 0;
            updateSliderPosition();
        })
        .catch(error => {
            console.error('Ошибка загрузки отзывов:', error);
            reviewsWrapper.innerHTML = `<div class="review-slide"><p style="text-align:center; color:#e74c3c;">Не удалось загрузить отзывы.<br>${error.message}</p></div>`;
        });
}

// Обновляем позицию слайдера
function updateSliderPosition() {
    const reviews = document.querySelectorAll('.review-slide');
    const reviewSlider = document.getElementById('reviewSlides');
    if (reviews.length > 0 && reviewSlider) {
        reviewSlider.style.transform = `translateX(-${currentReview * 100}%)`;
    }
}

// Прокрутка слайдера (оставляем как есть!)
function changeReview(direction) {
    const reviews = document.querySelectorAll('.review-slide');
    if (reviews.length <= 1) return;

    currentReview += direction;

    if (currentReview >= reviews.length) {
        currentReview = 0;
    } else if (currentReview < 0) {
        currentReview = reviews.length - 1;
    }

    updateSliderPosition();
}

// === Обработчик формы ===
const reviewForm = document.getElementById('reviewForm');
if (reviewForm) {
    reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                alert('Спасибо за ваш отзыв!');
                this.reset();
                loadReviews(); // Обновляем список!
            } else {
                return response.text().then(text => { throw new Error(text || 'Ошибка сервера'); });
            }
        })
        .catch(error => {
            console.error('Ошибка отправки:', error);
            alert('Не удалось отправить отзыв: ' + error.message);
        });
    });
}

// === Запуск при загрузке страницы ===
document.addEventListener('DOMContentLoaded', () => {
    loadReviews();
});
    </script>
</body>
</html>