// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CITAS = SERVER + 'dashboard/citas.php?action=';
const API_PRODUCTOS = SERVER + 'dashboard/inventario.php?action=';

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('chart-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
   
   // Petición para obtener los datos del registro solicitado.
 fetch(API_CITAS + 'citasRangoFecha', {
    method: 'post',
    body: new FormData(document.getElementById('chart-form'))
}).then(function (request) {
    // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
    if (request.ok) {
        // Se obtiene la respuesta en formato JSON.
        request.json().then(function (response) {
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
            if (response.status) {
                      // Se declaran los arreglos para guardar los datos a graficar.
                      let meses = [];
                      let cantidades = [];
                      // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                      response.dataset.map(function (row) {
                          // Se agregan los datos a los arreglos.
                          meses.push(row.mes);
                          cantidades.push(row.cantidad);
                      });
                      document.getElementById('chart-container').innerHTML='<canvas id="chart-citas"></canvas>'
                      // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                      barGraph('chart-citas', meses, cantidades, 'Cantidad de citas', 'Cantidad de citas mensuales');
            } else {
                sweetAlert(2, response.exception, null);
            }
        });
    } else {
        console.log(request.status + ' ' + request.statusText);
    }
});
});

function openChart(){
// Se abre la caja de diálogo (modal) que contiene el formulario.
M.Modal.getInstance(document.getElementById('chart-modal')).open();

}
// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    //Graficas.
    graficoTopEstilistas();
    graficoTopServicios();
    graficoTopClientes();
    graficoCitasMeses();
    graficoPastelMarcas();
   
    // Se define un objeto con la fecha y hora actual.
    let today = new Date();
    // Se define una variable con el número de horas transcurridas en el día.
    let hour = today.getHours();
    // Se define una variable para guardar un saludo.
    let greeting = '';
    // Dependiendo del número de horas transcurridas en el día, se asigna un saludo para el usuario.
    if (hour < 12) {
        greeting = 'Buenos días';
    } else if (hour < 19) {
        greeting = 'Buenas tardes';
    } else if (hour <= 23) {
        greeting = 'Buenas noches';
    }
    // Se muestra un saludo en la página web.
    document.getElementById('greeting').textContent = greeting;
    // Se inicializa el componente Modal para que funcionen las cajas de diálogo.
    M.Modal.init(document.querySelectorAll('.modal'));

});

// Función para mostrar top 5 servicios más solicitados en un gráfico de barras.
function graficoTopClientes() {
    // Petición para obtener los datos del gráfico.
    fetch(API_CITAS + 'TopClientes', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let cliente = [];
                    let cantidades = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        cliente.push(row.nombres_cliente);
                        cantidades.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    barGraph('chart1', cliente, cantidades, 'Cantidad de clientes', 'Top 5 clientes con más citas');
                } else {
                    document.getElementById('chart1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    
}

// Función para mostrar el porcentaje de productos por marca en un gráfico de pastel.
function graficoPastelMarcas() {
    // Petición para obtener los datos del gráfico.
    fetch(API_PRODUCTOS + 'porcentajeProductosMarca', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a gráficar.
                    let marcas = [];
                    let porcentajes = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        marcas.push(row.nombre_marcas);
                        porcentajes.push(row.porcentaje);
                    });
                    // Se llama a la función que genera y muestra un gráfico de pastel. Se encuentra en el archivo components.js
                    pieGraph('chart2', marcas, porcentajes, 'Porcentaje de productos por marca');
                } else {
                    document.getElementById('chart2').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}
// Función para mostrar top 5 productos mas vendidos en un mes en un gráfico de barras.
function graficoTopEstilistas() {
    // Petición para obtener los datos del gráfico.
    fetch(API_CITAS + 'TopEstilistas', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let usuario = [];
                    let cantidades = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        usuario.push(row.nombres_usuario);
                        cantidades.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    polarAreaGraph('chart3', usuario, cantidades, 'Cantidad de citas', 'Top 5 Estilistas más solicitados');
                } else {
                    document.getElementById('chart3').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    
}

// Función para mostrar top 5 servicios mas solicitados en un gráfico de barras.
function graficoTopServicios() {
    // Petición para obtener los datos del gráfico.
    fetch(API_CITAS + 'TopServicios', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let servicio = [];
                    let cantidades = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        servicio.push(row.nombre_servicio);
                        cantidades.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    doughnutGraph('chart4', servicio, cantidades, 'Cantidad de servicios', 'Top 5 servicios más solicitados');
                } else {
                    document.getElementById('chart4').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    
}


function graficoCitasMeses() {
    // Petición para obtener los datos del gráfico.
    fetch(API_CITAS + 'CitasMeses', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let fecha = [];
                    let cantidades = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        fecha.push(row.fecha);
                        cantidades.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    lineGraph('chart5', fecha, cantidades, 'Cantidad de citas ', 'Cantidad de citas en los ultimos dias');
                } else {
                    document.getElementById('chart5').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
    
}

