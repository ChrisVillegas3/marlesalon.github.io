<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/clientes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $clientes = new Clientes;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            //Se Leen todos los datos que hay en la tabla
            case 'readAll':
                if ($result['dataset'] = $clientes->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                // Se encarga de buscar lso datos en el campo
                case 'search':
                    $_POST = $clientes->validateForm($_POST);
                    if ($_POST['search'] == '') {
                        $result['exception'] = 'Ingrese un valor para buscar';
                    } elseif ($result['dataset'] = $clientes->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Valor encontrado';
                    } elseif (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                    break;
                    
                        // Para crear nuevos clientes

                    case 'create':
                        $_POST = $clientes->validateForm($_POST);
                        if (!$clientes->setNombres($_POST['nombres'])) {
                            $result['exception'] = 'Nombre incorrecto';
                        } elseif (!$clientes->setApellidos($_POST['apellidos'])) {
                            $result['exception'] = 'Apellido incorrecto';
                        } elseif (!$clientes->setTelefono($_POST['telefono'])) {
                            $result['exception'] = 'Telefono incorrecto';
                        } elseif ($clientes->createRow()) {
                            $result['status'] = 1;
                            $result['message'] = 'Cliente creado correctamente';
                        } else {
                            $result['exception'] = Database::getException();;
                        }
                        break;

                        // Lee una fila en especifico
                        case 'readOne':
                            if (!$clientes->setId($_POST['id'])) {
                                $result['exception'] = 'clientes incorrecto';
                            } elseif ($result['dataset'] = $clientes->readOne()) {
                                $result['status'] = 1;
                            } elseif (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                $result['exception'] = 'clientes inexistente';
                            }
                            break;
                            
                            // Actualiza los datos del objeto en la tabla
                        case 'update':
                            $_POST = $clientes->validateForm($_POST);
                            if (!$clientes->setId($_POST['id'])) {
                                $result['exception'] = 'clientes incorrecto';
                            } elseif (!$clientes->readOne()) {
                                $result['exception'] = 'clientes inexistente';
                            } elseif (!$clientes->setNombres($_POST['nombres'])) {
                                $result['exception'] = 'Nombre incorrecto';
                            } elseif (!$clientes->setApellidos($_POST['apellidos'])) {
                                $result['exception'] = 'Apellido incorrecto';

                            } elseif (!$clientes->setTelefono($_POST['telefono'])) {
                                $result['exception'] = 'telefono incorrecto';
                           
                            } elseif ($clientes->updateRow()) {
                                $result['status'] = 1;
                                $result['message'] = 'cliente modificado correctamente';
                            } else {
                                $result['exception'] = Database::getException();
                            }
                            break;


                // Borra los datos de los objetos en la tabla 
            case 'delete':
                if (!$clientes->setId($_POST['id'])) {
                    $result['exception'] = 'Cliente incorrecta';
                } elseif (!$data = $clientes->readOne()) {
                    $result['exception'] = 'Cliente inexistente';
                } elseif ($clientes->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'cliente eliminado correctamente';
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
