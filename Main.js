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

let mapaIniciado = false;
let mapa, marcador;

document.getElementById('activar-mapa').addEventListener('change', function () {
    const contenedor = document.getElementById('contenedor-mapa');
  
    if (this.checked) {
      contenedor.style.display = 'block';
  
      // Solo inicializa si aún no existe
      if (!mapaIniciado) {
        mapa = L.map('map').setView([8.621241, -70.244275], 16);  // Centro inicial ajustable
  
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);
  
        mapa.on('click', function (e) {
          const lat = e.latlng.lat;
          const lng = e.latlng.lng;
  
          // Guardar valores en campos ocultos
          document.getElementById('latitud').value = lat;
          document.getElementById('longitud').value = lng;
  
          // Añadir marcador o moverlo
          if (marcador) {
            marcador.setLatLng([lat, lng]);
          } else {
            marcador = L.marker([lat, lng]).addTo(mapa);
          }
        });
  
        mapaIniciado = true;
      }
  
      // Ajustar tamaño después de mostrar
      setTimeout(() => {
        mapa.invalidateSize();
      }, 300);
  
    } else {
      contenedor.style.display = 'none';
    }
  });

function mostrarInvitacionesPendientes() {
    fetch("obtener_invitaciones_usuario.php")
      .then(res => res.json())
      .then(data => {
        const contenedor = document.getElementById("invitaciones-usuario");
        contenedor.innerHTML = "";
  
        if (data.length === 0) {
          contenedor.innerHTML = "";
          return;
        }
  
        data.forEach(inv => {
          const div = document.createElement("div");
          div.classList.add("invitacion");
  
          div.innerHTML = `
            <p><strong>${inv.invitador_nombre} ${inv.invitador_apellido}</strong> te ha invitado al grupo: <strong>${inv.grupo_nombre}</strong></p>
            <button onclick="responderInvitacion(${inv.invitacion_id}, 'aceptada')">Aceptar</button>
            <button onclick="responderInvitacion(${inv.invitacion_id}, 'rechazada')">Rechazar</button>
          `;
  
          contenedor.appendChild(div);
        });
      });
  }
  document.addEventListener("DOMContentLoaded", mostrarInvitacionesPendientes);


  function responderInvitacion(id, respuesta) {
    fetch("responder_invitacion.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `invitacion_id=${id}&respuesta=${respuesta}`
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      mostrarInvitacionesPendientes(); // recargar
    });
  }
  

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
setInterval(obtenerNotificaciones, 10);

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
    



function comentariosPost(event, publicacionId) {
    event.stopPropagation();
    const comentariosDiv = document.getElementById(`comentarios-${publicacionId}`);
    const commentBtnColor = document.getElementById(`comment-btn-${publicacionId}`);
    comentariosDiv.style.display = comentariosDiv.style.display === 'block' ? 'none' : 'block';
    commentBtnColor.style.color = commentBtnColor.style.color === 'green' ? 'var(--color-third)' : 'green';

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

//BUSCAR PERFILES
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


//FUNCION PARA LOS LIKES
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

//FUNCION PARA ABRIR MENU LATERAL
function openNav() {
    document.getElementById("mobile-menu").style.width = "100%";
}
function closeNav() {
    document.getElementById("mobile-menu").style.width = "0%";
}


//FUNCION PARA MENU DE LAS PUBLICACIONES
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

//FUNCION PARA CARGAR LAS PUBLICACIONES
function cargarPublicaciones() {
    fetch('guardarPublicacion.php')
        .then(response => response.json())
        .then(data => {
            const contenedorPublicaciones = document.getElementById('publicaciones');
            data.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
            contenedorPublicaciones.innerHTML = '';

            data.forEach(publicacion => {
                const nuevaPublicacion = document.createElement('div');
                nuevaPublicacion.classList.add('post');
                nuevaPublicacion.id = `post-(${publicacion.publicacion_id})`;

                nuevaPublicacion.innerHTML = `
              <div class="post-header">
                  <img src="${publicacion.imagen}" alt="Foto de usuario">
                  <div class="post-info">
                      <div class="post-info-name">
                          <a href="perfilesUsuarios.php?id=${publicacion.usuario_id}"><p><strong>${publicacion.nombre} ${publicacion.apellido}</strong></p></a>
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
                                            <button onclick="mostrarFormularioReporte('publicacion',${publicacion.publicacion_id})">🚩 Reportar</button>
                                        
                                    </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="post-content">
                 <p>${publicacion.contenido}</p>
                        ${publicacion.imagensubida ? `
                        <div class="post-content-img">
                            <img src="${publicacion.imagensubida}" alt="Imagen de la publicación">
                        </div>
                        ` : ''}
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
                            <button id="comment-btn-${publicacion.publicacion_id}" class="comment-btn" onclick="comentariosPost(event,${publicacion.publicacion_id})">
                            <i class="fa-solid fa-comment"></i></button>
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
                        <div id="comentarios-publicacion-${publicacion.publicacion_id}" class="post-comments"></div>
                        <div  class="mandarcomentario" id="mandarcomentario">
          <form>
                <textarea class="comentario" name="comentario" id="comentario-input-${publicacion.publicacion_id}" placeholder="Escribe un comentario..." required></textarea>
                <button type="button" onclick="agregarComentario(event,${publicacion.publicacion_id})"><i class="fa-solid fa-paper-plane"></i></button>
    </form>
        </div>
                    </div>
              `;

                contenedorPublicaciones.appendChild(nuevaPublicacion);
            });
        });
}


// Llama a la función para cargar publicaciones al cargar la página
cargarPublicaciones();

function cargarSeguidos() {
    fetch('contenedorSeguidos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los seguidos');
            }
            return response.json();
        })
        .then(data => {
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

// Llama a la función para cargar publicaciones al cargar la página

function openConfiguration(){
    const configurationdiv= document.getElementById("configuration");
    configurationdiv.style.display = configurationdiv.style.display === 'block' ? 'none' : 'block';
}
function mostrarFormularioReporte(tipo, id) {
  const motivo = prompt("¿Por qué estás reportando esto?");
  if (!motivo) return;

  fetch("reportar.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ tipo, reportado_id: id, motivo })
  }).then(res => res.json())
    .then(data => alert(data.success ? "Reporte enviado" : "Error al reportar"));
}
document.getElementById("file-input").addEventListener("change", function() {
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
    document.getElementById("file-input").value = "";
    document.getElementById("file-name").textContent = "";
    document.getElementById("contenidoArchivo").style.display= 'none';
}

