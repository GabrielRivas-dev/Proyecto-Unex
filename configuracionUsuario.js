function obtenerMensajesNoleidos() {
    fetch("obtener_mensajes_noleidos.php")
    .then(response => response.json())
    .then(data => {
        let contador = document.getElementById("contador-mensajes");
        if (data.total_no_leidos > 0) {
            contador.textContent = data.total_no_leidos;
        } else {
            contador.style.display = "none"; // Ocultar si no hay mensajes no leídos
        }
    })
    .catch(error => console.error("Error al obtener contador de mensajes:", error));
}
setInterval(obtenerMensajesNoleidos, 2000);

// También actualizar al cargar la página
document.addEventListener("DOMContentLoaded", obtenerMensajesNoleidos);  

function obtenerNotificaciones() {
    fetch('obtener_notificaciones.php')
    .then(response => response.json())
    .then(data => {

        // Actualizar el contador de notificaciones no leídas
        const contador = document.getElementById("contador-notificaciones");
        if (data.no_leidas > 0) {
            contador.textContent = data.no_leidas;
        } else {
            contador.style.display = "none"; // Ocultar si no hay mensajes no leídos
        }

        // Obtener el div donde se mostrarán las notificaciones
        const lista = document.getElementById("lista-notificaciones");
        lista.innerHTML = ""; // Limpiar lista

        if (data.notificaciones.length === 0) {
            lista.innerHTML = "<p>No tienes notificaciones.</p>";
        }

        data.notificaciones.forEach(notif => {
            const div = document.createElement("div");
            div.textContent = notif.mensaje;
            div.style.padding = "10px";
            div.style.borderBottom = "1px solid #ddd";

            // Si la notificación no está leída, poner un fondo amarillo
            if (notif.leida == 0) {
                div.style.backgroundColor = "#f6e8df"; // Color amarillo claro
                div.style.fontWeight = "bold"; // Resaltar texto
            }

            lista.appendChild(div);
        });
    })
    .catch(error => console.error("Error al obtener notificaciones:", error));
}

// Cargar notificaciones cada 10 segundos (para actualizar en tiempo real)
setInterval(obtenerNotificaciones, 10000);

// Llamar a la función cuando cargue la página
document.addEventListener("DOMContentLoaded", obtenerNotificaciones);


// Mostrar/Ocultar notificaciones al hacer clic en el botón
function mostrarNotificaciones() {
    const lista = document.getElementById("lista-notificaciones");

    if (lista.style.display === "block") {
        lista.style.display = "none";

        // Marcar notificaciones como leídas solo cuando se cierre el div
        fetch('marcar_notificaciones.php', { method: 'POST' })
        .then(() => obtenerNotificaciones()); // 🔄 Volver a cargar para actualizar el contador
    } else {
        lista.style.display = "block";
    }
}

function cambiarClave(){
const div= document.getElementById('cambiar-contraseña');
div.style.display = div.style.display === 'block' ? 'none' : 'block';

}

document.getElementById('form-cambiar-password').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar que la página se recargue

    const passwordActual = document.getElementById('password-actual').value;
    const passwordNueva = document.getElementById('password-nueva').value;
    const passwordConfirmar = document.getElementById('password-confirmar').value;
    const mensaje = document.getElementById('mensaje');

    // Validar que las nuevas contraseñas coincidan
    if (passwordNueva !== passwordConfirmar) {
        mensaje.textContent = 'Las contraseñas no coinciden.';
        mensaje.style.color = 'red';
        return;
    }

    // Enviar la solicitud al servidor
    fetch('cambiar_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password_actual: passwordActual, password_nueva: passwordNueva })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mensaje.textContent = 'Contraseña actualizada correctamente.';
            mensaje.style.color = 'green';
            document.getElementById('form-cambiar-password').reset();
        } else {
            mensaje.textContent = data.message;
            mensaje.style.color = 'red';
        }
    })
    .catch(error => {
        console.error('Error al cambiar la contraseña:', error);
        mensaje.textContent = 'Hubo un error. Intenta nuevamente.';
        mensaje.style.color = 'red';
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const elemento = document.getElementById('id-usuario');
    const usuarioId = elemento.dataset.id;
    cargarSeguidoresSeguidos(usuarioId)
});

function datosPersonales(){
    const div= document.getElementById('divDatosPersonales');
    div.style.display = div.style.display === 'flex' ? 'none' : 'flex';
}
function credenciales(){
    const div= document.getElementById('divCredenciales');
    div.style.display = div.style.display === 'flex' ? 'none' : 'flex';
}
function datosAcademicos(){
    const div= document.getElementById('divDatosAcademicos');
    div.style.display = div.style.display === 'flex' ? 'none' : 'flex';
}

function editarcampo(campo){
    // Mostrar el input y el botón de guardar
    console.log(campo);
    document.getElementById(`${campo}-input`).style.display === 'inline';
    document.getElementById(`guardar-${campo}`).style.display === 'inline';

    if (document.getElementById(`${campo}-input`).style.display === 'inline') {
        document.getElementById(`${campo}-input`).style.display = 'none';
        document.getElementById(`guardar-${campo}`).style.display = 'none';
    } else {
        document.getElementById(`${campo}-input`).style.display = 'inline';
        document.getElementById(`guardar-${campo}`).style.display = 'inline';
    }
}

