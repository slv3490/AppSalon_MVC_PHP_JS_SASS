(function() {
    function anchoPantalla() {
        const formulario = document.querySelector(".formulario.login");


        if(window.innerWidth >= 375) {
            formulario.classList.remove("login");
        }
    }
    anchoPantalla();
})();
