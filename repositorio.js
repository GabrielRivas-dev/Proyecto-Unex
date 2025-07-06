let vistaActual = "mis";

function cargarRepositoriosPublicos() {
  fetch("obtener_repositorios_publicos.php")
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("repositorios");
      contenedor.innerHTML = "";

      if (data.length === 0) {
        contenedor.innerHTML = "<p>No has subido ningún repositorio.</p>";
        return;
      }

      data.forEach(repo => {
        const div = document.createElement("div");
        div.classList.add("repositorio-item");

        const archivosHTML = repo.archivos.map(archivo => {
          const ext = archivo.tipo.toLowerCase();
          if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
            return `<a href="${archivo.ruta}" download="${archivo.nombre_original}"><img src="${archivo.ruta}" alt="imagen" style="max-width:200px; max-height:200px"></a>`;
          } else if (ext === "pdf") {
            return `<embed src="${archivo.ruta}" type="application/pdf" width="100%" height="200px">`;
          } else {
            return `<p>📄 <a href="${archivo.ruta}" style="color:var(--color-texto);" download="${archivo.nombre_original}">${archivo.nombre_original}</a></p>`;
          }
          

        }).join("");

        div.innerHTML = `
          <h3>${repo.titulo}</h3>
          <p>${repo.descripcion}</p>
          <button onclick="mostrarArchivos(${repo.id}, this)">Ver archivos</button>
          <a class="link-descargar" href="descargar_zip.php?repo_id=${repo.id}">descargar repositorio</a>
          <div class="archivos" id="archivos-${repo.id}" style="display:none;">${archivosHTML}</div>
          <p style="color:gray;"><small>${repo.fecha}</small> - Subido por ${repo.nombre} ${repo.apellido}</p>
        `;

        contenedor.appendChild(div);
      });
    });
}

function alternarVistaRepositorio() {
  const boton = document.getElementById("boton-vista-repositorio");
  if (vistaActual === "mis") {
    cargarRepositoriosPublicos();
    vistaActual = "publicos";
    boton.textContent = "🔒 Ver Mis Repositorios";
  } else {
    cargarMisRepositorios();
    vistaActual = "mis";
    boton.textContent = "🌐 Ver Repositorios Públicos";
  }
}

// Botón alternar
const btnToggle = document.createElement("button");
btnToggle.id = "boton-vista-repositorio";
btnToggle.textContent = "🌐 Ver Repositorios Públicos";
btnToggle.onclick = alternarVistaRepositorio;
document.getElementById("repositorios").before(btnToggle);

// Cargar por defecto

function cargarMisRepositorios() {
  fetch("obtener_mis_repositorios.php")
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("repositorios");
      contenedor.innerHTML = "";

      if (data.length === 0) {
        contenedor.innerHTML = "<p>No has subido ningún repositorio.</p>";
        return;
      }

      data.forEach(repo => {
        const div = document.createElement("div");
        div.classList.add("repositorio-item");

        const archivosHTML = repo.archivos.map(archivo => {
          const ext = archivo.tipo.toLowerCase();
          if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
            return `<a href="${archivo.ruta}" download="${archivo.nombre_original}"><img src="${archivo.ruta}" alt="imagen" style="width:200px; height:200px; margin-right: 10px; object-position: center;
  object-fit: cover;"></a> <input type="checkbox" class="chk-archivo" value="${archivo.id}"style="margin-right: 5px;">`;
          } else if (ext === "pdf") {
            return `<embed src="${archivo.ruta}" type="application/pdf" width="100%" height="200px"> <input type="checkbox" class="chk-archivo" value="${archivo.id}"style="margin-right: 5px;">`;
          } else {
            return `<p>📄 <a href="${archivo.ruta}" style="color:var(--color-texto);" download="${archivo.nombre_original}">${archivo.nombre_original}</a> <input type="checkbox" class="chk-archivo" value="${archivo.id}"style="margin-right: 5px;"></p> `;
          }
          

        }).join("");

        div.innerHTML = `
          <h3>${repo.titulo}</h3>
          <p>${repo.descripcion}</p>
          <button onclick="mostrarArchivos(${repo.id}, this)">Ver archivos</button>
           <button onclick="confirmarEliminacion(${repo.id})">Eliminar repositorio</button>
             <button onclick="eliminarArchivosSeleccionados()">Eliminar seleccionados</button>
          <div class="archivos" id="archivos-${repo.id}" style="display:none;">
          <form onsubmit="agregarArchivosRepositorio(event, ${repo.id})" enctype="multipart/form-data">
              <input type="file" name="nuevo_archivo[]" multiple required>
              <button type="submit">Agregar archivo(s)</button>
            </form>
            ${archivosHTML}
           
          </div>
        `;

        contenedor.appendChild(div);
      });
    });
}
function eliminarArchivosSeleccionados() {
  const seleccionados = Array.from(document.querySelectorAll(".chk-archivo:checked"))
    .map(input => input.value);

  if (seleccionados.length === 0) {
    alert("Selecciona al menos un archivo");
    return;
  }

  fetch("eliminar_archivos.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ ids: seleccionados })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
        alert("Archivo(s) eliminados(s) correctamente");
        cargarMisRepositorios();
    } else {
      alert("Error al eliminar archivos");
    }
  });
}


function mostrarArchivos(repoId, boton) {
  const archivosDiv = document.getElementById(`archivos-${repoId}`);
  const visible = archivosDiv.style.display === 'block';
  archivosDiv.style.display = visible ? 'none' : 'block';
  boton.textContent = visible ? 'Ver archivos' : 'Ocultar archivos';
}
function agregarArchivosRepositorio(event, repoId) {
  event.preventDefault();
  const form = event.target;
  const formData = new FormData(form);
  formData.append("repo_id", repoId);

  fetch("agregar_archivos_repositorio.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Archivo(s) agregado(s) correctamente");
        cargarMisRepositorios();
      } else {
        alert("Error: " + data.message);
      }
    });
}
function confirmarEliminacion(repoId) {
  if (confirm("¿Estás seguro de que deseas eliminar este repositorio? Esta acción no se puede deshacer.")) {
    window.location.href = `eliminar_repositorio.php?repo_id=${repoId}`;
  }
}

cargarMisRepositorios();


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

