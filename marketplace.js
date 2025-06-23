document.getElementById("buscador-productos").addEventListener("input", function () {
  const query = this.value.trim();
  buscarProductos(query);
});
function buscarProductos(query) {
  fetch(`buscar_productos.php?query=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(productos => {
      const contenedor = document.getElementById("publicacionesMarketplace");
      contenedor.innerHTML = "";

      productos.forEach(producto => {
        const div = document.createElement("div");
        div.classList.add("item");
        div.onclick = () => abrirModalProducto(producto);
        div.innerHTML = `
     <div class="content">
            <div class="venta-header">${producto.titulo}</div>
            <div class="venta-imagen">
              <img src="${producto.imagen || 'uploads/default.jpg'}" alt="">
            </div>
            <div class="venta-info">
              <p>${producto.precio}$</p>
              <p>${producto.nombre} ${producto.apellido}</p>
            </div>
          </div>
        `;
        contenedor.appendChild(div);
      });
    });
}

function eliminarVenta(ventaid) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta venta?')) return;

    fetch('eliminar_venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ venta_id: ventaid })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar el comentario del DOM
                const publicacionElement = document.getElementById(`venta-(${ventaid})`);
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


function abrirVentas(){
  const principal= document.getElementById("unexShop");
  const segundario= document.getElementById("productos-propios");

  segundario.style.display="block";
  principal.style.display="none";

  cargarTusproductos();
}
function cerrarVentas(){
  const principal= document.getElementById("unexShop");
  const segundario= document.getElementById("productos-propios");

  segundario.style.display="none";
  principal.style.display="block";
}
function cargarTusproductos(){
  fetch("mis_productos.php")
    .then(res => res.json())
    .then(misProductos => {
      const contenedor = document.getElementById("tusVentas");
      
      contenedor.innerHTML = "";

      misProductos.forEach(producto => {
        const div = document.createElement("div");
        div.classList.add("producto");
        div.id = `venta-(${producto.id})`;
        div.innerHTML = `
              <img src="${producto.imagen || 'uploads/default.jpg'}" alt="">
              <p>${producto.titulo}</p>
              <p>${producto.precio}$</p>
              <p>${producto.descripcion}</p>
              <p>${producto.fecha}</p>
              <button onclick="eliminarVenta(${producto.id})">Eliminar</button>
        `;
        contenedor.appendChild(div);
      });
    });
  }

function cargarProductos() {
  fetch("obtener_productos.php")
    .then(res => res.json())
    .then(productos => {
      const contenedor = document.getElementById("publicacionesMarketplace");
      contenedor.innerHTML = "";

      productos.forEach(producto => {
        const div = document.createElement("div");
        div.classList.add("item");
         div.onclick = () => abrirModalProducto(producto);
        div.innerHTML = `
          <div class="content">
            <div class="venta-header">${producto.titulo}</div>
            <div class="venta-imagen">
              <img src="${producto.imagen || 'uploads/default.jpg'}" alt="">
            </div>
            <div class="venta-info">
              <p>${producto.precio}$</p>
              <p>${producto.nombre} ${producto.apellido}</p>
            </div>
          </div>
        `;
        contenedor.appendChild(div);
      });
    });
}
function abrirModalProducto(producto) {
   window.scrollTo({ top: 200, behavior: 'smooth' });
  document.getElementById("modal-titulo").textContent = producto.titulo;
  document.getElementById("modal-imagen").src = producto.imagen;
  document.getElementById("modal-descripcion").textContent = producto.descripcion;
document.getElementById("modal-fecha").textContent =producto.fecha;
  document.getElementById("modal-precio").textContent = producto.precio;
  document.getElementById("modal-vendedor").textContent = `${producto.nombre} ${producto.apellido}`;
  document.getElementById("modal-contactar").onclick = () => 
  contactarVendedor(producto.usuario_id, producto.id, producto.titulo);
  document.getElementById("modal-reportar").onclick = () => mostrarFormularioReporte('venta', producto.id);
  document.getElementById("modal-producto").style.display = "block";
  
}

function cerrarModalProducto() {
  document.getElementById("modal-producto").style.display = "none";
}

cargarProductos();

function contactarVendedor(vendedorId, productoId, titulo) {
  const tituloEncoded = encodeURIComponent(titulo);
  window.location.href = `mensajeria.php?receptor_id=${vendedorId}&producto_id=${productoId}&titulo=${tituloEncoded}`;
}


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

