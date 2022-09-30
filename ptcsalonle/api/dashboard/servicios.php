<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/servicios.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $servicio = new Servicios;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            //Se Leen todos los datos que hay en la tabla
            case 'readAll':
                if ($result['dataset'] = $servicio->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                // Se encarga de buscar los datos en el campo
            case 'search':
                $_POST = $servicio->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $servicio->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
                // Crea Nuevos objetos en la tabla
            case 'create':
                $_POST = $servicio->validateForm($_POST);
                if (!$servicio->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$servicio->setPrecio($_POST['precio'])) {
                    $result['exception'] = 'Precio incorrecto';
                } elseif ($servicio->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Servicio creado correctamente';
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
                // Lee un dato en especifico de la tabla
            case 'readOne':
                if (!$servicio->setId($_POST['id'])) {
                    $result['exception'] = 'Servicio incorrecto';
                } elseif ($result['dataset'] = $servicio->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Servicio inexistente';
                }
                break;
                // Actualiza los datos del objeto en la tabla
            case 'update':
                $_POST = $servicio->validateForm($_POST);
                if (!$servicio->setId($_POST['id'])) {
                    $result['exception'] = 'Servicio incorrecto';
                } elseif (!$servicio->readOne()) {
                    $result['exception'] = 'Servicio inexistente';
                } elseif (!$servicio->setNombre($_POST['nombre'])) {
                    $result['exception'] = 'Nombre incorrecto';
                } elseif (!$servicio->setPrecio($_POST['precio'])) {
                    $result['exception'] = 'Precio incorrecto';
               
                } elseif ($servicio->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Servicio modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                 // Borra los datos de los objetos en la tabla 
            case 'delete':
                if (!$servicio->setId($_POST['id'])) {
                    $result['exception'] = 'Servicio incorrecto';
                } elseif (!$data = $servicio->readOne()) {
                    $result['exception'] = 'Servicio inexistente';
                } elseif ($servicio->deleteRow()) {
                    $result['status'] = 1;
                  
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
