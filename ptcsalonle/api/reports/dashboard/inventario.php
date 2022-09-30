<?php
require('../../helpers/dashboard_report.php');
require('../../models/inventario.php');
require('../../models/marcas.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Productos por marcas');

// Se instancia el módelo Categorías para obtener los datos.
$marca = new Marcas;
// Se verifica si existen registros (marca) para mostrar, de lo contrario se imprime un mensaje.
if ($dataMarcas = $marca->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(251,243,243);
    $pdf->setTextColor(40, 40, 40);
   
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Times', 'B', 11);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(156, 10, utf8_decode('Nombre'), 0, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Cantidad'), 0, 1, 'C', 1);
    
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Times', '', 11);

    // Se recorren los registros ($dataMarcas) fila por fila ($rowMarca).
    foreach ($dataMarcas as $rowMarca) {
        // Se imprime una celda con el nombre de la marca.
        $pdf->cell(0, 10, utf8_decode('Marca: '.$rowMarca['nombre_marcas']), 0, 1, 'C', 0);
        // Se instancia el módelo Productos para procesar los datos.
        $producto = new Productos;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($producto->setMarca($rowMarca['id_marca'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $producto->productosMarca()) {
                // Se define el color de la letra, las lineas y el fondo
                $pdf->setFillColor(240,240,240);
                $pdf->setTextColor(40,40,40);
                $pdf->setDrawColor(251,223,223);
                $pdf->setLineWidth(0.5);

                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->cell(156, 10, utf8_decode($rowProducto['nombre_producto']), 1, 0);
                    $pdf->cell(30, 10, $rowProducto['cantidad_producto'], 1, 1);    
                }
                $pdf->setDrawColor(244,140,180);
                $pdf->setLineWidth(2);
                $pdf->line(15, 65, 200, 65);

            } else {
                $pdf->cell(0, 10, utf8_decode('No hay productos para esta marca'), 1, 1);
            }
        } else {
            $pdf->cell(0, 10, utf8_decode('Marca incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, utf8_decode('No hay marcas para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'inventario.pdf');
