const app = document.getElementById('perfil-app');
const tieneErrores = app.dataset.tieneErrores === 'true';

let contadorDirecciones = 0;

function activarEdicion() {
    document.getElementById('modo-vista').classList.add('hidden');
    document.getElementById('modo-edicion').classList.remove('hidden');
}

function cancelarEdicion() {
    document.getElementById('modo-edicion').classList.add('hidden');
    document.getElementById('modo-vista').classList.remove('hidden');
    
    // Al cancelar, limpiamos los cambios temporales y restauramos las direcciones originales
    restaurarDireccionesOriginales();
}

if (tieneErrores) {
    activarEdicion();
}

// ==========================================
// LÓGICA COMPLEMENTARIA PARA DIRECCIONES DINAMICAS
// ==========================================

function agregarDireccion(data = null) {
    const contenedor = document.getElementById('contenedor-direcciones');
    const template = document.getElementById('template-direccion').innerHTML;

    const datosHTML = {
        index: contadorDirecciones,
        numero: contadorDirecciones + 1,
        id: data ? data.id : '',
        alias: data ? data.alias : '',
        street: data ? data.street : '',
        postal_code: data ? data.postal_code : '',
        checked: data && data.is_default ? 'checked' : ''
    };

    let htmlRenderizado = template
        .replace(/{index}/g, datosHTML.index)
        .replace(/{numero}/g, datosHTML.numero)
        .replace(/{id}/g, datosHTML.id)
        .replace(/{alias}/g, datosHTML.alias)
        .replace(/{street}/g, datosHTML.street)
        .replace(/{postal_code}/g, datosHTML.postal_code)
        .replace(/{checked}/g, datosHTML.checked);

    contenedor.insertAdjacentHTML('beforeend', htmlRenderizado);

    // Capturamos la tarjeta que acabamos de insertar
    const cardActual = contenedor.querySelector(`.posicion-direccion[data-index="${contadorDirecciones}"]`);
    const selectProvincia = cardActual.querySelector('.select-provincia');
    const selectCiudad = cardActual.querySelector('.select-ciudad');

    // SI LA DIRECCIÓN YA EXISTÍA EN LA BASE DE DATOS:
    if (data && data.city && data.city.province_id) {
        // 1. Seleccionar la provincia guardada
        selectProvincia.value = data.city.province_id;
        
        // 2. Cargar las ciudades de esa provincia y preseleccionar la ciudad guardada
        fetch(`/api/provincias/${data.city.province_id}/ciudades`)
            .then(response => response.json())
            .then(ciudades => {
                selectCiudad.innerHTML = '<option value="" disabled>Selecciona Ciudad...</option>';
                ciudades.forEach(ciudad => {
                    const option = document.createElement('option');
                    option.value = ciudad.id;
                    option.text = ciudad.name;
                    if (ciudad.id === data.city_id) {
                        option.selected = true;
                    }
                    selectCiudad.appendChild(option);
                });
                selectCiudad.disabled = false;
            });
    }

    contadorDirecciones++;
}

// FUNCIÓN QUE SE ACTIVA CUANDO EL USUARIO CAMBIA LA PROVINCIA DE FORMA MANUAL
function cargarCiudadesDinamico(selectProvincia) {
    const card = selectProvincia.closest('.posicion-direccion');
    const selectCiudad = card.querySelector('.select-ciudad');
    const provinciaId = selectProvincia.value;

    if (!provinciaId) return;

    selectCiudad.disabled = true;
    selectCiudad.innerHTML = '<option value="" disabled selected>Cargando ciudades...</option>';

    // Hacemos la consulta asíncrona al método del controlador que creamos
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
            console.error("Error cargando ciudades:", error);
            selectCiudad.innerHTML = '<option value="" disabled selected>Error al cargar</option>';
        });
}

function eliminarDireccionElemento(boton) {
    const card = boton.closest('.posicion-direccion');
    card.remove();
    reindexarDirecciones();
}

