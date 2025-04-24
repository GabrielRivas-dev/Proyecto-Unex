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

let publicacionACompartir= null;

function divCompartir(publicacionId){
     publicacionACompartir = publicacionId;
    const div= document.getElementById('compartir-publicacion');
    div.style.display = div.style.display === 'block' ? 'none' : 'block';
    
    }
    function compartirPublicacion() {
        if (!publicacionACompartir) {
            alert("Error: No se encontró la publicación.");
            return;
        }
    
        const datos = JSON.stringify({ publicacion_id: publicacionACompartir });
    
        console.log("Enviando datos:", datos); // 👀 Ver qué se envía
    
        fetch('compartir_publicacion.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: datos
        })
        .then(response => response.text()) // Ver la respuesta del servidor
        .then(data => {
            console.log("Respuesta del servidor:", data); // 👀 Ver qué responde PHP
            try {
                let jsonData = JSON.parse(data);
                if (jsonData.success) {
                    alert("¡Publicación compartida con éxito!");
                    location.reload();
                } else {
                    alert("Error al compartir: " + jsonData.message);
                }
            } catch (error) {
                console.error("Error al procesar JSON:", error);
            }
        })
        .catch(error => console.error("Error:", error));
    }
    
document.addEventListener('DOMContentLoaded', () => {
    // Buscar todos los botones con la clase 'seguir-btn'
    const botones = document.querySelectorAll('button[id^="seguir-btn-"]');

    // Extraer los IDs de los usuarios de los botones
    botones.forEach(boton => {
        const usuarioId = boton.id.replace('seguir-btn-', ''); // Extraer el ID
        verificarEstadoSeguimiento(usuarioId); // Llamar a la función con cada ID
        cargarSeguidoresSeguidos(usuarioId);
    });
});
function toggleSeguir(seguidoId) {
    const boton = document.getElementById(`seguir-btn-${seguidoId}`);
    const accion = boton.textContent === 'Seguir' ? 'seguir_usuario.php' : 'dejar_seguir_usuario.php';

    fetch(accion, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ seguido_id: seguidoId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                boton.textContent = boton.textContent === 'Seguir' ? 'Dejar de seguir' : 'Seguir';
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
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
function verificarEstadoSeguimiento(seguidoId) {
    const boton = document.getElementById(`seguir-btn-${seguidoId}`);

    fetch(`estado_seguimiento.php?seguido_id=${seguidoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar el texto del botón según el estado de seguimiento
                boton.textContent = data.sigue ? 'Dejar de seguir' : 'Seguir';
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al verificar el estado de seguimiento:', error);
        });
}


function comentariosPost(event, publicacionId) {
    event.stopPropagation();
    const comentariosDiv = document.getElementById(`comentarios-${publicacionId}`);
    const commentBtnColor = document.getElementById(`comment-btn-${publicacionId}`);
    comentariosDiv.style.display = comentariosDiv.style.display === 'block' ? 'none' : 'block';
    commentBtnColor.style.color = commentBtnColor.style.color === 'green' ? '#79aefd' : 'green';

    fetch(`obtener_comentarios.php?publicacion_id=${publicacionId}`)
        .then(response => response.json())
        .then(data => {
            const contenedor = document.getElementById(`comentarios-publicacion-${publicacionId}`);
            
            contenedor.innerHTML = ''; // Limpiar comentarios anteriores

            if (!data.length) {
                contenedor.innerHTML = "<p>Se el primero en comentar</p>";
                return;
            }


            data.forEach(comentario => {
                const divComentario = document.createElement('div');
                divComentario.classList.add('comentario');
                divComentario.id = `comentario-${comentario.comentario_id}`;
                divComentario.innerHTML = `

                <div class="post-comments-head">
            <img src="${comentario.usuario_imagen}" alt="Foto de usuario">
            <strong>${comentario.usuario_nombre} ${comentario.usuario_apellido}</strong>
            <span>${comentario.fecha}</span>
                <div class="post-comments-config"><button onclick="comentarioConfig(event,${comentario.comentario_id})"><i class="fa-solid fa-minus"></i></button></div>
                    <div id="comentarioConfig-${comentario.comentario_id}" style="display: none;" class="comment-config">
                    <button onclick="eliminarComentario(${comentario.comentario_id})">Eliminar</button>
                </div>  
                </div>
            <div class="post-comments-info">
                <p>${comentario.comentario}</p>
            </div>
            `;
                contenedor.appendChild(divComentario);
            });
        })
        .catch(error => console.error('Error al cargar comentarios:', error));
}

function agregarComentario(event, publicacionId) {
    event.preventDefault(); // Evitar recargar la página

    const comentarioInput = document.getElementById(`comentario-input-${publicacionId}`);
    const comentarioTexto = comentarioInput.value.trim();

    if (comentarioTexto === '') {
        alert('El comentario no puede estar vacío');
        return;
    }

    fetch('agregar_comentario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ publicacion_id: publicacionId, comentario: comentarioTexto })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const comentariosDiv = document.getElementById(`comentarios-${publicacionId}`);
                comentariosDiv.style.display = comentariosDiv.style.display === 'block' ? 'none' : 'block';
                comentariosPost(event,publicacionId);
    
                
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al agregar el comentario:', error);
        });
}
function comentarioConfig(event,comentarioId) {
    event.stopPropagation(); // Evitar burbujeo del evento
    const configcomment = document.getElementById(`comentarioConfig-${comentarioId}`);
    configcomment.style.display = configcomment.style.display === 'block' ? 'none' : 'block';
}
function eliminarComentario(comentarioId) {
    console.log(comentarioId);
    if (!confirm('¿Estás seguro de que deseas eliminar este comentario?')) return;

    fetch('eliminar_comentario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ comentario_id: comentarioId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar el comentario del DOM
                const comentarioElement = document.getElementById(`comentario-${comentarioId}`);
                if (comentarioElement) comentarioElement.remove();
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el comentario:', error);
        });
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


function likePost(publicacionId) {
  fetch('like.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ publicacion_id: publicacionId })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Actualizar el contador de likes
        const likeButton = document.getElementById(`like-btn-${publicacionId}`);
        const likeCountSpan = document.getElementById(`like-count-${publicacionId}`);
        likeCountSpan.textContent = data.likes; // Mostrar el nuevo total de likes
        if (data.liked ) {
            likeButton.classList.add('liked');
        } else {
            likeButton.classList.remove('liked');
        }

    } else {
        alert(data.message); // Mostrar mensaje de error
    }
})
.catch(error => console.error('Error:', error));
}
document.addEventListener('DOMContentLoaded', () => {
fetch('get_likes.php')
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Aplicar el estado a los botones correspondientes
        data.likes.forEach(publicacionId => {
            const likeButton = document.getElementById(`like-btn-${publicacionId}`);
            if (likeButton) {
                likeButton.classList.add('liked');
            }
        });
    } else {
        console.error('Error al cargar los likes:', data.message);
    }
})
.catch(error => {
    console.error('Error al recuperar los likes:', error);
});
});

function openNav() {
    document.getElementById("mobile-menu").style.width = "100%";
}
function closeNav() {
    document.getElementById("mobile-menu").style.width = "0%";
}

function publicacionConfig(event, publicacionId) {
  event.stopPropagation(); // Evitar burbujeo del evento
  const configMenu = document.getElementById(`publicacion-config-${publicacionId}`);
  configMenu.style.display = configMenu.style.display === 'block' ? 'none' : 'block';
}

function eliminarPublicacion(publicacionId) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta publicacion?')) return;

    fetch('eliminarpublicacion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ publicacion_id: publicacionId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar el comentario del DOM
                const publicacionElement = document.getElementById(`post-(${publicacionId})`);
                if (publicacionElement) publicacionElement.remove();
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar la publicacion:', error);
        });
}
  function cargarPublicacionesPerfil() {
    fetch('perfilpublicacionesusuarios.php')
    .then(response => response.json())
    .then(data => {
        const contenedorPublicaciones = document.getElementById('publicaciones');

        //POR SI NO TIENE PUBLICACIONES
        if (!data.length) {
            contenedorPublicaciones.innerHTML = '<div class="post"><p style="text-align:center;">No tiene publicaciones</p></div>';
            return;
        }

        data.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

        // Limpiar el contenedor
        contenedorPublicaciones.innerHTML = '';


        // Iterar sobre los datos recibidos y crear los elementos
        data.forEach(publicacion => {
            const nuevaPublicacion = document.createElement('div');
            nuevaPublicacion.classList.add('post');
            nuevaPublicacion.id = `post-(${publicacion.publicacion_id})`;

            nuevaPublicacion.innerHTML = `
              <div class="post-header">
                  <img src="${publicacion.imagen}" alt="Foto de usuario">
                  <div class="post-info">
                      <div class="post-info-name">
                          <p><strong>${publicacion.nombre} ${publicacion.apellido}</strong></p>
                          <span>${publicacion.fecha}</span>
                      </div>
                      <div class="post-info-menu">
                         <button onclick="publicacionConfig(event,${publicacion.publicacion_id})" class="config-btn" data-id="${publicacion.id}">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div id="publicacion-config-${publicacion.publicacion_id}" class="config-menu" style="display: none;">
                                <ul>      
                                    <li>
                                        <form>
                                            <button onclick="eliminarPublicacion(${publicacion.publicacion_id})" type="submit">Eliminar publicación</button>
                                    </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="post-content">
                  <p>${publicacion.contenido}</p>
                  <div class="post-content-img">
                      <img src="${publicacion.imagensubida}" alt="Imagen de la publicación">
                  </div>
              </div>
              <div class="post-btns">
                 <ul>
                        <li>
                            <button id="like-btn-${publicacion.publicacion_id}" class="like-btn" onclick="likePost(${publicacion.publicacion_id})">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                            <span id="like-count-${publicacion.publicacion_id}">${publicacion.total_likes}</span>
                        </li>
                        <li>
                            <button id="comment-btn-${publicacion.publicacion_id}" class="comment-btn" onclick="comentariosPost(event,${publicacion.publicacion_id})"><i class="fa-solid fa-comment"></i></button>
                            <span>${publicacion.total_comments}</span>
                        </li>
                        <li>
                            <button id="share-btn-${publicacion.publicacion_id}" class="share-btn" onclick="divCompartir(${publicacion.publicacion_id})">
                                <i class="fa-solid fa-share-from-square"></i>
                            </button>
                            <span>${publicacion.compartidos}</span>
                        </li>
                    </ul>
                </div>
                <div id="comentarios-${publicacion.publicacion_id}" class="comentarios">
        <div id="comentarios-publicacion-${publicacion.publicacion_id}" class="post-comments">
        
        </div>
        <div  class="mandarcomentario" id="mandarcomentario">
          <form>
                <textarea class="comentario" name="comentario" id="comentario-input-${publicacion.publicacion_id}" placeholder="Escribe un comentario..." required></textarea>
                <button type="button" onclick="agregarComentario(event,${publicacion.publicacion_id})"><i class="fa-solid fa-paper-plane"></i></button>
    </form>
        </div>
        </div>
              `;
            // Agregar la nueva publicación al contenedor
            contenedorPublicaciones.appendChild(nuevaPublicacion);
        });
    });
}
  
  // Llama a la función para cargar publicaciones al cargar la página
  cargarPublicacionesPerfil();

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
    configurationdiv.style.display = configurationdiv.style.display === 'block' ? 'none' : 'block';
}