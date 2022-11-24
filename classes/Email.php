<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email,$nombre,$token){
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;
    }

    public function enviarConfirmacion(){
        //Creamos el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '37206110fef52a';
        $mail->Password = '623aa5c5b688ba';
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@apppsalon.com','AppSalon.com');
        $mail->Subject='Confirma tu cuenta';

        //set html
        $mail->isHTML(true);
        $mail->CharSet='UTF-8';

        $contenido="<html>";

        $contenido.="<p><strong>Hola ".$this->nombre."</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";

        $contenido.="<p>Presiona aquí: <a href='http://localhost:8000/confirmar-cuenta?token=".$this->token."'>Confirmar cuenta</a> </p>";

        $contenido.="<p>Si no solicistaste esta cuenta, puedes ignorar el mensaje</p>";

        $contenido.="</html>";

        $mail->Body=$contenido;
        $mail->AltBody="Texto alternativo sin html";

        //Enviar email
        $mail->send();

    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '37206110fef52a';
        $mail->Password = '623aa5c5b688ba';
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@apppsalon.com','AppSalon.com');
        $mail->Subject='Reestablece tu contraseña';

        //set html
        $mail->isHTML(true);
        $mail->CharSet='UTF-8';

        $contenido="<html>";

        $contenido.="<p><strong>Hola ".$this->nombre."</strong> Has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo.</p>";

        $contenido.="<p>Presiona aquí: <a href='http://localhost:8000/recuperar?token=".$this->token."'>Reestablecer contraseña</a> </p>";

        $contenido.="<p>Si no solicistaste esta acción, puedes ignorar el mensaje</p>";

        $contenido.="</html>";

        $mail->Body=$contenido;
        $mail->AltBody="Texto alternativo sin html";

        //Enviar email
        $mail->send();
    }
}