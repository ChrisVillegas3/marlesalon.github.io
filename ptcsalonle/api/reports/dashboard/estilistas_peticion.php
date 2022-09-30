<?php
if (isset($_GET['id'])) {
require('../../helpers/dashboard_report2.php');
require('../../models/citas.php');
require('../../models/usuarios.php');

$usuario = new Usuarios;

// Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
if ($usuario->setId($_GET['id'])) {
    // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
    if ($rowUsuario = $usuario->readOne()) {
        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Reservaciones de estilista ' . $rowUsuario['nombres_usuario'] . ' ' . $rowUsuario['apellidos_usuario']); 
        // Se instancia el módelo Citas para procesar los datos.
        $cita = new Citas;
        if ($cita->setUsuario($rowUsuario['id_usuario'])) {
            // Se verifica si existen registros (Citas) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataCitas = $cita->estilistasPeticion()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(251,243,243);
                $pdf->setTextColor(40,40,40);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Times', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(40, 10, utf8_decode('Fecha'), 0, 0, 'C', 1);
                $pdf->cell(60, 10, utf8_decode('Servicio'), 0, 0, 'C', 1);
                $pdf->cell(35, 10, utf8_decode('Precio'), 0, 0, 'C', 1);
                $pdf->cell(35, 10, utf8_decode('Nombres cliente'), 0, 0, 'C', 1);
                $pdf->cell(35, 10, utf8_decode('Apellidos cliente'), 0, 0, 'C', 1);
                $pdf->cell(40, 10, utf8_decode('Estado'), 0, 1, 'C', 1);
                // Se establece la fuente para los datos de los Citas.
                $pdf->setFont('Times', '', 11);
                // Se recorren los registros ($dataCitas) fila por fila ($rowPedido).
                $pdf->setFillColor(240,240,240);
                $pdf->setDrawColor(251,223,223);
                $pdf->setTextColor(40,40,40);
                $pdf->setLineWidth(0.5);
                foreach ($dataCitas as $rowCita) {
                    ($rowCita['estado_cita']) ? $estado = 'Completado' : $estado = 'Pendiente';
                    // Se imprimen las celdas con los datos de los Citas.
                $pdf->cell(40, 10, utf8_decode($rowCita['fecha']), 1, 0);
                $pdf->cell(60, 10, utf8_decode($rowCita['nombre_servicio']), 1, 0);
                $pdf->cell(35, 10, utf8_decode($rowCita['precio_servicio']), 1, 0);
                $pdf->cell(35, 10, utf8_decode($rowCita['nombres_cliente']), 1, 0);
                $pdf->cell(35, 10, utf8_decode($rowCita['apellidos_cliente']), 1, 0);
                $pdf->cell(40, 10, $estado, 1, 1);
                }
                $pdf->setDrawColor(244,140,180);
                $pdf->setLineWidth(2);
                $pdf->line(15, 65, 259, 65);
            } else {
                $pdf->cell(0, 10, utf8_decode('No hay Citas para este usuario'), 1, 1);
            }
} else {
    $pdf->cell(0, 10, utf8_decode('Uusario inexistente'), 1, 1);
}
        // Se envía el documento al navegador y se llama al método footer()
        $pdf->output('I', 'Usuarios.pdf');
} else {
    header('../../../views/dashboard/usuarios.html');
}
} else { 
    header('../../../views/dashboard/usuarios.html');
}
}
?>