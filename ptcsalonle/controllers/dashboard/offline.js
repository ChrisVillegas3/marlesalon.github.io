/*
*   Controlador de uso general en las páginas web del sitio privado cuando no se ha iniciado sesión.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    const header = `
    
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
});