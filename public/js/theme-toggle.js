const themeToggleBtn = document.getElementById('theme-toggle');
const sunIcon = document.getElementById('theme-toggle-light-icon');
const moonIcon = document.getElementById('theme-toggle-dark-icon');

function updateTheme(theme) {
    if (theme === 'light') {
        document.documentElement.classList.add('light');
        document.documentElement.setAttribute('data-bs-theme', 'light');
        moonIcon.classList.remove('hidden');
        sunIcon.classList.add('hidden');
    } else {
        document.documentElement.classList.remove('light');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        sunIcon.classList.remove('hidden');
        moonIcon.classList.add('hidden');
    }
    localStorage.setItem('theme', theme);
}

themeToggleBtn.addEventListener('click', function() {
    // Si tiene la clase light, el nuevo tema será dark
    const newTheme = document.documentElement.classList.contains('light') ? 'dark' : 'light';
    updateTheme(newTheme);
});

// Al cargar la página (Sincronizar con lo que hizo el script anti-parpadeo)
const savedTheme = localStorage.getItem('theme') ||
                  (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

updateTheme(savedTheme);
