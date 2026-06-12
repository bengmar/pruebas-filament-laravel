document.addEventListener('DOMContentLoaded', function () {
    const btn = document.querySelector('#togglePassword');
    const input = document.querySelector('#passwordLogin');
    const icon = document.querySelector('#eyeIcon');

    if (btn) {
        btn.addEventListener('click', function () {
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    }
});
