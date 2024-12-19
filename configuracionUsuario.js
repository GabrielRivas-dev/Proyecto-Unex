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
    document.getElementById(`${campo}-input`).style.display = 'inline';
    document.getElementById(`guardar-${campo}`).style.display = 'inline';
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
    configurationdiv.style.display = configurationdiv.style.display === 'block' ? 'none' : 'block';
}