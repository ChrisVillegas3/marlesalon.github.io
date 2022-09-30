<?php
// Se importan los archivos a usar
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/usuarios.php');
require_once('../helpers/mailer.php');

$correo = new Correo;

//Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    //Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();

    //Se inicia la clase correspondiente
    $usuarios = new Usuarios;

    //Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Variables para el correo a enviar.
    $codigo = rand(10000, 99999);
    $destinatario = '';
    $subject = 'Recuperación de contraseña - Marlé Salón';
    $body = '<h3 style="color: black; font-weight: normal;";>Hola, nos enteramos que has tenido problemas con tu contraseña. Para reiniciar tu contraseña ingresa el siguiente código en el área solicitada:</h3>
    <h2><b>' . $codigo . '</b></h2>
    <p style="color: black;">Si no has solicitado un reinicio de contraseña, ignora este mensaje </p>';

    // Se verifica que no haya ninguna sesión iniciada
    if (!isset($_SESSION['idUsuario'])) {
        // Se verifica la acción
        switch ($_GET['action']) {
                // Se obtiene el correo del usuario para enviar el código y guardarlo en la base
            case 'enviarCorreo':
                $_POST = $usuarios->validateForm($_POST);
                if ($usuarios->setAlias($_POST['usuario'])) {
                    if ($dataUsuario = $usuarios->obtenerCorreo()) {
                        if ($usuarios->setId($dataUsuario['id_usuario'])) {
                            if ($usuarios->saveCode($codigo)) {
                                $destinatario = $dataUsuario['correo_usuario'];
                                if ($envio = $correo->enviarCorreo($destinatario, $subject, $body)) {
                                    if ($envio['status']) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Correo enviado exitosamente.';
                                        $result['dataset'] = $dataUsuario;
                                    } else {
                                        $result['exception'] = $envio['exception'];
                                    }
                                } else {
                                    $result['exception'] = 'Ocurrió un error al enviar el correo';
                                }
                            } else {
                                $result['exception'] = 'Ha ocurrido un error al generar el código';
                            }
                        } else {
                            $result['exception'] = 'Ocurrió un error al momento de obtener el usuario';
                        }
                    } else {
                        $result['exception'] = 'Usuario no encontrado';
                    }
                } else {
                    $result['exception'] = 'Usuario no válido';
                }
                break;
                // Se verifica que el código sea el mismo guardado en la base y se resetea el código
            case 'verificarCodigo':
                $_POST = $usuarios->validateForm($_POST);
                if ($usuarios->setId($_POST['id-usuario'])) {
                    if ($usuarios->validateNaturalNumber($_POST['codigo'])) {
                        if ($usuarios->checkCode($_POST['codigo'])) {
                            $result['status'] = 1;
                            $result['message'] = 'Código correcto';
                        } else {
                            $result['exception'] = 'Código incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Código inválido';
                    }
                } else {
                    $result['exception'] = 'Usuario no válido';
                }
                break;
                // Se vuelve a verificar el código para cambiar la contraseña
            case 'cambiarContrasenia':
                $_POST = $usuarios->validateForm($_POST);
                if ($usuarios->setId($_POST['id-usuario'])) {
                    if ($usuarios->validateNaturalNumber($_POST['codigo'])) {
                        if ($usuarios->checkCode($_POST['codigo'])) {
                            if ($_POST['pwd'] == $_POST['pwd2']) {
                                if ($usuarios->setClave($_POST['pwd'])) {
                                    if (!($usuarios->checkPassword($_POST['pwd']))) {
                                        if ($usuarios->changePassword()) {
                                            if ($usuarios->setIntentos(0)) {
                                                if ($usuarios->intentos()) {
                                                    $result['status'] = 1;
                                                    $result['message'] = 'Se cambió la contraseña exitosamente.';
                                                    $usuarios->resetCode();
                                                } else {
                                                    $result['status'] = 1;
                                                    $result['message'] = Database::getException() . $usuarios->getIntentos();
                                                    $usuarios->resetCode();
                                                }
                                            } else {
                                                $result['exception'] = 'Ocurrió un error al reiniciar al usuario';
                                            }
                                            $usuarios->resetCode();
                                        } else {
                                            $usuarios['exception'] = 'No se pudo cambiar la contraseña';
                                        }
                                    } else {
                                        $result['exception'] = 'La nueva contraseña no puede ser igual a la anterior';
                                    }
                                } else {
                                    $result['exception'] = $usuarios->getPasswordError();
                                }
                            } else {
                                $result['exception'] = 'Claves diferentes';
                            }
                        } else {
                            $result['exception'] = 'Código incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Código inválido';
                    }
                } else {
                    $result['exception'] = 'Usuario no válido';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
                break;
        }
    } else {
        $result['exception'] = 'Se encontró una sesión iniciada';
    }

    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}