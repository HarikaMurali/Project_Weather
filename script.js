document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.weather-form');
    const input = form.querySelector('input[name="city"]');

    // Clear input after form submission (optional)
    form.addEventListener('submit', function () {
        setTimeout(() => {
            input.value = ''; // Clear input after submission
        }, 500);
    });

    // Optional animation for the overlay
    const overlay = document.querySelector('.weather-overlay');
    if (overlay) {
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 1s ease-in-out';
        setTimeout(() => {
            overlay.style.opacity = '1'; // Fade-in animation
        }, 200);
    }
});