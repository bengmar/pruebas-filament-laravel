function evaluarDireccionNueva(select) {
    const bloque = document.getElementById('bloque-nueva-direccion');
    const inputs = bloque.querySelectorAll('input, select');

    if (select.value === 'nueva_direccion') {
        // Mostrar bloque y habilitar inputs para que viajen en el request
        bloque.classList.remove('d-none');
        inputs.forEach(input => {
            if(input.id !== 'select-ciudad-checkout') input.disabled = false;
            input.required = true;
        });
    } else {
        // Ocultar bloque y deshabilitar inputs (así Laravel no valida campos vacíos ocultos)
        bloque.classList.add('d-none');
        inputs.forEach(input => {
            input.disabled = true;
            input.required = false;
        });
    }
}

function cargarCiudadesCheckout(selectProvincia) {
    const selectCiudad = document.getElementById('select-ciudad-checkout');
    const provinciaId = selectProvincia.value;

    if (!provinciaId) return;

    selectCiudad.disabled = true;
    selectCiudad.innerHTML = '<option value="" disabled selected>Cargando ciudades...</option>';

    // Reutilizamos la misma ruta tipo API que creamos anteriormente en web.php
    fetch(`/api/provincias/${provinciaId}/ciudades`)
        .then(response => response.json())
        .then(ciudades => {
            selectCiudad.innerHTML = '<option value="" disabled selected>Selecciona Ciudad...</option>';
            ciudades.forEach(ciudad => {
                const option = document.createElement('option');
                option.value = ciudad.id;
                option.text = ciudad.name;
                selectCiudad.appendChild(option);
            });
            selectCiudad.disabled = false;
        })
        .catch(error => {
            console.error("Error:", error);
            selectCiudad.innerHTML = '<option value="" disabled selected>Error al cargar</option>';
        });
}

// ─── VALIDACIÓN FRONTEND DEL FORM DE CHECKOUT ─────────────────────────────

(function () {
    const form = document.getElementById('form-checkout');
    if (!form) return;

    // Reglas por campo: { selector, tests: [{ fn, message }] }
    const camposTexto = [
        {
            name: 'customer_name',
            tests: [
                { fn: v => v.trim() !== '',           msg: 'El nombre es obligatorio.' },
                { fn: v => v.trim().length >= 2,      msg: 'El nombre debe tener al menos 2 caracteres.' },
                { fn: v => /^[\p{L}\s\-]+$/u.test(v), msg: 'El nombre solo puede contener letras.' },
            ]
        },
        {
            name: 'customer_lastname',
            tests: [
                { fn: v => v.trim() !== '',           msg: 'El apellido es obligatorio.' },
                { fn: v => v.trim().length >= 2,      msg: 'El apellido debe tener al menos 2 caracteres.' },
                { fn: v => /^[\p{L}\s\-]+$/u.test(v), msg: 'El apellido solo puede contener letras.' },
            ]
        },
        {
            name: 'customer_email',
            tests: [
                { fn: v => v.trim() !== '',                          msg: 'El email es obligatorio.' },
                { fn: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),    msg: 'Ingresá un email válido.' },
            ]
        },
    ];

    // Valida un input y muestra/oculta su invalid-feedback
    function validarCampo(input) {
        const rules = camposTexto.find(c => c.name === input.name);
        if (!rules) return true;

        for (const { fn, msg } of rules.tests) {
            if (!fn(input.value)) {
                setInvalid(input, msg);
                return false;
            }
        }
        setValid(input);
        return true;
    }

    function setInvalid(input, msg) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        const feedback = input.nextElementSibling;
        if (feedback?.classList.contains('invalid-feedback')) {
            feedback.textContent = msg;
        }
    }

    function setValid(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    }

    // Validación del bloque de nueva dirección (solo si está visible)
    function validarNuevaDireccion() {
        const bloque = document.getElementById('bloque-nueva-direccion');
        if (bloque.classList.contains('d-none')) return true;

        let valido = true;

        const calle = document.getElementById('input-nueva-calle');
        const cp    = document.getElementById('input-nuevo-cp');
        const alias = document.getElementById('input-nuevo-alias');
        const city  = document.getElementById('hidden-city-id');

        if (!calle.value.trim() || calle.value.trim().length < 5) {
            setInvalid(calle, 'Ingresá una dirección completa (mínimo 5 caracteres).');
            valido = false;
        } else setValid(calle);

        if (!cp.value.trim() || !/^\d{4,8}$/.test(cp.value.trim())) {
            setInvalid(cp, 'El código postal solo puede contener números (4 a 8 dígitos).');
            valido = false;
        } else setValid(cp);

        if (!alias.value.trim() || alias.value.trim().length < 2) {
            setInvalid(alias, 'El alias debe tener al menos 2 caracteres.');
            valido = false;
        } else setValid(alias);

        // Ciudad viene del hidden input; validamos el select visible
        const selectCiudad = document.getElementById('select-ciudad-checkout');
        if (!city.value) {
            selectCiudad.classList.add('is-invalid');
            valido = false;
        } else {
            selectCiudad.classList.remove('is-invalid');
        }

        return valido;
    }

    // Validación del método de pago
    function validarMetodoPago() {
        const seleccionado = form.querySelector('input[name="paymentMethod"]:checked');
        const errorDiv = document.getElementById('payment-error');

        if (!seleccionado) {
            errorDiv.classList.remove('d-none');
            // Borde rojo en los contenedores para refuerzo visual
            form.querySelectorAll('input[name="paymentMethod"]').forEach(r => r.classList.add('is-invalid'));
            return false;
        }

        errorDiv.classList.add('d-none');
        form.querySelectorAll('input[name="paymentMethod"]').forEach(r => r.classList.remove('is-invalid'));
        return true;
    }

    // Validación en tiempo real (blur)
    camposTexto.forEach(({ name }) => {
        const input = form.querySelector(`[name="${name}"]`);
        if (!input) return;
        input.addEventListener('blur', () => validarCampo(input));
        input.addEventListener('input', () => {
            // Solo limpiar el error mientras escribe, no disparar nueva validación
            if (input.classList.contains('is-invalid')) validarCampo(input);
        });
    });

    // Submit: validar todo antes de enviar
    form.addEventListener('submit', function (e) {
        let formValido = true;

        camposTexto.forEach(({ name }) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input && !validarCampo(input)) formValido = false;
        });

        if (!validarNuevaDireccion()) formValido = false;
        if (!validarMetodoPago())    formValido = false;

        if (!formValido) {
            e.preventDefault();
            e.stopPropagation();

            // Scroll al primer campo con error
            const primerError = form.querySelector('.is-invalid');
            if (primerError) {
                primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                primerError.focus();
            }
        }
    });

    form.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', () => validarMetodoPago());
    });
})();