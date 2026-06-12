document.addEventListener('DOMContentLoaded', function() {
    const setupSearch = (inputId, suggestionsId) => {
        const input = document.querySelector(inputId);
        const suggestions = document.querySelector(suggestionsId);
        let timeout = null;

        if (!input || !suggestions) return;

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value.trim();

            if (query.length < 3) {
                suggestions.classList.add('d-none');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`/search?query=${query}`, {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(response => response.text())
                .then(html => {
                    suggestions.innerHTML = html;
                    suggestions.classList.remove('d-none');
                });
            }, 300);
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add('d-none');
            }
        });
    };

    // Activamos ambos buscadores
    setupSearch('#main-search', '#search-suggestions');     // Escritorio
    setupSearch('#mobile-search', '#mobile-search-suggestions'); // Móvil
});
