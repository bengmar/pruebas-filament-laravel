document.addEventListener('DOMContentLoaded', function () {

    // 1. CONTROL DE ALERTAS FLASH EXISTENTES
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.classList.remove('animate__fadeInUp');
            flashMessage.classList.add('animate__fadeOutDown');
            flashMessage.addEventListener('animationend', function () {
                flashMessage.remove();
            });
        }, 3000);
    }

    // 2. VACIAR CARRITO CON SWEETALERT
    document.addEventListener('click', function (event) {
        const btnVaciar = event.target.closest('#btn-vaciar-carrito');
        if (btnVaciar) {
            const formVaciar = document.getElementById('form-vaciar-carrito');
            if (formVaciar) {
                event.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminarán todos los productos de tu carrito.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, vaciar',
                    cancelButtonText: 'Cancelar',
                    background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#212529' : '#ffffff',
                    color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#212529'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formVaciar.submit();
                    }
                });
            }
        }
    });

    // 3. AÑADIR AL CARRITO ASÍNCRONO
    document.addEventListener('submit', function (e) {
        if (e.target && e.target.classList.contains('form-agregar-carrito')) {
            e.preventDefault();

            const form = e.target;
            const url = form.getAttribute('action');
            const formData = new FormData(form);
            //const submitBtn = form.querySelector('.btn-agregar');
            const submitBtn = e.submitter;

            if (submitBtn && submitBtn.name) {
                formData.append(submitBtn.name, submitBtn.value);
            }

            if (submitBtn) submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const dataFields = {};
            formData.forEach((value, key) => { dataFields[key] = value });

            const temaOscuro = document.documentElement.getAttribute('data-bs-theme') === 'dark';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(dataFields)
            })
                .then(async response => {
                    // SOLUCIÓN AL INVITADO: Si devuelve 401 es porque no inició sesión
                    if (response.status === 401) {
                        throw new Error('AUTH_REQUIRED');
                    }

                    if (response.status === 403) {
                        throw new Error('ADMIN_RESTRICTED');
                    }

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.error || 'No se pudo agregar al carrito');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Actualizamos los contadores visuales antes de congelar con la alerta
                        const subtotalDisplay = document.querySelector('.cart-subtotal-display');
                        const totalDisplay = document.querySelector('.cart-total-display');
                        if (subtotalDisplay) subtotalDisplay.textContent = data.formatted_subtotal;
                        if (totalDisplay) totalDisplay.textContent = data.formatted_subtotal;

                        let globalBadge = document.querySelector('.global-cart-badge');
                        if (globalBadge) {
                            globalBadge.textContent = data.total_items_count;
                        }

                        // Lanzamos SweetAlert2 para dar un feedback limpio usando tus estilos
                        if (dataFields['action'] === 'buy_now') {

                            // Feedback visual limpio de redirección
                            Swal.fire({
                                title: '¡Procesando!',
                                text: 'Redirigiendo al formulario de pago...',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                                background: temaOscuro ? '#212529' : '#ffffff',
                                color: temaOscuro ? '#ffffff' : '#212529'
                            }).then(() => {
                                // Redirigimos al cliente directamente al checkout
                                window.location.href = '/checkout'; // Ajustá la URL si tu ruta usa otro patrón
                            });

                        } else {
                            // Comportamiento tradicional para "Añadir al Carrito" (se queda en la página)
                            Swal.fire({
                                title: '¡Agregado!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                background: temaOscuro ? '#212529' : '#ffffff',
                                color: temaOscuro ? '#ffffff' : '#212529'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    if (error.message === 'AUTH_REQUIRED') {
                        // Redirección elegante para usuarios invitados
                        Swal.fire({
                            title: '¡Inicia sesión!',
                            text: 'Debes tener una cuenta para poder añadir productos al carrito.',
                            icon: 'info',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ir a Iniciar Sesión',
                            background: temaOscuro ? '#212529' : '#ffffff',
                            color: temaOscuro ? '#ffffff' : '#212529'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/login'; // Ajustá la URL si tu ruta usa otro patrón
                            }
                        });
                    } else if (error.message === 'ADMIN_RESTRICTED') {
                        // Bloqueo y redirección para el Administrador
                        Swal.fire({
                            title: 'Acceso Restringido',
                            text: 'Los administradores no pueden añadir productos al carrito de compras ni realizar compras.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ir al Panel de Admin',
                            cancelButtonText: 'Permanecer aquí',
                            background: temaOscuro ? '#212529' : '#ffffff',
                            color: temaOscuro ? '#ffffff' : '#212529'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/admin';
                            }
                        });
                    } else {
                        let mensajeUsuario = 'Ocurrió un error inesperado. Intenta nuevamente.';

                        if (
                            error.message.includes('stock') ||
                            error.message.includes('Stock') ||
                            error.message.includes('inventario')
                        ) {
                            mensajeUsuario = 'No hay suficiente stock disponible del producto solicitado.';
                        }

                        Swal.fire({
                            title: 'Stock insuficiente',
                            text: mensajeUsuario,
                            icon: 'warning',
                            confirmButtonColor: '#f39c12',
                            background: temaOscuro ? '#212529' : '#ffffff',
                            color: temaOscuro ? '#ffffff' : '#212529'
                        });
                    }
                })
                .finally(() => {
                    if (submitBtn) submitBtn.disabled = false;
                });
        }
    });
});