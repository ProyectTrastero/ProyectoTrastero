<?php

//Create an instance; passing `true` enables exceptions
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
require "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\{
    BD,
    Usuario
};

// Inicializa el acceso a las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Inicializa el acceso a las variables de entorno
$views = __DIR__ . '/../vistas';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

// Establece conexión a la base de datos PDO
try {
    $bd = BD::getConexion();
} catch (PDOException $error) {
    echo $blade->run("cnxbderror", compact('error'));
    die;
}

function enviarCorreo($correo, $contraseñaRecuperada, $aliasRecuperado){
    $mail = new PHPMailer(true);
    try {
    //Server settings
    
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Nos muestra en pantalla cada acción realizada por phpmailer
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';
    $mail->SMTPAuth   = true;  
    $mail->Username   = 'emmamania@hotmail.com';
    $mail->Password   = 'secreta1;)';
    $mail->SMTPSecure = 'STARTTLS';
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('emmamania@hotmail.com', 'MiTrastero.com');
    $mail->addAddress($correo);     //Add a recipient


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Credenciales de acceso';
    $mail->Body    = 'Su alias de acceso  a MiTrastero.com es ' . $aliasRecuperado . ' y su contraseña '. $contraseñaRecuperada;
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$existe=false;
$mensaje="";
$correo;
if(isset($_POST['enviar'])){
    $correo=trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_STRING));
    $existe = Usuario::existeCorreo($bd, $correo);
    if($existe){
        $contraseña = Usuario::obtenerContraseña($bd, $correo);
        $alias= Usuario::obtenerAlias($bd, $correo);
        enviarCorreo($correo, $contraseña, $alias);
        $mensaje="Se le ha enviado un correo con sus credenciales a la dirección indicada.";
//        echo $blade->run("recuperarContraseña", compact('mensaje'));
    }else if($correo == ""){
        $mensaje="El campo correo es obligatorio.";
    }else{
        $mensaje="La direccion de correo eléctronico no se encuentra en nuestra base de datos.";
    }
    echo $blade->run("recuperarContraseña", compact('mensaje')); 
    
}else if(isset($_POST['volver'])){
    header("Location: index.php");
}else{
    echo $blade->run("recuperarContraseña");
}


