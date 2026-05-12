/**
 * ==========================================
 * Scenario C - Browser Validation + Ajax
 * ==========================================
 */
(function () {
    const registerForm = document.getElementById('registerForm');
    if (!registerForm) return;

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const resultEl = document.getElementById('emailCheckResult');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // C.06 Ajax email validation
    emailInput.addEventListener('blur', async () => {
        const email = emailInput.value.trim();
        if (!emailRegex.test(email)) {
            resultEl.textContent = 'Invalid email format';
            resultEl.style.color = '#b91c1c';
            return;
        }
        try {
            const response = await fetch('ajax_check_email.php?email=' + encodeURIComponent(email));
            const data = await response.json();
            resultEl.textContent = data.message || '';
            resultEl.style.color = data.available ? '#166534' : '#b91c1c';
        } catch (error) {
            resultEl.textContent = 'Email check failed';
            resultEl.style.color = '#b91c1c';
        }
    });

    // C.05 Browser-side validation
    registerForm.addEventListener('submit', (event) => {
        const email = emailInput.value.trim();
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (!emailRegex.test(email) || password.length < 8 || password !== confirm) {
            event.preventDefault();
            alert('Please fix validation errors before submitting.');
        }
    });
})();
