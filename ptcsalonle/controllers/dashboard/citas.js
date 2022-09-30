// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_CITAS = SERVER + 'dashboard/citas.php?action=';
const ENDPOINT_CLIENTES = SERVER + 'dashboard/clientes.php?action=readAll';
const ENDPOINT_SERVICIO = SERVER + 'dashboard/servicios.php?action=readAll';
const ENDPOINT_USUARIOS = SERVER + 'dashboard/usuarios.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_CITAS);
    // Se define una variable para establecer las opciones del componente Modal.
    let options = {
        dismissible: false,
        onOpenStart: function () {
            // Se restauran los elementos del formulario.
            document.getElementById('save-form').reset();
            // Se define un objeto con la fecha y hora actual.
            let today = new Date();
            
            document.getElementById('fecha').min=today.toLocaleDateString();
            
        }
    }
    // Se inicializa el componente Modal para que funcionen las cajas de diálogo.
    M.Modal.init(document.querySelectorAll('.modal'), options);
});

 // Función para abrir el reporte de productos.
 function openReport1() {
    // Se establece la ruta del reporte en el servidor.
    let url = SERVER + 'reports/dashboard/reservaciones.php' ;
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(url);
}
// Función para abrir el reporte de clientes.
function openReport(id) {
    // Se define una variable para inicializar los parámetros del reporte.
    let params = '?id=' + id;
    // Se establece la ruta del reporte en el servidor.
    let url = SERVER + 'reports/dashboard/reservacion_cliente.php';
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(url + params);
}


// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se establece un icono para el estado del producto.
        (row.estado_cita) ? icon = 'done_all' : icon = 'access_time';
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>
                <td>${row.nombres_cliente}</td>
                <td>${row.nombre_servicio}</td>
                <td>${row.fecha}</td>
                <td>${row.hora}</td>
                <td>${row.nombres_usuario}</td>
                <td><i class="material-icons">${icon}</i></td>
                <td>
                    <a onclick="openUpdate(${row.id_cita})" class="btn-floating waves-effect pink lighten-1 tooltipped" data-tooltip="Actualizar">
                            <i class="material-icons">mode_edit</i>
                    </a>
                    <a onclick="openDelete(${row.id_cita})" class="btn-floating waves-effect pink lighten-1 tooltipped" data-tooltip="Eliminar">
                           <i class="material-icons">delete</i>
                    </a>
                    <a onclick="openReport(${row.id_cita})" class="btn-floating waves-effect pink darken-2 tooltipped" data-tooltip="Reporte">
                         <i class="material-icons">assignment</i>
                    </a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
    // Se inicializa el componente Material Box para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_CITAS, 'search-form');
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreate() {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('save-modal')).open();
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modal-title').textContent = 'Creación de cita';

    // Se llama a la función que llena el select del formulario. Se encuentra en el archivo components.js
    fillSelect(ENDPOINT_CLIENTES, 'cliente', null);
    fillSelect(ENDPOINT_SERVICIO, 'servicio', null);
    fillSelect(ENDPOINT_USUARIOS, 'usuario', null);
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    // Se abre la caja de diálogo (modal) que contiene el formulario.
    M.Modal.getInstance(document.getElementById('save-modal')).open();
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modal-title').textContent = 'Actualizar Cita';
    // Se establece el campo de archivo como opcional.
  
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_CITAS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id').value = response.dataset.id_cita;
                    document.getElementById('fecha').value = response.dataset.fecha;
                    document.getElementById('hora').value = response.dataset.hora;
                    fillSelect(ENDPOINT_CLIENTES, 'cliente', response.dataset.id_cliente);
                    fillSelect(ENDPOINT_SERVICIO, 'servicio', response.dataset.id_servicio);
                    fillSelect(ENDPOINT_USUARIOS, 'usuario', response.dataset.id_usuario);
                    if (response.dataset.estado_cita) {
                        document.getElementById('estado').checked = true;
                    } else {
                        document.getElementById('estado').checked = false;
                    }
                    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
                    M.updateTextFields();
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    (document.getElementById('id').value) ? action = 'actualizar' : action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRow(API_CITAS, action, 'save-form', 'save-modal');
});

// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_CITAS, data);
}