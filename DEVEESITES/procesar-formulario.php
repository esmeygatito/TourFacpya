<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ReCaptcha
    $recaptchaSecret = '6Lc2ShUrAAAAAEvS2sU1vMsdkLjA8vy2EMAhquBl';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptchaResponse)) {
        echo "Por favor confirma que no eres un robot.";
        exit();
    }

    // Verifica reCAPTCHA usando cURL
    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ]));
    $verificacion = curl_exec($ch);
    curl_close($ch);

    $captcha = json_decode($verificacion);

    if (!$captcha || empty($captcha->success)) {
        echo "La validación del CAPTCHA falló. Por favor, inténtalo de nuevo.";
        exit();
    }

    // Datos del formulario
    $nombre = htmlspecialchars($_POST["Nombre"] ?? '');
    $email = htmlspecialchars($_POST["Email"] ?? '');
    $tipo = htmlspecialchars($_POST["Tipo-Solicitud"] ?? '');
    $mensaje = htmlspecialchars($_POST["Mensaje"] ?? '');

    // Envío de correo
    $to = "contacto@deveesites.com";
    $subject = "Nuevo mensaje de $nombre";
    $body = "Nombre: $nombre\nCorreo: $email\nTipo: $tipo\n\nMensaje:\n$mensaje";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        header("Location: index.html");
        exit();
    } else {
        echo "Error al enviar el mensaje. Inténtalo más tarde.";
    }
} else {
    echo "Método no permitido.";
}
?>