function reindexarDirecciones() {
    const contenedor = document.getElementById('contenedor-direcciones');
    const cards = contenedor.querySelectorAll('.posicion-direccion');
    contadorDirecciones = 0;

    cards.forEach((card, idx) => {
        card.setAttribute('data-index', idx);
        card.querySelector('.fw-bold').innerText = `Dirección #${idx + 1}`;

        card.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const nuevoName = name.replace(/addresses\[\d+\]/, `addresses[${idx}]`);
                input.setAttribute('name', nuevoName);
            }
            
            if (input.type === 'checkbox') {
                input.setAttribute('id', `def-${idx}`);
                const label = card.querySelector('label');
                if (label) label.setAttribute('for', `def-${idx}`);
            }
        });
        contadorDirecciones++;
    });
}

function restaurarDireccionesOriginales() {
    const contenedor = document.getElementById('contenedor-direcciones');
    if (!contenedor) return;

    contenedor.innerHTML = '';
    contadorDirecciones = 0;

    if (typeof direccionesIniciales !== 'undefined' && direccionesIniciales.length > 0) {
        direccionesIniciales.forEach(dir => agregarDireccion(dir));
    }
}

document.addEventListener("DOMContentLoaded", function() {
    restaurarDireccionesOriginales();
});

// Escucha cuando se hace click en cualquier checkbox de predeterminado
document.addEventListener('change', function(e) {
    if (e.target && e.target.classList.contains('form-check-input') && e.target.name.includes('[is_default]')) {
        // Si el checkbox fue marcado (true)
        if (e.target.checked) {
            const contenedor = document.getElementById('contenedor-direcciones');
            const todosLosCheckboxes = contenedor.querySelectorAll('input[type="checkbox"]');
            
            // Destildamos absolutamente todos los DEMÁS checkboxes de la sección
            todosLosCheckboxes.forEach(cb => {
                if (cb !== e.target) {
                    cb.checked = false;
                }
            });
        }
    }
});

// ─── VALIDACIÓN FRONTEND DEL FORM DE PERFIL ───────────────────────────────

