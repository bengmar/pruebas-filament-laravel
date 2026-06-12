/**
 * Maneja la actualización dinámica de cantidades respetando el stock.
 */
document.addEventListener("DOMContentLoaded", function () {
    // 1. DECREMENTO (-)
    document.querySelectorAll(".btn-qty-decrement").forEach((button) => {
        button.addEventListener("click", function () {
            const itemId = this.getAttribute("data-item-id");
            const input = document.querySelector(
                `.input-qty-cart[data-item-id="${itemId}"]`,
            );
            const cardBody = input.closest(".card-body");
            const maxAlert = cardBody.querySelector(".label-stock-max");
            const typeAlert = cardBody.querySelector(".label-type-err");
            let value = parseInt(input.value);

            typeAlert.classList.add("d-none");

            if (value > 1) {
                input.value = value - 1;
                maxAlert.classList.add("d-none");
                updateCartQuantity(itemId, input.value, input);
            }
        });
    });

    // 2. INCREMENTO (+)
    document.querySelectorAll(".btn-qty-increment").forEach((button) => {
        button.addEventListener("click", function () {
            const itemId = this.getAttribute("data-item-id");
            const input = document.querySelector(
                `.input-qty-cart[data-item-id="${itemId}"]`,
            );
            const cardBody = input.closest(".card-body");
            const maxAlert = cardBody.querySelector(".label-stock-max");
            const typeAlert = cardBody.querySelector(".label-type-err");
            const max = parseInt(input.getAttribute("max"));
            let value = parseInt(input.value);

            typeAlert.classList.add("d-none");

            if (value < max) {
                input.value = value + 1;
                maxAlert.classList.add("d-none");
                updateCartQuantity(itemId, input.value, input);
            } else {
                maxAlert.classList.remove("d-none");
            }
        });
    });

    // 3. INPUT MANUAL (oninput — validación en tiempo real)
    document.querySelectorAll(".input-qty-cart").forEach((input) => {
        input.addEventListener("input", function () {
            const cardBody = this.closest(".card-body");
            const maxAlert = cardBody.querySelector(".label-stock-max");
            const typeAlert = cardBody.querySelector(".label-type-err");
            const max = parseInt(this.getAttribute("max"));

            if (this.value === "") {
                typeAlert.classList.remove("d-none");
                maxAlert.classList.add("d-none");
                return;
            }

            typeAlert.classList.add("d-none");
            let value = parseInt(this.value);

            if (value < 1) {
                this.value = 1;
                maxAlert.classList.add("d-none");
            } else if (value > max) {
                this.value = max;
                maxAlert.classList.remove("d-none");
            } else {
                maxAlert.classList.add("d-none");
            }
        });

        // 4. CHANGE — dispara el AJAX cuando el usuario termina de escribir
        input.addEventListener("change", function () {
            const itemId = this.getAttribute("data-item-id");
            let value = parseInt(this.value);
            const max = parseInt(this.getAttribute("max"));
            const initialValue = parseInt(
                this.getAttribute("data-initial-value"),
            );

            if (isNaN(value) || value < 1) {
                this.value = 1;
                value = 1;
            } else if (value > max) {
                this.value = max;
                value = max;
            }

            if (value !== initialValue) {
                updateCartQuantity(itemId, value, this);
            }
        });
    });

    // 5. AJAX — actualización en base de datos y refresco del DOM
    function updateCartQuantity(itemId, quantity, inputElement) {
        const url = `/cart/update/${itemId}`;
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!csrfToken) {
            console.error("Error: No se encontró el meta tag del CSRF token.");
            return;
        }

        fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: JSON.stringify({ quantity: parseInt(quantity) }),
        })
            .then(async (response) => {
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.error || "Error en el servidor");
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    inputElement.setAttribute("data-initial-value", quantity);

                    const itemCard = document.querySelector(
                        `.item-cart-card[data-item-id="${itemId}"]`,
                    );
                    if (itemCard) {
                        const itemTotalDisplay = itemCard.querySelector(
                            ".item-total-display",
                        );
                        if (itemTotalDisplay)
                            itemTotalDisplay.textContent =
                                data.formatted_item_total;
                    }

                    const subtotalDisplay = document.querySelector(
                        ".cart-subtotal-display",
                    );
                    const totalDisplay = document.querySelector(
                        ".cart-total-display",
                    );
                    if (subtotalDisplay)
                        subtotalDisplay.textContent = data.formatted_subtotal;
                    if (totalDisplay)
                        totalDisplay.textContent = data.formatted_subtotal;

                    // =========================================================================
                    // ACTUALIZACIÓN DINÁMICA DEL BADGE GLOBAL (MEJORADO)
                    // =========================================================================
                    const cartBadge =
                        document.getElementById("global-cart-badge");
                    const totalQuantity = parseInt(data.total_quantity) || 0;

                    if (cartBadge) {
                        // Actualizamos el número del contador con la respuesta del servidor
                        cartBadge.textContent = totalQuantity;

                        // Si el carrito se quedó vacío, ocultamos el badge y recargamos
                        if (totalQuantity <= 0) {
                            cartBadge.classList.add("d-none");

                            // Forzamos una recarga rápida para que Blade dibuje el estado "Carrito Vacío"
                            setTimeout(() => {
                                window.location.reload();
                            }, 300);
                        } else {
                            cartBadge.classList.remove("d-none");
                        }
                    }
                    // =========================================================================
                    // NUEVO: CONEXIÓN CON EL STOCK DE LA PÁGINA DE DETALLES
                    // =========================================================================
                    // 1. Buscamos si en la pantalla actual existe la función de sincronización
                    if (typeof actualizarStockDesdeCarrito === "function") {
                        // 2. Obtenemos el ID del producto que está mirando el usuario en el detalle
                        // (Asumimos que el formulario de agregar tiene el input hidden que ya pusiste)
                        const detailProductIdInput = document.querySelector(
                            'input[name="product_id"]',
                        );

                        if (detailProductIdInput) {
                            const currentDetailProductId =
                                detailProductIdInput.value;

                            // 3. Buscamos en el carrito lateral la tarjeta que corresponda a ESTE producto
                            // Para esto, a las tarjetas de tu carrito lateral les podés poner un atributo data-product-id
                            const matchingCartCard = document.querySelector(
                                `.item-cart-card[data-product-id="${currentDetailProductId}"]`,
                            );

                            let cantidadEnCarritoActual = 0;
                            if (matchingCartCard) {
                                const qtyInput =
                                    matchingCartCard.querySelector(
                                        ".input-qty-cart",
                                    );
                                cantidadEnCarritoActual = qtyInput
                                    ? parseInt(qtyInput.value)
                                    : 0;
                            }

                            // 4. Ejecutamos la función mágica del Blade pasándole las unidades que tiene en el carro
                            actualizarStockDesdeCarrito(
                                cantidadEnCarritoActual,
                            );
                        }
                    }
                    // =========================================================================
                }
            })
            .catch((error) => {
                console.error("Error al actualizar el carrito:", error);
                alert(
                    error.message ||
                        "Ocurrió un error al actualizar la cantidad.",
                );
                inputElement.value =
                    inputElement.getAttribute("data-initial-value");
            });
    }

    // =========================================================================
    // NUEVO: PASO DE MENSAJES DE STOCK DESDE LARAVEL (Usuario A vs Usuario B)
    // =========================================================================
    const bridgeInput = document.getElementById("stock-error-bridge");
    // Evaluamos de forma segura si el html tiene la configuración oscura activa
    const temaOscuro =
        document.documentElement.getAttribute("data-bs-theme") === "dark" ||
        document.documentElement.classList.contains("dark");

    if (bridgeInput && bridgeInput.value.trim() !== "") {
        Swal.fire({
            title: "¡Atención! Carrito Actualizado",
            text: bridgeInput.value,
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Revisar Carrito",
            background: temaOscuro ? "#212529" : "#ffffff",
            color: temaOscuro ? "#ffffff" : "#212529",
        }).then(() => {
            // Reemplaza 'offcanvasCarrito' por el ID real de la etiqueta de tu menú lateral
            const cartOffcanvasEl = document.getElementById("offcanvasCarrito");
            if (cartOffcanvasEl && typeof bootstrap !== "undefined") {
                const bsOffcanvas =
                    bootstrap.Offcanvas.getInstance(cartOffcanvasEl) ||
                    new bootstrap.Offcanvas(cartOffcanvasEl);
                bsOffcanvas.show();
            }
        });
    }
});
