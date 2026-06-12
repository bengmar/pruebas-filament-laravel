//Se llama a la función al finalizar la carga de la vista, asi verifica si ya hay texto cargado
document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.getElementById("mensaje");
    const contador = document.getElementById("contador");
    const limite = textarea.getAttribute("maxlength");

    // Definimos la función que actualiza el número
    function actualizarContador() {
        const longitudActual = textarea.value.length;
        contador.textContent = `${longitudActual} / ${limite} caracteres`;

        // Estética: si está cerca del límite, cambia a rojo
        if (longitudActual >= limite || longitudActual < 10) {
            contador.style.color = "red";
        } else {
            contador.style.color = "#666";
        }
    }

    // Ejecución AL CARGAR (para capturar el valor de 'old'), antes de que el usuario haga input.
    actualizarContador();

    // Ejecución cada vez que el usuario ESCRIBE
    textarea.addEventListener("input", actualizarContador);
});
