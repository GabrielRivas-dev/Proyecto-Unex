
let mapaIniciado = false;
let mapa, marcador;

document.getElementById('activar-mapa').addEventListener('change', function () {
    const contenedor = document.getElementById('contenedor-mapa');
  
    if (this.checked) {
      contenedor.style.display = 'block';
  
      // Solo inicializa si aún no existe
      if (!mapaIniciado) {
        mapa = L.map('map').setView([8.621241, -70.244275], 16); // Centro inicial ajustable
  
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

function cargarEventos() {
    fetch('mostrar_eventos.php')
        .then(response => response.json())
        .then(data => {
            const contenedorPublicaciones = document.getElementById('eventos');
             if (!data.length) {
            contenedorPublicaciones.innerHTML = '<div class="post"><p style="text-align:center;">No hay eventos</p></div>';
            return;
        }
            data.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
            contenedorPublicaciones.innerHTML = '';

            data.forEach(eventos => {
                const nuevaPublicacion = document.createElement('div');
                nuevaPublicacion.classList.add('post');
                nuevaPublicacion.id = `post-(${eventos.evento_id})`;

                nuevaPublicacion.innerHTML = `
              <div class="post-header">
                  <img src="${eventos.imagen}" alt="Foto de usuario">
                  <div class="post-info">
                      <div class="post-info-name">
                          <p><strong>${eventos.nombre} ${eventos.apellido}</strong></p>
                          <span>${eventos.creado_en}</span>
                      </div>
                          <div class="post-info-menu">
                         <button onclick="publicacionConfig(event,${eventos.evento_id})" class="config-btn" data-id="${eventos.evento_id}">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div id="publicacion-config-${eventos.evento_id}" class="config-menu" style="display: none;">
                                <ul>
                                    <li>
                                       <form>
                                            <button onclick="eliminarEvento(${eventos.evento_id})" type="submit">Eliminar Evento</button>
                                            <button onclick="mostrarFormularioReporte('Evento',${eventos.evento_id})">🚩 Reportar</button>
                                        
                                    </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="post-content">
                 <p>${eventos.descripcion}</p>
                 <span style="color:grey;">dia del evento ${eventos.fecha} a la hora ${eventos.hora}</span>
                 ${eventos.latitud && eventos.longitud &&
                    parseFloat(eventos.latitud) !== 0 &&
                    parseFloat(eventos.longitud) !== 0
                    ? `<div id="map-${eventos.evento_id}" class="evento-mapa"></div>` 
                    : ''}
                  
              </div>
              `;

                contenedorPublicaciones.appendChild(nuevaPublicacion);
                if (eventos.latitud !=0 && eventos.longitud !=0) {
                    setTimeout(() => {
                      const map = L.map(`map-${eventos.evento_id}`).setView([eventos.latitud, eventos.longitud], 15);
                      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                      L.marker([eventos.latitud, eventos.longitud]).addTo(map);
                    }, 100);
                  }
            });
        });
}


// Llama a la función para cargar publicaciones al cargar la página
cargarEventos();

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

document.addEventListener("DOMContentLoaded", function () {
  const fechaInput = document.querySelector('input[name="fecha"]');
  const hoy = new Date();
  const yyyy = hoy.getFullYear();
  const mm = String(hoy.getMonth() + 1).padStart(2, '0');
  const dd = String(hoy.getDate()).padStart(2, '0');
  const fechaHoy = `${yyyy}-${mm}-${dd}`;
  fechaInput.min = fechaHoy;
});
function publicacionConfig(event, publicacionId) {
    event.stopPropagation(); // Evitar burbujeo del evento
    const configMenu = document.getElementById(`publicacion-config-${publicacionId}`);
    configMenu.style.display = configMenu.style.display === 'block' ? 'none' : 'block';
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

function eliminarEvento(evento_id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este evento?')) return;

    fetch('eliminar_evento.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({evento_id: evento_id })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar el comentario del DOM
                const publicacionElement = document.getElementById(`post-(${evento_id})`);
                if (publicacionElement) publicacionElement.remove();
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el evento:', error);
        });
}
