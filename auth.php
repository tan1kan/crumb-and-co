<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// === 🔑 СПЕЦИАЛЬНЫЕ ДАННЫЕ ДЛЯ АДМИНА ===
$ADMIN_EMAIL = 'admin@gmail.com';       // ← ОБЯЗАТЕЛЬНО ЗАМЕНИТЕ!
$ADMIN_PASSWORD = 'admin1';    // ← ОБЯЗАТЕЛЬНО ЗАМЕНИТЕ!

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $error = 'Заполните все поля.';
        } else {
            // 🔐 Проверка: если введены админские данные
            if ($email === $ADMIN_EMAIL && $password === $ADMIN_PASSWORD) {
                $_SESSION['is_admin'] = true;
                header('Location: admin/');
                exit;
            }

            // 👤 Обычная авторизация через БД
            $pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: account.php');
                exit;
            } else {
                $error = 'Неверный email или пароль.';
            }
        }

    } elseif (isset($_POST['register'])) {
        file_put_contents('debug.txt', print_r($_POST, true));
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!$name || !$email || !$phone || !$password || !$confirm_password) {
            $error = 'Заполните все поля.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Неверный формат email.';
        } elseif ($password !== $confirm_password) {
            $error = 'Пароли не совпадают.';
        } elseif (strlen($password) < 6) {
            $error = 'Пароль должен быть не короче 6 символов.';
        } else {
            $pdo = new PDO("mysql:host=localhost;dbname=crumb_and_co;charset=utf8", 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Пользователь с таким email уже существует.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)")
                    ->execute([$name, $email, $hash]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                header('Location: account.php');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход и регистрация | Crumb & Co</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        <style>
    .error-message {
        color: #e74c3c;
        margin-bottom: 15px;
        padding: 10px;
        background: #ffecec;
        border-radius: 4px;
    }
    .auth-tabs {
        display: flex;
        margin-bottom: 20px;
    }
    .auth-tab {
        padding: 15px 20px;
        background: #f0f0f0;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }
    .auth-tab.active {
        background: #a89f96;
        color: white;
    }
    .auth-form {
        display: none;
    }
    .auth-form.active {
        display: block;
    }
    .password-container {
        position: relative;
    }
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
    }
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

.footer__contacts i {
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
                </ul>
            </nav>
        </div>
    </header>
<?php if (!empty($error)): ?>
    <div style="color: #e74c3c; background: #fdf2f2; padding: 12px; margin-bottom: 20px; border-radius: 4px; border-left: 4px solid #e74c3c;">
        <strong>Ошибка:</strong> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
    <main class="auth-page">
        <div class="container">
            <div class="auth-container">
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Вход</button>
                    <button class="auth-tab" data-tab="register">Регистрация</button>
                </div>
                
                <div class="auth-content">
                    <!-- Форма входа -->
                    <form id="login-form" class="auth-form active" method="POST">
                        <input type="hidden" name="login" value="1">
                        <h2>Вход в аккаунт</h2>
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input type="email" id="login-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Пароль</label>
                            <div class="password-container">
                                <input type="password" id="login-password" name="password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('login-password')">👁️</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary">Войти</button>
                    </form>
                    
                    <!-- Форма регистрации -->
                    <form id="register-form" class="auth-form" method="POST">
                        <input type="hidden" name="register" value="1">
                        <h2>Регистрация</h2>
                        <div class="form-group">
                            <label for="register-name">Имя</label>
                            <input type="text" id="register-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" id="register-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="register-phone">Телефон</label>
                            <input type="tel" id="register-phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Пароль</label>
                            <div class="password-container">
                                <input type="password" id="register-password" name="password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('register-password')">👁️</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="register-confirm">Подтвердите пароль</label>
                            <div class="password-container">
                                <input type="password" id="register-confirm" name="confirm_password" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('register-confirm')">👁️</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary">Зарегистрироваться</button>
                    </form>
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
        document.querySelectorAll('.auth-tab').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.auth-tab').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
                button.classList.add('active');
                const tab = button.dataset.tab;
                document.getElementById(`${tab}-form`).classList.add('active');
            });
        });

        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>