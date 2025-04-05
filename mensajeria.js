function obtenerMensajesGrupo(grupoId) {
    fetch(`obtener_mensajes_grupo.php?grupo_id=${grupoId}`)
    .then(response => response.json())
    .then(data => {
        let mensajesDiv = document.getElementById("mensajes");
        mensajesDiv.innerHTML = "";

        data.forEach(mensaje => {
            let div = document.createElement("div");
            div.classList.add("mensaje");
            div.textContent = `${mensaje.nombre}: ${mensaje.mensaje}`; // Muestra el nombre del emisor
            mensajesDiv.appendChild(div);
        });

        mensajesDiv.scrollTop = mensajesDiv.scrollHeight;
    });
}

//Aparecer formulario para crear el grupo
function AgregarGrupo(){
 const div= document.getElementById("grupoFormulario");
 div.style.display = div.style.display=== 'block' ? 'none' : 'block'; 
 document.getElementById("formCrearGrupo").reset();
}

//formulario para crear grupos
document.addEventListener("DOMContentLoaded", function() {
    cargarUsuarios(); // Llamar función para mostrar usuarios

    document.getElementById("formCrearGrupo").addEventListener("submit", function(event) {
        event.preventDefault();

        let nombreGrupo = document.getElementById("nombreGrupo").value;
        let miembros = [];
        
        document.querySelectorAll("input[name='miembros']:checked").forEach(checkbox => {
            miembros.push(checkbox.value);
        });

        if (miembros.length === 0) {
            alert("Selecciona al menos un miembro");
            return;
        }

        let formData = new FormData();
        formData.append("nombre_grupo", nombreGrupo);
        formData.append("miembros", JSON.stringify(miembros));

        fetch("crear_grupo.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("mensaje").innerHTML = "Grupo creado con éxito.";
                document.getElementById("formCrearGrupo").reset();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error al crear grupo:", error));
    });
});


function cargarUsuarios() {
    fetch("obtener_usuarios.php")
    .then(response => response.json())
    .then(data => {
        let listaUsuarios = document.getElementById("listaUsuarios");
        listaUsuarios.innerHTML = "";

        const idCreador = document.getElementById("idCreador").value; // Obtener ID del creador


        data.forEach(usuario => {
            if (usuario.id != idCreador) { // Evita mostrar al creador
                let div = document.createElement("div");
                div.innerHTML = `
                    <input type="checkbox" name="miembros" value="${usuario.id}">
                    ${usuario.nombre} ${usuario.apellido}
                `;
                listaUsuarios.appendChild(div);
            }
        });

        if (listaUsuarios.innerHTML === "") {
            listaUsuarios.innerHTML = "<p>No hay usuarios disponibles</p>";
        }
    })
    .catch(error => console.error("Error al cargar usuarios:", error));
}


function cargarConversaciones() {
    fetch("contenedorChats.php")
    .then(response => response.json())
    .then(data => {
        const conversacionesDiv = document.getElementById("conversaciones");
        conversacionesDiv.innerHTML = "";

        data.forEach(chat => {
            let div = document.createElement("div");
            div.classList.add("chat-item");
            div.onclick = () => seleccionarReceptor(chat.receptor_id);

            if (chat.visto == 1) {
                div.innerHTML = `
                <div class="chat-info">
                <a><img src="${chat.imagen}"> 
                    <p>${chat.nombre} ${chat.apellido}</p></a>
                    <p class="ultimo-mensaje-visto">${chat.ultimo_mensaje ? chat.ultimo_mensaje : "No hay mensajes"}</p>
                </div>
            `;
            }
            else{
                div.innerHTML = `
                <div class="chat-info">
                <a><img src="${chat.imagen}"> 
                    <p>${chat.nombre} ${chat.apellido}</p></a>
                    <p class="ultimo-mensaje-novisto">${chat.ultimo_mensaje ? chat.ultimo_mensaje : "No hay mensajes"}</p>
                </div>
            `;
            }
            

            conversacionesDiv.appendChild(div);
        });
    })
    .catch(error => console.error("Error al cargar conversaciones:", error));
}
setInterval(cargarConversaciones, 2000);

// Llamar a la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarConversaciones);

function cargarGrupos() {
    fetch("contenedorGrupos.php")
    .then(response => response.json())
    .then(data => {
        const listaGrupos = document.getElementById("grupos");
        listaGrupos.innerHTML = "";

        if (!data.length) {
            listaGrupos.innerHTML = "<p>No hay grupos disponibles</p>";
            return;
        }

        data.forEach(grupo => {
            // Evitar duplicados
            if (!document.getElementById(`grupo-${grupo.grupo_id}`)) {
                let div = document.createElement("div");
                div.classList.add("grupo-item");
                div.id = `grupo-${grupo.grupo_id}`;
                div.onclick = () => seleccionarReceptor(grupo.grupo_id, true); // Indica que es un grupo

                // Mostrar el último mensaje si está disponible
                let ultimoMensaje = grupo.ultimo_mensaje 
                    ? `<span class="ultimo-mensaje">${grupo.ultimo_mensaje}</span>` 
                    : `<span class="ultimo-mensaje">Sin mensajes</span>`;

                div.innerHTML = `
                    <div class="chat-info">
                        <p><strong>${grupo.grupo_nombre}</strong></p>
                        ${ultimoMensaje}
                    </div>
                `;
                listaGrupos.appendChild(div);
            }
        });
    })
    .catch(error => console.error("Error al cargar grupos:", error));
}

setInterval(cargarGrupos, 2000);

// Llamar a la función al cargar la página
document.addEventListener('DOMContentLoaded', cargarGrupos);

let chatId = null;
let receptorId = null;
let receptorInfo = {}; // Almacenar la info del receptor

// Función para seleccionar un usuario y obtener su chat_id + info
function seleccionarReceptor(id) {
    fetch("obtener_chat.php?receptor_id=" + id)
    .then(response => response.json())
    .then(data => {
        chatId = data.chat_id;
        receptorId = data.receptor.id;
        receptorInfo = data.receptor; // Guardamos la info del receptor
        console.log("Chat ID:", chatId);
        console.log("Receptor Info:", receptorInfo);
        actualizarInterfazReceptor();
        obtenerMensajes();
        marcarMensajesVistos();
    });
}

function marcarMensajesVistos() {
    fetch("marcar_mensajes_vistos.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "chat_id=" + chatId
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error("Error al marcar mensajes como vistos:", data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}
// Función para actualizar la interfaz con la info del receptor
function actualizarInterfazReceptor() {
    document.getElementById("nombre-receptor").textContent = receptorInfo.nombre + " " + receptorInfo.apellido;
    document.getElementById("imagen-receptor").src = receptorInfo.imagen;
}

document.getElementById("archivo").addEventListener("change", function() {
    let archivo = this.files[0]; // Obtener el archivo seleccionado
    const fileDiv= document.getElementById("contenidoArchivo");
    if (archivo) {
        document.getElementById("file-name").textContent = archivo.name; // Mostrar nombre
        fileDiv.style.display= 'block';
    } else {
        document.getElementById("file-name").textContent = "Seleccionar archivo"; // Resetear
        fileDiv.style.display= 'none';
    }
});
function quitarArchivo(){
    document.getElementById("archivo").value = "";
    document.getElementById("file-name").textContent = "";
    document.getElementById("contenidoArchivo").style.display= 'none';
}

// Función para enviar un mensaje
document.getElementById("formularioMensaje").addEventListener("submit", function(event) {
    event.preventDefault(); // Evitar recarga de página

    let formData = new FormData(this);
    formData.append("chat_id", chatId);

    fetch("enviar_mensaje.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("mensaje").value = "";
            document.getElementById("archivo").value = "";
            document.getElementById("file-name").textContent = "";
            obtenerMensajes(); // Recargar mensajes
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error al enviar mensaje:", error));
});

function obtenerMensajes() {
    if (!chatId) return;

    const mensajesDiv = document.getElementById("mensajes");
    const estabaAbajo = mensajesDiv.scrollTop + mensajesDiv.clientHeight >= mensajesDiv.scrollHeight - 10; // Detectar si está en el último mensaje

    fetch("obtener_mensajes.php?chat_id=" + chatId)
    .then(response => response.json())
    .then(data => {
        const scrollPosicionAntes = mensajesDiv.scrollTop;
        mensajesDiv.innerHTML = "";

        data.forEach(mensaje => {
            let div = document.createElement("div");
            div.classList.add("mensaje");
            
            // Mostrar mensaje de texto si existe
            if (mensaje.mensaje) {
                let textoMensaje = document.createElement("p");
                textoMensaje.textContent = mensaje.mensaje;
                div.appendChild(textoMensaje);
            }

            // Si el mensaje tiene un archivo adjunto
            if (mensaje.archivo) {
                let archivoElemento;
                let extension = mensaje.archivo.split('.').pop().toLowerCase();

                if (["jpeg", "jpg", "png", "gif"].includes(extension)) {
                    // Mostrar imagen directamente en el chat
                    archivoElemento = document.createElement("img");
                    archivoElemento.src = mensaje.archivo;
                    archivoElemento.style.maxWidth = "200px";
                    archivoElemento.style.borderRadius = "10px";
                    archivoElemento.style.display = "block";
                    archivoElemento.style.marginTop = "5px";
                } else {
                    // Contenedor estilo "WhatsApp" para documentos
                    let contenedorArchivo = document.createElement("div");
                    contenedorArchivo.classList.add("archivo-adjunto");

                    let iconoArchivo = document.createElement("i");
                    iconoArchivo.classList.add("fa-solid");
                    
                    if (extension === "pdf") {
                        iconoArchivo.classList.add("fa-file-pdf");
                        iconoArchivo.style.color = "red";
                    } else if (["doc", "docx"].includes(extension)) {
                        iconoArchivo.classList.add("fa-file-word");
                        iconoArchivo.style.color = "blue";
                    } else if (["xls", "xlsx"].includes(extension)) {
                        iconoArchivo.classList.add("fa-file-excel");
                        iconoArchivo.style.color = "green";
                    } else {
                        iconoArchivo.classList.add("fa-file");
                        iconoArchivo.style.color = "gray";
                    }

                    let nombreArchivo = document.createElement("p");
                    nombreArchivo.textContent = mensaje.archivo.split('/').pop();

                    let linkDescargar = document.createElement("a");
                    linkDescargar.href = mensaje.archivo;
                    linkDescargar.textContent = "Abrir";
                    linkDescargar.target = "_blank";
                    linkDescargar.classList.add("boton-descargar");

                    contenedorArchivo.appendChild(iconoArchivo);
                    contenedorArchivo.appendChild(nombreArchivo);
                    contenedorArchivo.appendChild(linkDescargar);
                    
                    archivoElemento = contenedorArchivo;
                }
                
                div.appendChild(document.createElement("br"));
                div.appendChild(archivoElemento);
            }

            if (mensaje.emisor_id == usuarioActual) {
                div.classList.add("mensaje-propio");

                let vistoIcon = document.createElement("span");
                // Icono de mensaje visto/no visto
                if (mensaje.visto == 1) {
                    vistoIcon.innerHTML = "<i class='fa-solid fa-eye'></i>"; 
                    vistoIcon.style.color = "green";
                } else {
                    vistoIcon.innerHTML = "<i class='fa-solid fa-eye'></i>"; 
                    vistoIcon.style.color = "gray";
                }
                div.appendChild(vistoIcon);

                // Permitir eliminar mensaje al hacer clic
                div.onclick = () => mostrarOpcionEliminar(div, mensaje.id);
            } else {
                div.classList.add("mensaje-ajeno");
            }

            mensajesDiv.appendChild(div);
        });
  // Mantener posición del scroll si el usuario no estaba en el último mensaje
  if (estabaAbajo) {
    mensajesDiv.scrollTop = mensajesDiv.scrollHeight;
} else {
    mensajesDiv.scrollTop = scrollPosicionAntes;
}
    });
    marcarMensajesVistos();
}

setInterval(obtenerMensajes, 4000);

function mostrarOpcionEliminar(mensajeDiv, mensajeId) {
    // Si ya existe un botón de eliminar en el mensaje, lo removemos
    let botonExistente = mensajeDiv.querySelector(".eliminar-mensaje");
    if (botonExistente) {
        botonExistente.remove();
        return; // Si ya estaba visible, oculta el botón al hacer clic nuevamente
    }

    // Crear botón de eliminar
    let btnEliminar = document.createElement("button");
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("eliminar-mensaje");
    btnEliminar.onclick = (event) => {
        event.stopPropagation(); // Evita que el clic cierre el botón inmediatamente
        eliminarMensaje(mensajeId);
    };

    mensajeDiv.appendChild(btnEliminar);
}

function eliminarMensaje(mensajeId) {
    if (!confirm("¿Seguro que quieres eliminar este mensaje?")) return;

    fetch("eliminar_mensaje.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mensaje_id: mensajeId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            obtenerMensajes(); // Recargar mensajes después de eliminar
        } else {
            alert("Error al eliminar mensaje: " + data.message);
        }
    })
    .catch(error => console.error("Error al eliminar mensaje:", error));
}

function obtenerMensajesNoleidos() {
    fetch("obtener_mensajes_noleidos.php")
    .then(response => response.json())
    .then(data => {
        let contador = document.getElementById("contador-mensajes");
        if (data.no_leidas > 0) {
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
        if (data.total_no_leidos > 0) {
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
                div.style.backgroundColor = "#b8dbff"; // Color amarillo claro
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

function buscarPerfiles() {
    const query = document.getElementById('buscador').value.trim();

    if (query.length === 0) {
        document.getElementById('resultados').innerHTML = ''; // Limpiar resultados si no hay texto
        resultados.style.display = 'none';
        return;
    }

    fetch(`buscar_perfiles.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const resultados = document.getElementById('resultados');
            resultados.innerHTML = ''; // Limpiar resultados anteriores

            if (data.length > 0) {
                resultados.style.display = 'block';
                data.forEach(usuarios => {
                    const perfil = document.createElement('div');
                    perfil.classList.add('perfilresultados');
                    perfil.innerHTML = `
                        <img src="${usuarios.imagen}" alt="${usuarios.Nombre}" class="foto-perfil" />
                        <div class="info-perfil">
                            <a href="perfilesUsuarios.php?id=${usuarios.id}"><p><strong>${usuarios.Nombre} ${usuarios.Apellido}</strong></p></a> 
                        </div>
                    `;
                    resultados.appendChild(perfil);
                });
            } else {
                resultados.innerHTML = '<p>No se encontraron resultados.</p>';
            }
        })
        .catch(error => console.error('Error al buscar perfiles:', error));
}

function openNav() {
    document.getElementById("mobile-menu").style.width = "100%";
}
function closeNav() {
    document.getElementById("mobile-menu").style.width = "0%";
}

function openConfiguration(){
    const configurationdiv= document.getElementById("configuration");
    configurationdiv.style.display = configurationdiv.style.display === 'block' ? 'none' : 'block';
}




