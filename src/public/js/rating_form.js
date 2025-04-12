document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.rating-form__star-wrap .rating-form__star');
    const ratingInput = document.getElementById('ratingValue');
    let selectedRating = 0;

    stars.forEach((star, index) => {
        const value = index + 1;

        star.addEventListener('mouseover', () => {
            stars.forEach((s, i) => {
            s.classList.toggle('hover', i < value);
            });
        });

        star.addEventListener('mouseout', () => {
            stars.forEach((s, i) => {
            s.classList.remove('hover');
            });
        });

        star.addEventListener('click', () => {
        selectedRating = value;
        ratingInput.value = value;

        stars.forEach((s, i) => {
            s.classList.toggle('selected', i < value);
            });
        });
    });
});