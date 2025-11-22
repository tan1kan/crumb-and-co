document.addEventListener('DOMContentLoaded', function() {
    // Переключение между вкладками входа и регистрации
    const authTabs = document.querySelectorAll('.auth-tab');
    const authForms = document.querySelectorAll('.auth-form');
    
    authTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Убираем активный класс со всех вкладок и форм
            authTabs.forEach(t => t.classList.remove('active'));
            authForms.forEach(f => f.classList.remove('active'));
            
            // Добавляем активный класс к текущей вкладке и форме
            this.classList.add('active');
            document.getElementById(`${tabName}-form`).classList.add('active');
        });
    });
    
    // Переключение видимости пароля
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.parentElement.querySelector('input');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🔒';
        });
    });
    
    // Валидация формы регистрации в реальном времени
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        const passwordInput = document.getElementById('register-password');
        const confirmInput = document.getElementById('register-confirm');
        
        // Валидация пароля при вводе
        passwordInput.addEventListener('input', function() {
            validatePassword(this.value);
            validatePasswordMatch();
        });
        
        confirmInput.addEventListener('input', validatePasswordMatch);
        
        // Отправка формы регистрации
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('register-name').value;
            const email = document.getElementById('register-email').value;
            const phone = document.getElementById('register-phone').value;
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            // Проверка валидности формы
            if (!validateForm()) {
                return false;
            }
            
            // В реальном приложении здесь будет отправка формы на сервер
            console.log('Регистрация:', { name, email, phone, password });
            alert('Регистрация успешна!');
            window.location.href = 'index.html';
        });
    }
    
    // Валидация формы входа
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            
            // В реальном приложении здесь будет отправка формы на сервер
            console.log('Вход:', { email, password });
            alert('Вход выполнен успешно!');
            window.location.href = 'index.html';
        });
    }
});

// Функции валидации
function validatePassword(password) {
    const passwordGroup = document.getElementById('register-password').closest('.form-group');
    const errorElement = passwordGroup.querySelector('.error-message') || createErrorMessage(passwordGroup);
    
    if (password.length < 6) {
        showError(passwordGroup, 'Пароль должен содержать не менее 6 символов');
        return false;
    } else {
        hideError(passwordGroup);
        return true;
    }
}

function validatePasswordMatch() {
    const password = document.getElementById('register-password').value;
    const confirmPassword = document.getElementById('register-confirm').value;
    const confirmGroup = document.getElementById('register-confirm').closest('.form-group');
    const errorElement = confirmGroup.querySelector('.error-message') || createErrorMessage(confirmGroup);
    
    if (password !== confirmPassword) {
        showError(confirmGroup, 'Пароли не совпадают');
        return false;
    } else {
        hideError(confirmGroup);
        return true;
    }
}

function validateForm() {
    const isPasswordValid = validatePassword(document.getElementById('register-password').value);
    const isPasswordMatch = validatePasswordMatch();
    
    return isPasswordValid && isPasswordMatch;
}

function createErrorMessage(formGroup) {
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message';
    formGroup.appendChild(errorElement);
    return errorElement;
}

function showError(formGroup, message) {
    formGroup.classList.add('error');
    const errorElement = formGroup.querySelector('.error-message');
    if (errorElement) {
        errorElement.textContent = message;
    }
}

function hideError(formGroup) {
    formGroup.classList.remove('error');
}

// Добавьте эту функцию в auth.js
function initPasswordStrength() {
    const passwordInput = document.getElementById('register-password');
    if (!passwordInput) return;
    
    const strengthIndicator = document.createElement('div');
    strengthIndicator.className = 'password-strength';
    strengthIndicator.innerHTML = `
        <div class="strength-bar"></div>
        <div class="strength-text"></div>
    `;
    passwordInput.parentElement.appendChild(strengthIndicator);
    
    passwordInput.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        updateStrengthIndicator(strengthIndicator, strength);
    });
}

function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 6) strength += 1;
    if (password.length >= 8) strength += 1;
    if (/[a-z]/.test(password)) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/[0-9]/.test(password)) strength += 1;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 1;
    
    return Math.min(strength, 5);
}

function updateStrengthIndicator(indicator, strength) {
    const bar = indicator.querySelector('.strength-bar');
    const text = indicator.querySelector('.strength-text');
    
    const colors = ['#dc3545', '#ffc107', '#ffc107', '#17a2b8', '#28a745'];
    const texts = ['Очень слабый', 'Слабый', 'Средний', 'Хороший', 'Отличный'];
    
    bar.style.width = `${(strength / 5) * 100}%`;
    bar.style.backgroundColor = colors[strength - 1] || '#dc3545';
    text.textContent = texts[strength - 1] || 'Очень слабый';
}