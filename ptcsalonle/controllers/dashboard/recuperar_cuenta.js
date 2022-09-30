
// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_RECUPERACION = SERVER + 'dashboard/recuperacion.php?action=';

//MÉTODO QUE AGREGA UN CONTROLADOR DE EVENTOS CUANDO EL CONTENIDO DEL DOCUMENTO HA SIDO CARGADO
document.addEventListener('DOMContentLoaded', function () {

    //INICIALIZADOR DEL NAVBAR  
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);

    //INICIALIZADOR DEL MODAL
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
});

document.getElementById('form-usuario').addEventListener('submit', function (event) {
    // Se evita recargar la página cuando se envía el formulario
    event.preventDefault();

    document.getElementById("enviar-codigo").disabled = true;
    fetch(API_RECUPERACION + 'enviarCorreo', {
        method: 'post',
        body: new FormData(document.getElementById('form-usuario'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, null);
                    document.getElementById("enviar-codigo").innerHTML = 'Enviar de nuevo <i class="material-icons right">refresh</i>';
                    document.getElementById("verificar-codigo").disabled = false;
                    document.getElementById("codigo").disabled = false;
                    document.getElementById("id-usuario").value = response.dataset.id_usuario;
                    document.getElementById("enviar-codigo").disabled = true;
                    setTimeout(function () {
                        document.getElementById("enviar-codigo").disabled = false;
                    }, 20000);
                } else {
                    sweetAlert(2, response.exception, null);
                    document.getElementById("enviar-codigo").innerHTML = 'Enviar de nuevo <i class="material-icons right">refresh</i>';
                    document.getElementById("enviar-codigo").disabled = false;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
});

document.getElementById('codigo-form').addEventListener('submit', function (event) {
    // Se evita recargar la página cuando se envía el formulario
    event.preventDefault();

    document.getElementById("verificar-codigo").disabled = true;
    fetch(API_RECUPERACION + 'verificarCodigo', {
        method: 'post',
        body: new FormData(document.getElementById('codigo-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, null);
                    document.getElementById('cambiar-contrasenia').disabled = false;
                    document.getElementById("pwd").disabled = false;
                    document.getElementById("pwd2").disabled = false;
                    document.getElementById("verificar-codigo").disabled = true;
                    document.getElementById("codigo").disabled = true;
                } else {
                    sweetAlert(2, response.exception, null);
                    document.getElementById("verificar-codigo").disabled = false;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
});

document.getElementById('contrasenia-form').addEventListener('submit', function (event) {
    // Se evita recargar la página cuando se envía el formulario
    event.preventDefault();

    document.getElementById('cambiar-contrasenia').disabled = true;
    if (document.getElementById("pwd").value == document.getElementById("pwd2").value) {
        // Se cambia la clase de los input para mostrar el mensaje de error
        document.getElementById("pwd").classList.add("valid");
        document.getElementById("pwd2").classList.add("valid");
        document.getElementById("pwd").classList.remove("invalid");
        document.getElementById("pwd2").classList.remove("invalid");
        data = new FormData(document.getElementById('contrasenia-form'));
        data.append('codigo', document.getElementById('codigo').value);
        data.append('id-usuario', document.getElementById('id-usuario').value);
        fetch(API_RECUPERACION + 'cambiarContrasenia', {
            method: 'post',
            body: data
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        sweetAlert(1, response.message, 'index.html');
                        document.getElementById('cambiar-contrasenia').disabled = true;
                        document.getElementById("pwd").disabled = true;
                        document.getElementById("pwd2").disabled = true;
                        document.getElementById("verificar-codigo").disabled = true;
                        document.getElementById("codigo").disabled = true;
                        document.getElementById("codigo").value = '';
                        document.getElementById("pwd").value = '';
                        document.getElementById("pwd2").value = '';
                    } else {
                        sweetAlert(2, response.exception, null);
                        document.getElementById('cambiar-contrasenia').disabled = false;
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        }).catch(function (error) {
            console.log(error);
        });
    } else {
        // Se cambia la clase de los input para mostrar el mensaje de error
        document.getElementById("pwd").classList.remove("valid");
        document.getElementById("pwd2").classList.remove("valid");
        document.getElementById("pwd").classList.add("invalid");
        document.getElementById("pwd2").classList.add("invalid");
        document.getElementById('cambiar-contrasenia').disabled = false;
    }

});