
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