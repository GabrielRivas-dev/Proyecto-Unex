let mapaIniciado = false;
let mapa, marcador;

document.getElementById('activar-mapa').addEventListener('change', function () {
    const contenedor = document.getElementById('contenedor-mapa');
  
    if (this.checked) {
      contenedor.style.display = 'block';
  
      // Solo inicializa si aún no existe
      if (!mapaIniciado) {
        mapa = L.map('map').setView([10.0, -84.0], 15); // Centro inicial ajustable
  
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
function eliminarForo(foroId) {
    if (!confirm('¿Estás seguro de que deseas eliminar este foro?')) return;

    fetch('eliminar_foro.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ foro_id: foroId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al eliminar el foro:', error);
        });
}

function seguirForo(foroId) {
  const formData = new FormData();
  formData.append("foro_id", foroId);

  fetch("seguir_foro.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    const btn = document.getElementById("btn-seguir-foro");
    if (data.following) {
      btn.textContent = "Siguiendo";
    } else {
      btn.textContent = "Seguir";
    }
  });
}

function eliminarComentario(id) {
  if (!confirm("¿Estás seguro de eliminar este comentario?")) return;

  const formData = new FormData();
  formData.append("id", id);

  fetch("eliminar_comentario_foro.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const foroId = document.getElementById("foro-id").value;
        cargarComentariosForo(foroId);
      }
      else{
        alert("Error al eliminar comentario: " + (data.message || ""));
      }
    });
}

document.addEventListener("DOMContentLoaded", function () {
  const foroIdInput = document.getElementById("foro-id");
  if (foroIdInput) {
    const foroId = foroIdInput.value;
    cargarComentariosForo(foroId);
  }
});
function comentariosPostForo(event, id) {
    event.stopPropagation();
    const respuestasDiv = document.getElementById(`respuestas-${id}`);
    const inputRespuestas= document.getElementById(`respuesta-input-${id}`);
    respuestasDiv.style.display = respuestasDiv.style.display === 'block' ? 'none' : 'block';
    inputRespuestas.style.display = inputRespuestas.style.display === 'block' ? 'none' : 'block';
}

function cargarComentariosForo(foroId) {
  fetch(`obtener_comentarios_foro.php?foro_id=${foroId}`)
    .then(res => res.json())
    .then(data => {
      const contenedor = document.getElementById("foro-comentario");
      contenedor.innerHTML = "";
      data.forEach(c => {
        const div = document.createElement("div");
        div.classList.add("foro-comentario");
        div.innerHTML = `
          <div class="foro-comentario-header">
              <img src="${c.imagen}" alt="foto-perfil">
              <p>${c.nombre} ${c.apellido}</p>
              <span>${c.fecha}</span>
            </div>
            <div class="eliminar-foro-comentario"><button <button onclick="eliminarComentario(${c.id})">&times</button></div>
            <div class="foro-comentario-mensaje"><p>${c.comentario}</p></div></div>
            <div class="foro-comentario-botones">
            <button onclick="mostrarFormularioRespuesta(${c.id})">Respuestas</button>
            </div>
           <div id="respuesta-form-${c.id}" class="respuesta-form" style="display:none;">
            <textarea id="respuesta-input-${c.id}" placeholder="Responder comentario"></textarea>
            <button onclick="enviarRespuesta(${foroId}, ${c.id})">Enviar</button>
          </div>

        `;
       
        contenedor.appendChild(div);

        // Verificar si hay respuestas y agregarlas
        if (Array.isArray(c.respuestas) && c.respuestas.length > 0) {
  const verBtn = document.createElement("button");
  verBtn.classList.add("button-repuestas");
  verBtn.textContent = `Ver ${c.respuestas.length} respuesta(s)`;
  verBtn.onclick = () => {
    rwrap.style.display = rwrap.style.display === "none" ? "block" : "none";
  };

  const rwrap = document.createElement("div");
  rwrap.classList.add("respuesta-wrapper");
  rwrap.style.display = "none";

  c.respuestas.forEach(r => {
    const rdiv = document.createElement("div");
    rdiv.classList.add("comentario-respuesta");
    rdiv.innerHTML = `
       <div class="respuesta-comentario-header">
              <img src="${r.imagen}" alt="foto-perfil">
              <p>${r.nombre} ${r.apellido}</p>
              <span>${r.fecha}</span>
            </div>
            <div class="eliminar-foro-comentario"><button <button onclick="eliminarComentario(${r.id})">&times</button></div>
            <div class="respuesta-comentario-mensaje"><p>${r.comentario}</p></div></div>
    `;
    rwrap.appendChild(rdiv);
  });

  div.appendChild(verBtn);
  div.appendChild(rwrap);
}
      });
    });
}
function mostrarFormularioRespuesta(parentId) {
  const form = document.getElementById(`respuesta-form-${parentId}`);
  form.style.display = form.style.display === "none" ? "flex" : "none";
}

function enviarRespuesta(foroId, parentId) {
  const texto = document.getElementById(`respuesta-input-${parentId}`).value;
  const formData = new FormData();
  formData.append("foro_id", foroId);
  formData.append("comentario", texto);
  formData.append("parent_id", parentId);

  fetch("guardar_comentario_foro.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        cargarComentariosForo(foroId);
      }
    });
}

function eliminarComentarioForo(comentarioId) {
    console.log(comentarioId);
    /*if (!confirm('¿Estás seguro de que deseas eliminar este comentario?')) return;

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
        });*/
}

document.getElementById("formComentarioForo").addEventListener("submit", function(e) {
  e.preventDefault();

  const comentario = document.getElementById("comentario").value;
  const foroId = document.getElementById("foro-id").value;
  const formData = new FormData();

  formData.append("foro_id", foroId);
  formData.append("comentario", comentario);

  fetch("guardar_comentario_foro.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById("comentario").value = "";
      cargarComentariosForo(foroId);
    } else {
      alert("Error al comentar: " + (data.message || ""));
    }
  })
  .catch(error => console.error("Error al enviar comentario:", error));
});


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
document.addEventListener('DOMContentLoaded', cargarSeguidos);

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

// Llama a la función para cargar publicaciones al cargar la página

function openConfiguration(){
    const configurationdiv= document.getElementById("configuration");
    configurationdiv.style.display = configurationdiv.style.display === 'block' ? 'none' : 'block';
}
//FUNCION PARA ABRIR MENU LATERAL
function openNav() {
    document.getElementById("mobile-menu").style.width = "100%";
}
function closeNav() {
    document.getElementById("mobile-menu").style.width = "0%";
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
document.getElementById("formCrearForo").addEventListener("submit", function (e) {
  e.target.querySelector("button[type='submit']").disabled = true;
});

function mostrarFormularioReporte(tipo, id) {
  const motivo = prompt("¿Por qué estás reportando este foro?");
  if (!motivo || motivo.trim() === "") {
    alert("Debes escribir un motivo para el reporte.");
    return;
  }

  fetch("reportar.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ tipo: tipo, reportado_id: id, motivo: motivo })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("Reporte enviado correctamente.");
    } else {
      alert("Error al enviar el reporte: " + data.message);
    }
  })
  .catch(error => {
    console.error("Error en reporte:", error);
  });
}
