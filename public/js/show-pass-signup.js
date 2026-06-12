document.addEventListener('DOMContentLoaded', function () {
    // Función genérica para evitar repetir código
    const setupPasswordToggle = (buttonId, inputId, iconId) => {
        const btn = document.querySelector(buttonId);
        const input = document.querySelector(inputId);
        const icon = document.querySelector(iconId);

        if (btn && input && icon) {
            btn.addEventListener('click', function () {
                const isPass = input.type === 'password';
                input.type = isPass ? 'text' : 'password';
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
    };

    // Configurar el primer campo (Password)
    setupPasswordToggle('#togglePasswordSignup', '#passwordSignup', '#eyeIconSignup');

    // Configurar el segundo campo (Confirm Password)
    setupPasswordToggle('#togglePasswordConfirm', '#passwordConfirmSignup', '#eyeIconConfirm');
});