function guardarCampo(campo) {
    const nuevoValor = document.getElementById(`${campo}-input`).value;

    // Enviar los datos al servidor
    fetch('actualizar_usuario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ campo, valor: nuevoValor })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar el texto y volver al modo de visualización
                document.getElementById(`${campo}-texto`).textContent = nuevoValor;
                document.getElementById(`${campo}-texto`).style.display = 'inline';
                document.getElementById(`${campo}-input`).style.display = 'none';
                document.getElementById(`guardar-${campo}`).style.display = 'none';
                alert("vuelve a iniciar sesion para efectuar los cambios");
            } else {
                alert('Error al actualizar el campo');
            }
        })
        .catch(error => {
            console.error('Error al actualizar la información:', error);
        });
}

function cargarSeguidoresSeguidos(usuarioId) {
    fetch(`obtener_seguidores.php?usuario_id=${usuarioId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar los contadores en el DOM
                document.getElementById('seguidores-count').textContent = data.seguidores;
                document.getElementById('seguidos-count').textContent = data.seguidos;
            } else {
                console.error('Error al obtener seguidores:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar seguidores:', error);
        });
}
function buscarPerfiles() {
    const query = document.getElementById('buscador').value.trim();
    const resultados = document.getElementById('resultados');

    if (query.length === 0) {
        resultados.innerHTML = '';
        resultados.style.display = 'none';
        return;
    }

    fetch(`buscar_perfiles.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            resultados.innerHTML = '';

            if (data.length > 0) {
                resultados.style.display = 'block';
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.classList.add('perfilresultados');

                    if (item.tipo === 'usuario') {
                        div.innerHTML = `
                            <img src="${item.imagen}" alt="${item.Nombre}" class="foto-perfil" />
                            <div class="info-perfil">
                                <a href="perfilesUsuarios.php?id=${item.id}">
                                    <p><strong>${item.Nombre} ${item.Apellido}</strong></p>
                                </a> 
                            </div>
                        `;
                    } else if (item.tipo === 'foro') {
                        div.innerHTML = `
                                
                                    <img src="${item.imagen}" alt="${item.titulo}" class="foto-perfil" />
                            <div class="info-perfil">
                            <a href="foro.php?id=${item.id}">
                                    <p><strong>${item.titulo}</strong></p>
                                    </a>
                            </div>
                                
                        `;
                    }

                    resultados.appendChild(div);
                });
            } else {
                resultados.innerHTML = '<p>No se encontraron resultados.</p>';
            }
        })
        .catch(error => console.error('Error al buscar perfiles:', error));
}
function cargarSeguidosForo() {
    fetch('contenedorSeguidosForo.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los seguidos');
            }
            return response.json();
        })
        .then(data => {
            const contenedorSeguidos = document.getElementById('seguidos-foros');
            // Limpiar el contenedor
            contenedorSeguidos.innerHTML = '';

            // Iterar sobre los datos recibidos y crear los elementos
            data.forEach(foroSeguidos => {
                const listaSeguidores = document.createElement('div');
                listaSeguidores.classList.add('seguido');

                listaSeguidores.innerHTML = `
                    <a href="foro.php?id=${foroSeguidos.foro_id}">
                    <img src="${foroSeguidos.imagen}" alt="Imagen del seguido">
                    <p>${foroSeguidos.titulo}</p>
                    </a>
                `;

                // Agregar el elemento al contenedor
                contenedorSeguidos.appendChild(listaSeguidores);
            });
        })
        .catch(error => {
            console.error('Error al cargar los seguidos:', error);
        });
}

// Llamar a la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarSeguidosForo);

//FUNCION PARA ABRIR MENU LATERAL
function openNav() {
    document.getElementById("mobile-menu").style.width = "100%";
}
function closeNav() {
    document.getElementById("mobile-menu").style.width = "0%";
}



  function cargarSeguidos() {
    fetch('contenedorSeguidos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los seguidos');
            }
            return response.json();
        })
        .then(data => {
            console.log("Datos cargados:", data);

            const contenedorSeguidos = document.getElementById('seguidos');
            // Limpiar el contenedor
            contenedorSeguidos.innerHTML = '';

            // Iterar sobre los datos recibidos y crear los elementos
            data.forEach(seguidos => {
                const listaSeguidores = document.createElement('div');
                listaSeguidores.classList.add('seguido');

                listaSeguidores.innerHTML = `
                    <a href="perfilesUsuarios.php?id=${seguidos.seguido_id}">
                    <img src="${seguidos.imagen}" alt="Imagen del seguido">
                    <p>${seguidos.nombre}</p>
                    <p>${seguidos.apellido}</p>
                    </a>
                `;

                // Agregar el elemento al contenedor
                contenedorSeguidos.appendChild(listaSeguidores);
            });
        })
        .catch(error => {
            console.error('Error al cargar los seguidos:', error);
        });
}

// Llamar a la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarSeguidos);

function openConfiguration(){
    const configurationdiv= document.getElementById("configuration");
    configurationdiv.style.display = configurationdiv.style.display === 'flex' ? 'none' : 'flex';
}