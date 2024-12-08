document.getElementById("Miformulario").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita el envío del formulario si hay errores
    let esValido = true;

   

    // Validar el campo Email
    const email = document.getElementById("email").value;
    const errorEmail = document.getElementById("erroremail");
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        errorEmail.textContent = "Por favor, ingresa un email válido.";
        errorEmail.style.display = "block";
        esValido = false;
    } else {
        errorEmail.style.display = "none";
    }

    // Validar el campo Contraseña
    const password = document.getElementById("password").value;
    const errorPassword = document.getElementById("errorPassword");
    if (password.length < 6) {
        errorPassword.textContent = "La contraseña debe tener al menos 6 caracteres.";
        errorPassword.style.display = "block";
        esValido = false;
    } else {
        errorPassword.style.display = "none";
    }

    // Enviar el formulario si no hay errores
    if (esValido) {
        alert("Formulario enviado correctamente");
        // Aquí puedes ejecutar la función para enviar el formulario
    }
});