<?php
require('../../helpers/dashboard_report2.php');
require('../../models/citas.php');
require('../../models/servicios.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Citas realizadas');

// Se instancia el módelo Categorías para obtener los datos.
$cita = new Citas;
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataCitas = $cita->citasRealizadas()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(251,243,243);
    $pdf->setTextColor(40,40,40);
    $pdf->setDrawColor(244,140,180);
    $pdf->setLineWidth(2);
    $pdf->line(15, 66, 254, 66);
     // Se declara la variable para sumar
     $total = 0;
     $precio = 0;
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(30, 10, utf8_decode('Fecha'), 0, 0, 'C', 1);
    $pdf->cell(50, 10, utf8_decode('Servicio'), 0, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Precio (US$)'), 0, 0, 'C', 1);
    $pdf->cell(40, 10, utf8_decode('Cliente'), 0, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Hora'), 0, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Estilista'), 0, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Estado'), 0, 1, 'C', 1);
    
    
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);
    // Se define el color de la letra, las lineas y el fondo
    $pdf->setFillColor(240,240,240);
    $pdf->setTextColor(40,40,40);
    $pdf->setDrawColor(251,223,223);
    $pdf->setLineWidth(0.5);
// Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
foreach ($dataCitas as $rowCita) {
    ($rowCita['estado_cita']) ? $estado = 'Completado' : $estado = 'En espera';
    // Se imprimen las celdas con los datos de los productos.
    $pdf->cell(30, 10, utf8_decode($rowCita['fecha']), 1, 0);
    $pdf->cell(50, 10, utf8_decode($rowCita['nombre_servicio']), 1, 0);
    $pdf->cell(30, 10, $rowCita['precio_servicio'], 1, 0);
    $pdf->cell(40, 10, utf8_decode($rowCita['nombres_cliente']), 1, 0);
    $pdf->cell(30, 10, utf8_decode($rowCita['hora']), 1, 0);
    $pdf->cell(30, 10, utf8_decode($rowCita['nombres_usuario']), 1, 0);
    $pdf->cell(30, 10, $estado, 1, 1);
   
    // Se calculan los valores que tendran las variables.
 
    $precio += utf8_decode($rowCita['precio_servicio']);
    }
    
    } else {
    $pdf->cell(0, 10, utf8_decode('No hay reservaciones'), 1, 1);
    }
    //Se establece total
    $pdf->ln(20);
    $pdf->setTextColor(0);
    $pdf->cell(116);
    $pdf->cell(60, 10, utf8_decode('Total'), 0, 1, 'C', 1);
    $pdf->setTextColor(0);
    $pdf->cell(116);
    $pdf->cell(60, 10, '$' . $precio, 'B', 1, 'C');
    

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'reservaciones.pdf');