(function () {
    const form = document.querySelector('form[action*="panel-usuario"]');
    if (!form) return;

    // ── Helpers ──────────────────────────────────────────────────────────────
    function setInvalid(input, msg) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');

        // Busca el invalid-feedback como hermano directo O dentro del mismo contenedor padre
        let fb = input.nextElementSibling;
        if (!fb || !fb.classList.contains('invalid-feedback')) {
            fb = input.closest('div')?.querySelector('.invalid-feedback');
        }
        if (fb) fb.textContent = msg;
    }

    function setValid(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');

        // Limpia el mensaje anterior para que no quede texto fantasma
        let fb = input.nextElementSibling;
        if (!fb || !fb.classList.contains('invalid-feedback')) {
            fb = input.closest('div')?.querySelector('.invalid-feedback');
        }
        if (fb) fb.textContent = '';
    }

    // ── Reglas de campos fijos ────────────────────────────────────────────────
    const camposFijos = [
        {
            name: 'first_name',
            tests: [
                { fn: v => v.trim() !== '',            msg: 'El nombre es obligatorio.' },
                { fn: v => v.trim().length >= 2,       msg: 'Mínimo 2 caracteres.' },
                { fn: v => /^[\p{L}\s\-]+$/u.test(v), msg: 'Solo puede contener letras.' },
            ]
        },
        {
            name: 'last_name',
            tests: [
                { fn: v => v.trim() !== '',            msg: 'El apellido es obligatorio.' },
                { fn: v => v.trim().length >= 2,       msg: 'Mínimo 2 caracteres.' },
                { fn: v => /^[\p{L}\s\-]+$/u.test(v), msg: 'Solo puede contener letras.' },
            ]
        },
        {
            name: 'email',
            tests: [
                { fn: v => v.trim() !== '',                        msg: 'El email es obligatorio.' },
                { fn: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),  msg: 'Ingresá un email válido.' },
            ]
        },
        {
            name: 'phone',
            tests: [
                // El teléfono es opcional, solo validamos formato si tiene valor
                { fn: v => v === '' || /^[\d\s\+\-\(\)]+$/.test(v), msg: 'Solo números, espacios y +, -, (, ).' },
            ]
        },
    ];

    function validarCampo(input) {
        const rules = camposFijos.find(c => c.name === input.name);
        if (!rules) return true;
        for (const { fn, msg } of rules.tests) {
            if (!fn(input.value)) { setInvalid(input, msg); return false; }
        }
        setValid(input);
        return true;
    }

    // ── Validación de contraseña ──────────────────────────────────────────────
    function validarPassword() {
        const pass    = form.querySelector('[name="password"]');
        const confirm = form.querySelector('[name="password_confirmation"]');

        // Si confirmation tiene valor pero password no, es un error
        if (!pass.value && confirm.value) {
            setInvalid(pass, 'Completá la nueva contraseña o dejá ambos campos vacíos.');
            setInvalid(confirm, 'Completá la nueva contraseña o dejá ambos campos vacíos.');
            return false;
        }

        // Si ambos están vacíos, no quiere cambiar la contraseña — está bien
        if (!pass.value && !confirm.value) {
            pass.classList.remove('is-invalid', 'is-valid');
            confirm.classList.remove('is-invalid', 'is-valid');
            return true;
        }

        // A partir de acá, pass.value tiene algo
        let valido = true;

        if (pass.value.length < 8) {
            setInvalid(pass, 'La contraseña debe tener al menos 8 caracteres.');
            valido = false;
        } else if (!/[a-zA-Z]/.test(pass.value) || !/[0-9]/.test(pass.value)) {
            setInvalid(pass, 'Debe contener al menos una letra y un número.');
            valido = false;
        } else {
            setValid(pass);
        }

        if (pass.value !== confirm.value) {
            setInvalid(confirm, 'Las contraseñas no coinciden.');
            valido = false;
        } else if (confirm.value) {
            setValid(confirm);
        }

        return valido;
    }

    // ── Validación de direcciones dinámicas ───────────────────────────────────
    function validarDirecciones() {
        const tarjetas = document.querySelectorAll('.posicion-direccion');
        let valido = true;

        tarjetas.forEach(tarjeta => {
            const alias  = tarjeta.querySelector('[name*="[alias]"]');
            const street = tarjeta.querySelector('[name*="[street]"]');
            const cp     = tarjeta.querySelector('[name*="[postal_code]"]');
            const ciudad = tarjeta.querySelector('.select-ciudad');
            const selectProvincia = tarjeta.querySelector('.select-provincia');

            if (!alias.value.trim() || alias.value.trim().length < 2) {
                setInvalid(alias, 'El alias debe tener al menos 2 caracteres.');
                valido = false;
            } else setValid(alias);

            if (!street.value.trim() || street.value.trim().length < 5) {
                setInvalid(street, 'Ingresá una dirección completa.');
                valido = false;
            } else setValid(street);

            if (!cp.value.trim() || !/^\d{4,8}$/.test(cp.value.trim())) {
                setInvalid(cp, 'El código postal solo puede contener números (4 a 8 dígitos).');
                valido = false;
            } else setValid(cp);

            if (!selectProvincia.value) {
                selectProvincia.classList.add('is-invalid');
                valido = false;
            } else if (!ciudad.value) {
                selectProvincia.classList.remove('is-invalid');
                ciudad.classList.add('is-invalid');
                valido = false;
            } else {
                selectProvincia.classList.remove('is-invalid');
                ciudad.classList.remove('is-invalid');
            }
        });

        return valido;
    }

    // ── Listeners en tiempo real (blur) ───────────────────────────────────────
    camposFijos.forEach(({ name }) => {
        const input = form.querySelector(`[name="${name}"]`);
        if (!input) return;
        input.addEventListener('blur', () => validarCampo(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) validarCampo(input);
        });
    });

    // Contraseña: validar confirmación en tiempo real
    const passConfirm = form.querySelector('[name="password_confirmation"]');
    if (passConfirm) {
        passConfirm.addEventListener('input', () => validarPassword());
    }

    // ── Submit ────────────────────────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        let formValido = true;

        camposFijos.forEach(({ name }) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input && !validarCampo(input)) formValido = false;
        });

        if (!validarPassword())     formValido = false;
        if (!validarDirecciones())  formValido = false;

        if (!formValido) {
            e.preventDefault();
            e.stopPropagation();
            const primerError = form.querySelector('.is-invalid');
            if (primerError) {
                primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                primerError.focus();
            }
        }
    });
})();