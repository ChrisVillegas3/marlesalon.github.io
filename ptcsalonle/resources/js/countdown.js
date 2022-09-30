var inactivityTime = function () {
    var time;
    // DOM Events
    document.onmousemove = resetTimer;
    document.onkeydown = resetTimer;
    
    //Funcion para cerrar sesion por inactividad
    function logout() {
        fetch(API + 'logOut', {
            method: 'get'
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
            if (request.ok) {
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        sweetAlert(1, response.message, 'index.html');
                    } else {
                        sweetAlert(2, response.exception, null);
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        }).catch(function (error) {
            console.log(error);
        });    
    }
    //Se coloca la cantidad de tiempo para cerrar sesion (5 minutos)
    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 300000)
        // 1000 milliseconds = 1 second
    }
};

window.onload = function() {
    inactivityTime();
}