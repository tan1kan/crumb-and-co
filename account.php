<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// === Обработка AJAX-запроса на обновление ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update'])) {
    header('Content-Type: application/json');
    
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    
    $response = ['success' => false, 'message' => ''];

    if (!$name || !$email) {
        $response['message'] = 'Имя и email обязательны.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Неверный формат email.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $response['message'] = 'Этот email уже используется.';
        } else {
            $pdo->prepare("
                UPDATE users 
                SET name = ?, email = ?, phone = ?, city = ? 
                WHERE id = ?
            ")->execute([$name, $email, $phone, $city, $_SESSION['user_id']]);
            
            $_SESSION['user_name'] = $name;
            $response = ['success' => true, 'message' => 'Данные успешно обновлены!'];
        }
    }
    
    echo json_encode($response);
    exit;
}

// === Загрузка данных пользователя ===
$stmt = $pdo->prepare("SELECT name, email, phone, city, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: auth.php');
    exit;
}

// === Заказы ===
$stmt = $pdo->prepare("SELECT id, product_name, price, created_at, status FROM orders WHERE user_name = ? ORDER BY created_at DESC");
$stmt->execute([$user['name']]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Crumb & Co</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-actions {
            margin-top: 15px;
        }
        .btn--edit {
            background: #a89f96;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .edit-form-container {
            display: none;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin: 20px 0;
        }
        .edit-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .edit-form { grid-template-columns: 1fr; }
        }
        .form-group { margin-bottom: 15px; }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-buttons {
            grid-column: span 2;
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn--save {
            background: #a89f96;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn--cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            display: none;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
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
                    <li>
                        <a href="logout.php" class="auth-button">
                            <i class="fas fa-sign-out-alt"></i> Выйти
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="account-page">
        <div class="container">
            <div class="account-content">
                <div class="account-profile">
                    <img src="img/account.jpg" alt="Фото профиля" class="account-profile__img">
                    <h2 class="account-profile__name"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="account-profile__email"><?= htmlspecialchars($user['email']) ?></p>
                    
                    <div class="account-profile__info">
                        <div class="account-info__item">
                            <span class="account-info__label">Телефон:</span>
                            <span class="account-info__value"><?= htmlspecialchars($user['phone'] ?? '+7 (999) 123-45-67') ?></span>
                        </div>
                        <div class="account-info__item">
                            <span class="account-info__label">Город:</span>
                            <span class="account-info__value"><?= htmlspecialchars($user['city'] ?? 'Иркутск') ?></span>
                        </div>
                        <div class="account-info__item">
                            <span class="account-info__label">Дата регистрации:</span>
                            <span class="account-info__value"><?= date('d.m.Y', strtotime($user['created_at'])) ?></span>
                        </div>
                    </div>

                    <!-- Кнопка редактирования -->
                    <div class="profile-actions">
                        <button id="editBtn" class="btn--edit">Редактировать профиль</button>
                    </div>
                </div>

                <!-- Форма редактирования (изначально скрыта) -->
                <div class="edit-form-container" id="editFormContainer">
                    <div class="alert alert-success" id="successMsg"></div>
                    <div class="alert alert-error" id="errorMsg"></div>
                    
                    <form id="editForm" class="edit-form">
                        <input type="hidden" name="ajax_update" value="1">
                        
                        <div class="form-group">
                            <label for="editName">Имя *</label>
                            <input type="text" id="editName" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editEmail">Email *</label>
                            <input type="email" id="editEmail" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editPhone">Телефон</label>
                            <input type="tel" id="editPhone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+7 (___) ___-__-__">
                        </div>
                        
                        <div class="form-group">
                            <label for="editCity">Город</label>
                            <input type="text" id="editCity" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" placeholder="Иркутск">
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="btn--save">Сохранить</button>
                            <button type="button" id="cancelBtn" class="btn--cancel">Отмена</button>
                        </div>
                    </form>
                </div>

                <!-- Заказы -->
                <div class="account-orders">
                    <h2 class="section-title">Мои заказы (<?= count($orders) ?>)</h2>
                    <?php if (empty($orders)): ?>
                        <p style="text-align: center; color: #777; margin-top: 20px;">У вас пока нет заказов.</p>
                    <?php else: ?>
                        <div class="orders-list">
                            <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-card__header">
                                    <span class="order-card__id">Заказ #<?= htmlspecialchars($order['id']) ?></span>
                                    <span class="order-card__date"><?= date('d.m.Y', strtotime($order['created_at'])) ?></span>
                                </div>
                                <span class="order-status <?= $order['status'] === 'completed' ? 'status-completed' : 'status-processing' ?>">
                                    <?= $order['status'] === 'completed' ? 'Выполнен' : 'В обработке' ?>
                                </span>
                                <div class="order-card__details">
                                    <div class="order-item">
                                        <span class="order-item__name"><?= htmlspecialchars($order['product_name']) ?></span>
                                    </div>
                                </div>
                                <div class="order-card__total"><?= number_format($order['price'], 0, '', ' ') ?> ₽</div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('editBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formContainer = document.getElementById('editFormContainer');
            const editForm = document.getElementById('editForm');
            const successMsg = document.getElementById('successMsg');
            const errorMsg = document.getElementById('errorMsg');

            // Показать форму
            editBtn.addEventListener('click', function() {
                formContainer.style.display = 'block';
                successMsg.style.display = 'none';
                errorMsg.style.display = 'none';
            });

            // Скрыть форму
            cancelBtn.addEventListener('click', function() {
                formContainer.style.display = 'none';
            });

            // Отправка формы через AJAX
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('account.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successMsg.textContent = data.message;
                        successMsg.style.display = 'block';
                        errorMsg.style.display = 'none';
                        
                        // Обновляем данные на странице
                        document.querySelector('.account-profile__name').textContent = document.getElementById('editName').value;
                        document.querySelector('.account-profile__email').textContent = document.getElementById('editEmail').value;
                        const phoneVal = document.getElementById('editPhone').value || '+7 (999) 123-45-67';
                        document.querySelectorAll('.account-info__value')[0].textContent = phoneVal;
                        const cityVal = document.getElementById('editCity').value || 'Иркутск';
                        document.querySelectorAll('.account-info__value')[1].textContent = cityVal;

                        // Через 2 секунды скрываем форму
                        setTimeout(() => {
                            formContainer.style.display = 'none';
                        }, 1500);
                    } else {
                        errorMsg.textContent = data.message;
                        errorMsg.style.display = 'block';
                        successMsg.style.display = 'none';
                    }
                })
                .catch(() => {
                    errorMsg.textContent = 'Ошибка при сохранении данных.';
                    errorMsg.style.display = 'block';
                    successMsg.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>