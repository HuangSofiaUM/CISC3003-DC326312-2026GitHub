/**
 * ==========================================
 * Scenario B - Client-side Validation
 * ==========================================
 */
(function () {
    const form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', (event) => {
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const subject = document.getElementById('subject');
        const message = document.getElementById('message');

        let valid = true;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!name.value || name.value.trim().length < 2) valid = false;
        if (!emailRegex.test(email.value.trim())) valid = false;
        if (!subject.value || subject.value.trim().length < 3) valid = false;
        if (!message.value || message.value.trim().length < 10) valid = false;

        if (!valid) {
            event.preventDefault();
            alert('Please complete all fields with valid values.');
        }
    });
})();
