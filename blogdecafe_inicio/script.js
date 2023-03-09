const datos = {
    nombre: "Luciano",
    email: "",
    mensaje: "",
}

const nombre = document.querySelector("#nombre");
const email = document.querySelector("#email");
const mensaje = document.querySelector("#mensaje");

const leerTexto = e => datos[e.target.id] = e.target.value;

nombre.addEventListener("input", leerTexto);
email.addEventListener("input", leerTexto);
mensaje.addEventListener("input", leerTexto);

const formulario = document.querySelector(".formulario");
//Evento submit
formulario.addEventListener("submit", (e) => {
    e.preventDefault();
    const {nombre,email, mensaje} = datos; //Extraer y crear tres variables del objeto datos

    //Validar formulario
    if(nombre === "" || email === "" || mensaje === "") {
        return mostrarMensaje("Todos los campos son obligatorios", true);
    }
    else mostrarMensaje("Formulario enviado correctamente")
})

// Muestra un error en pantalla
let mostrarMensaje = (mensaje, error = null) => {
    let alerta = document.createElement("P");
    alerta.classList.add("alerta");
    alerta.textContent = mensaje;
    


    if (error) alerta.classList.add("error");
    else alerta.classList.add("correcto");

    console.log(alerta)

    formulario.appendChild(alerta);


    setTimeout(() => {
        alerta.remove()
    }, 5000);
}

