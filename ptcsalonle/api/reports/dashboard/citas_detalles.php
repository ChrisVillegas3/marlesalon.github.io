<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/citas.php');
    require('../../models/servicios.php');

    // Se instancia el módelo citas para procesar los datos.
 
    $servicio = new Servicios;
    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($servicio->setId($_GET['id'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowServicio = $servicio->readOne()) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Detalles reservacion '.$rowServicio['nombres_servicio']);
            // Se instancia el módelo Productos para procesar los datos.
            $cita = new Citas;
            if ($cita->setServicio($rowCita['id_cita'])) {
                // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
                if ($dataCitas = $cita->citasDetalle()) {
                    // Se establece un color de relleno para los encabezados.
                    $pdf->setFillColor(225);
                    $pdf->setTextColor(40,40,40);
                    $pdf->setDrawColor(88,88,88);
                    // Se establece la fuente para los encabezados.
                    $pdf->setFont('Times', 'B', 11);
                    // Se imprimen las celdas con los encabezados.
                    $pdf->cell(40, 10, utf8_decode('Fecha'), 0, 0, 'C', 1);
                    $pdf->cell(60, 10, utf8_decode('Servicio'), 0, 0, 'C', 1);
                    $pdf->cell(35, 10, utf8_decode('Precio'), 0, 0, 'C', 1);
                    $pdf->cell(35, 10, utf8_decode('Nombres cliente'), 0, 0, 'C', 1);
                    $pdf->cell(40, 10, utf8_decode('Estado'), 0, 1, 'C', 1);
                
                    // Se establece la fuente para los datos de los productos.
                    $pdf->setFont('Times', '', 11);
                    // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                    $pdf->setFillColor(240,240,240);
                    $pdf->setTextColor(40,40,40);
                    $pdf->setDrawColor(220,220,220);
                    $pdf->setLineWidth(0.5);
                    foreach ($dataCitas as $rowCita) {
                        ($rowCita['estado_cita']) ? $estado = 'Completado' : $estado = 'Pendiente';
                        // Se imprimen las celdas con los datos de los Citas.
                    $pdf->cell(40, 10, utf8_decode($rowCita['fecha']), 1, 0);
                    $pdf->cell(60, 10, utf8_decode($rowCita['nombre_servicio']), 1, 0);
                    $pdf->cell(35, 10, utf8_decode($rowCita['precio_servicio']), 1, 0);
                    $pdf->cell(35, 10, utf8_decode($rowCita['nombres_cliente']), 1, 0);
                    }
                    $pdf->setDrawColor(88,88,88);
                    $pdf->setLineWidth(2);
                    $pdf->line(15, 65, 200, 65);
                } else {
                    $pdf->cell(0, 10, utf8_decode('No hay detalles'), 1, 1);
                }
                // Se envía el documento al navegador y se llama al método footer()
                $pdf->output('I', 'citas.pdf');
            } else {
                header('location: ../../../views/dashboard/citas.html');
            }
        } else {
            header('location: ../../../views/dashboard/citas.html');
        }
    } else {
        header('location: ../../../views/dashboard/citas.html');
    }
} else {
    header('location: ../../../views/dashboard/citas.html');
}
?>