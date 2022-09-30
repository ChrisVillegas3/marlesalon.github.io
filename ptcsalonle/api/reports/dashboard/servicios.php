<?php
require('../../helpers/dashboard_report.php');
require('../../models/servicios.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Servicios');

// Se instancia el módelo Categorías para obtener los datos.
$servicio = new Servicios;
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataServicios = $servicio->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(251,243,243);
    $pdf->setTextColor(40, 40, 40);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(150, 10, utf8_decode('Nombre'), 0, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Precio (US$)'), 0, 1, 'C', 1);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);
    // Se define el color de la letra, las lineas y el fondo
    $pdf->setFillColor(240,240,240);
    $pdf->setTextColor(40,40,40);
    $pdf->setDrawColor(251,223,223);
    $pdf->setLineWidth(0.5);
    // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
    foreach ($dataServicios as $rowServicio) {
        // Se imprimen las celdas con los datos de los productos.
        $pdf->cell(150, 10, utf8_decode($rowServicio['nombre_servicio']), 1, 0);
        $pdf->cell(30, 10, utf8_decode($rowServicio['precio_servicio']), 1, 1);
    }
    $pdf->setDrawColor(244,140,180);
    $pdf->setLineWidth(2);
    $pdf->line(15, 65, 194, 65);
} else {
    $pdf->cell(0, 10, utf8_decode('No hay servicios para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'servicios.pdf');
