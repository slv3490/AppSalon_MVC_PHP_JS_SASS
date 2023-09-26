window.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
})

function iniciarApp() {
    buscador();
}

function buscador() {
    const fechaInput = document.querySelector("#fecha");
    fechaInput.addEventListener("input", function(e) {
        const fechaSeleccionada = e.target.value;
        window.location = `?fecha=${fechaSeleccionada}`;
    })  
}