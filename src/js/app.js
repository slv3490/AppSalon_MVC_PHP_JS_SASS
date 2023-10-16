let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id: "",
    nombre: "",
    fecha: "",
    hora: "",
    servicios: []
}

document.addEventListener("DOMContentLoaded", function() {
    iniciarApp();
})

function iniciarApp() {
    mostrarSeccion();
    tabs();
    botonesPaginador();
    paginaAnterior();
    paginaSiguiente();

    consultarAPI(); //Consulta la api en el backend de php

    IdCliente();
    nombreCliente();
    seleccionarfecha();
    mostrarhora();

    mostrarResumen();
}

function mostrarSeccion() {
    //Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector(".mostrar");
    if(seccionAnterior) {
        seccionAnterior.classList.remove("mostrar");
    }
    //Agregar la clase mostrar
    const mostrar = document.querySelector(`#paso-${paso}`);
    mostrar.classList.add("mostrar");

    //Resalta el tab actual
    const tabAnterior = document.querySelector(".actual");
    if(tabAnterior) {
        tabAnterior.classList.remove("actual");
    }
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add("actual");
}

function tabs() {
    const botones = document.querySelectorAll(".tabs button");
    botones.forEach( boton => {
        boton.addEventListener("click", e => {
            paso = parseInt(e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();
        })
    })
}
function botonesPaginador() {
    const paginadorAnterior = document.querySelector("#anterior");
    const paginadorSiguiente = document.querySelector("#siguiente");

    if(paso === 1) {
        paginadorAnterior.classList.add("ocultar");
        paginadorSiguiente.classList.remove("ocultar");
    } else if (paso === 3) {
        paginadorAnterior.classList.remove("ocultar");
        paginadorSiguiente.classList.add("ocultar");
        mostrarResumen();
    } else {
        paginadorAnterior.classList.remove("ocultar");
        paginadorSiguiente.classList.remove("ocultar");
    }
    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector("#anterior");
    paginaAnterior.addEventListener("click", function () {
        
        if(paso <= pasoInicial) return;

        paso--;

        botonesPaginador();
    })
}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector("#siguiente");
    paginaSiguiente.addEventListener("click", function () {
        
        if(paso >= pasoFinal) return;

        paso++;

        botonesPaginador();
    })
}

async function consultarAPI() {
    try {
        const url = "/api/servicios";
        const respuesta = await fetch(url);
        const servicios = await respuesta.json();

        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement("DIV");
        servicioDiv.classList.add("servicio");
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector("#servicios").appendChild(servicioDiv);
    })
}

function seleccionarServicio(servicio) {
    const {id} = servicio;
    const {servicios} = cita;

    cita.servicios = [...servicios, servicio];

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)
    //Comprueba si esta agregado o no
    if(servicios.some(agregado => agregado.id === id )) {
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove("seleccionado");
    } else {    
        //Agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add("seleccionado");
    }
}

function IdCliente() {
    cita.id = document.querySelector("#id").value;
}

function nombreCliente () {
    cita.nombre = document.querySelector("#nombre").value;
}

function seleccionarfecha() {

    const inputFecha = document.querySelector("#fecha");
    inputFecha.addEventListener("input", function(e) {
        
        const dia = new Date(e.target.value).getUTCDay()
        
        if([6, 0].includes(dia)) {
            e.target.value = "";
            mostrarAlerta("Los sabados y domingos no abrimos", "error", ".formulario");
        } else {
            cita.fecha = e.target.value;
        }

    })
}

function mostrarhora() {
    const mostrarhora = document.querySelector("#hora");
    mostrarhora.addEventListener("input", function(e) {

        const hora = e.target.value.split(":")[0]

        if(hora < 10 || hora > 22) {
            e.target.value = ""
            mostrarAlerta("Hora no valida", "error", ".formulario")
        } else {
            cita.hora = e.target.value
        }
    })

}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    const alerta = document.createElement("DIV");
    alerta.textContent = mensaje;
    alerta.classList.add("alerta")
    alerta.classList.add(tipo)

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece) {
        setTimeout(() => {
            alerta.remove()
        }, 3000);
    }

}

function mostrarResumen() {
    const resumen = document.querySelector(".contenido-resumen")
    
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild)
    }


    if(Object.values(cita).includes("") || cita.servicios.length === 0) {
        mostrarAlerta("Faltan datos o Servicios", "error", ".contenido-resumen", false);

        return;
    }

    const {nombre, fecha, hora, servicios} = cita;

    const resumenServicio = document.createElement("H3");
    resumenServicio.textContent = "Resumen Servicio";
    resumen.appendChild(resumenServicio);

    servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio;
        const contenedorCliente = document.createElement("DIV");
        contenedorCliente.classList.add("contenedor-servicio");

        const textoContenedor = document.createElement("P");
        textoContenedor.innerHTML = nombre

        const precioServicio = document.createElement("P")
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`

        contenedorCliente.appendChild(textoContenedor);
        contenedorCliente.appendChild(precioServicio);

        resumen.appendChild(contenedorCliente);
    })

    const resumenCita = document.createElement("H3");
    resumenCita.textContent = "Resumen Cita";
    resumen.appendChild(resumenCita);

    const nombreCliente = document.createElement("P");
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`

    //Formatear la fecha
    const fechaObj = new Date(fecha);
    const day = fechaObj.getDate() + 2;
    const month = fechaObj.getMonth();
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year,month,day))

    const opciones = {weekday: "long", year: "numeric", month: "long", day: "numeric"}
    const fechaFormateada = fechaUTC.toLocaleDateString("es-AR", opciones)

    const fechaCliente = document.createElement("P");
    fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`

    const horaCliente = document.createElement("P");
    horaCliente.innerHTML = `<span>Hora:</span> ${hora} horas`
    console.log(cita);

    const botonEnviarCita = document.createElement("BUTTON");
    botonEnviarCita.classList.add("boton");
    botonEnviarCita.textContent = "Reservar Cita";
    botonEnviarCita.onclick = reservarCita;


    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    resumen.appendChild(botonEnviarCita);
}

async function reservarCita() {
    const datos = new FormData();
    const {nombre, fecha, hora, servicios, id} = cita;

    const idServicio = servicios.map(servicios => servicios.id)

    datos.append("usuarioId", id);
    datos.append("fecha", fecha);
    datos.append("hora", hora);
    datos.append("servicios", idServicio);

    
    try {
        //Peticion hacia la api
        const url = "/api/citas";
        const respuesta = await fetch(url, {
            method: "POST",
            body: datos
        });
        const resultado = await respuesta.json();

        if(resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue reservada correctamente',
                button: "Ok"
            }).then( () => {
                window.location.reload();
            })
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita'
          })
    }
}
