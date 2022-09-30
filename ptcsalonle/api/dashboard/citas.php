<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/citas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cita = new Citas;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //Se Leen todos los datos que hay en la tabla
            case 'readAll':
                if ($result['dataset'] = $cita->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                // Se encarga de buscar los datos en el campo
            case 'search':
                $_POST = $cita->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $cita->searchRows($_POST['search'])) {
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
                $_POST = $cita->validateForm($_POST);
                if (!$cita->setHora($_POST['hora'])) {
                    $result['exception'] = 'Hora incorrecta';
                } elseif (!$cita->setFecha($_POST['fecha'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$cita->setCliente($_POST['cliente'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif (!$cita->setServicio($_POST['servicio'])) {
                    $result['exception'] = 'Servicio incorrecto';
                } elseif (!$cita->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$cita->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif ($cita->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cita creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

                // Lee un dato en especifico de la tabla
            case 'readOne':
                if (!$cita->setId($_POST['id'])) {
                    $result['exception'] = 'Cita incorrecta';
                } elseif ($result['dataset'] = $cita->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Cita inexistente';
                }
                break;


                // Actualiza un dato
            case 'actualizar':
                $_POST = $cita->validateForm($_POST);
                if (!$cita->setId($_POST['id'])) {
                    $result['exception'] = 'Cita incorrecta';
                } elseif (!$cita->readOne()) {
                    $result['exception'] = 'Cliente inexistente';
                } elseif (!$cita->setHora($_POST['hora'])) {
                    $result['exception'] = 'Hora incorrecta';
                } elseif (!$cita->setFecha($_POST['fecha'])) {
                    $result['exception'] = 'Fecha incorrecta';
                } elseif (!$cita->setCliente($_POST['cliente'])) {
                    $result['exception'] = 'Cliente incorrecto';
                } elseif (!$cita->setServicio($_POST['servicio'])) {
                    $result['exception'] = 'Servicio incorrecto';
                } elseif (!$cita->setUsuario($_POST['usuario'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif (!$cita->setEstado(isset($_POST['estado']) ? 1 : 0)) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif ($cita->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cita modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;

                // Grafico top 5 Estilistas
                case 'TopEstilistas':
                if ($result['dataset'] = $cita->TopEstilistas()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                    }
                break;
                
                // Grafico citas por mes
                case 'CitasMeses':
                if ($result['dataset'] = $cita->CitasMeses()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;

                // Grafico top 5 servicios
                case 'TopServicios':
                    if ($result['dataset'] = $cita->TopServicios()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'No hay datos disponibles';
                        }
                    break;

                    // Grafico top 5 clientes
                case 'TopClientes':
                    if ($result['dataset'] = $cita->TopClientes()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = 'No hay datos disponibles';
                        }
                    break;
                
                // Elimina un dato
                case 'delete':
                    if (!$cita->setId($_POST['id'])) {
                        $result['exception'] = 'Cita incorrecta';
                    } elseif (!$data = $cita->readOne()) {
                        $result['exception'] = 'Cita inexistente';
                    } elseif ($cita->deleteRow()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = Database::getException();
                    }
                    break;

                case 'citasRangoFecha':
                    if (!$cita->setFechaInicio($_POST['fecha_inicio'])) {
                        $result['exception'] = 'Fecha inicial incorrecta';
                    } elseif (!$cita->setFechaFin($_POST['fecha_fin'])) {
                        $result['exception'] = 'Fecha final incorrrecta';
                    } elseif ($result['dataset'] = $cita->citasRangoFechas()) {
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
