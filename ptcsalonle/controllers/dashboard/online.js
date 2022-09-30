/*
   Controlador de uso general en las páginas web del sitio privado cuando se ha iniciado sesión.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para establecer la ruta y parámetros de comunicación con la API.
const API = SERVER + 'dashboard/usuarios.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    fetch(API + 'getUser', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se revisa si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
                if (response.session) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se direcciona a la página web principal.
                    if (response.status) {
                        const header = `
                            <div class="navbar-fixed" >
                                <nav class="red lighten-4">
                                    <div class="nav-wrapper">
                                        <a href="main.html" class="brand-logo"><img src="${SERVER}images/logo.png" height="60"></a>
                                        <a href="#" data-target="mobile-menu" class="sidenav-trigger black-text"><i class="material-icons">menu</i></a>
                                        <ul class="right hide-on-med-and-down">
                                        <li><a class="black-text" href="inventario.html"><i class="material-icons left">ballot</i>Inventario</a></li>
                                        <li><a class="black-text" href="citas.html"><i class="material-icons left">date_range</i>Reservaciones</a></li>
                                        <li><a class="black-text" href="clientes.html"><i class="material-icons left">face_2</i>Clientes</a></li>
                                        <li><a class="black-text" href="servicios.html"><i class="material-icons left">content_cut</i>Servicios</a></li>
                                        <li><a class="black-text" href="usuarios.html"><i class="material-icons left">people_alt</i>Usuarios</a></li>
                                        
                                            <li>
                                                <a href="#" class="dropdown-trigger black-text" data-target="desktop-dropdown">
                                                    <i class="material-icons right">account_circle</i>Cuenta: <b>${response.username}</b>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                                <ul id="desktop-dropdown" class="dropdown-content black-text">
                                    <li><a href="perfil.html"></i>Ver perfil</a></li>
                                    <li><a href="contra.html"></i>Cambiar contraseña</a></li>
                                    <li><a onclick="logOut()"><i class="material-icons black-text">power_settings_new</i>Cerrar sesión</a></li>
                                </ul>
                            </div>
                            <ul id="mobile-menu" class="sidenav"
                            <a href="main.html"><img class="circle" src="../../resources/img/logo.png" height="90"></a>
                                <li><a class="black-text" href="inventario.html"></i>Inventario</a></li>
                                <li><a class="black-text" href="citas.html"></i>Reservaciones</a></li>
                                <li><a class="black-text" href="clientes.html"></i>Clientes</a></li>
                                <li><a class="black-text" href="servicios.html"></i>Servicios</a></li>
                                <li><a class="black-text" href="usuarios.html"></i>Usuarios</a></li>
                                <li>
                                    <a class="dropdown-trigger" href="#" data-target="mobile-dropdown">
                                        <i class="material-icons">account_circle</i>Cuenta: <b>${response.username}</b>
                                    </a>
                                </li>
                            </ul>
                            <ul id="mobile-dropdown" class="dropdown-content black-text">
                                <li><a href="perfil.html">Editar perfil</a></li>
                                <li><a href="contra.html">Cambiar contraseña</a></li>
                                <li><a onclick="logOut()"><i class="material-icons black-text">power_settings_new</i>Cerrar sesión</a></li>
                            </ul>
                        `;
                        const footer = `
                           
                        <div class="container center-align">
                            <FONT COLOR="black"><span>© 2022 Marlé Salon
                           
                            </span>
                            </FONT>
                    
                    </div>
                 
                        `;
                        document.querySelector('header').innerHTML = header;
                        document.querySelector('footer').innerHTML = footer;
                        // Se inicializa el componente Dropdown para que funcione la lista desplegable en los menús.
                        M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'));
                        // Se inicializa el componente Sidenav para que funcione la navegación lateral.
                        M.Sidenav.init(document.querySelectorAll('.sidenav'));
                    } else {
                        sweetAlert(3, response.exception, 'index.html');
                    }
                } else {
                    location.href = 'index.html';
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});