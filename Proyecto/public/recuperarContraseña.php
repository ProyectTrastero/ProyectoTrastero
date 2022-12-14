<?php

//Create an instance; passing `true` enables exceptions

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

$enviado=false;
//Función que envía un correo electrónico usando el protocolo SMTP de Outlook
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
    $mail->addAddress($correo);    


    //Content
    $mail->isHTML(true);                                 
    $mail->Subject = 'Credenciales de acceso';
    $mail->Body    = 'Sus credenciales de acceso  a MiTrastero.com son :<br>Usuario: ' . $aliasRecuperado . '<br> Contraseña: '. $contraseñaRecuperada;
    $mail->send();
    
    
  
    
    
  
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
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
//$mievp = Usuario::
$existe=false;
$mensaje="";


$correo;
//if(isset($_POST['enviar'])){
    $correo=trim(filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_STRING));
    $existe = Usuario::existeCorreo($bd, $correo);
    
    //Si existe el correo se genera mensaje de petición realizada correctamente.
    //Por cuestiones de seguridad solo se proporciona información de que la petición se ha generado correctamente.
    if($existe){
        $contraseña = Usuario::obtenerContraseña($bd, $correo) ;
        $alias= Usuario::obtenerAlias($bd, $correo);
        enviarCorreo($correo, $contraseña, $alias);
        $mensaje="Su petición se ha generado correctamente. Si el email es correcto se le enviarán sus credenciales al correo proporcionado.";
    //Si el correo está vacío se genera mensaje de campo obligatorio.
    }else if($correo == ""){
        $mensaje="El campo correo es obligatorio.";
    //Si no existe el correo se envía también mensaje de que la petición se ha realizado con éxito. Aunque en este caso no haya envío de correo 
    //ya que el email facilitado no se encuentra en la bbdd.
    }else{
        $mensaje="Su petición se ha generado correctamente. Si el email es correcto se le enviarán sus credenciales al correo proporcionado.";
    }

    $response=[];
    //Respuesta AJAX
    try {
        $response['mensaje']=$mensaje;  
    } catch (Exception $ex) {
        $response['error'] = true;
    }
    
    header('Content-type: application/json');
    echo json_encode($response);
    
    die;  


    $response=[];
 
    try {
        $response['mensaje']=$mensaje;  
    } catch (Exception $ex) {
        $response['error'] = true;
    }
    
    header('Content-type: application/json');
    echo json_encode($response);
    
    die;  


