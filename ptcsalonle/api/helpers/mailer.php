<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('../libraries/phpmailer/src/Exception.php');
require('../libraries/phpmailer/src/PHPMailer.php');
require('../libraries/phpmailer/src/SMTP.php');


class Correo
{

    // Función para enviar correo, recibe el destinatario, asunto y cuerpo del correo
    public function enviarCorreo($destinatario, $subject, $body)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        // Se crea un array de retorno
        $result = array('status' => 0, 'message' => null, 'exception' => null);
        try {
            // Ajustes del servidor

            // Se setea el idioma para mostrar los errores
            $mail->setLanguage('es');
            // Enviar usando SMTP
            $mail->isSMTP();
            // Servidor por el cual se envía el correo (en este caso, gmail)
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            // Usuario del correo
            $mail->Username = 'marlesaloninc@gmail.com';
            // Contraseña del correo
            $mail->Password = 'jaxhfkqmgoxvkpur';
            // Se establece el encriptado y el puerto
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Emisor
            $mail->setFrom('marlesaloninc@gmail.com', 'Marle Salon');

            // Destinatario
            $mail->addAddress($destinatario);

            // Contenido del correo 

            // Se setea el contenido del correo como HTML
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->CharSet = 'UTF-8';
            //Se envía el correo
            $mail->send();
            $result['status'] = 1;
            $result['message'] = 'Correo enviado';
            return $result;
        } catch (Exception $e) {
            $result['status'] = 0;
            $result['exception'] = 'Error al enviar el correo: ' . $mail->ErrorInfo;
            return $result;
        }
    }
}
