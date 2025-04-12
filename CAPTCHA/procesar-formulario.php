<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Datos del formulario
    $nombre = htmlspecialchars($_POST["Nombre"]);
    $email = htmlspecialchars($_POST["Email"]);
    $tipo = htmlspecialchars($_POST["Tipo-Solicitud"]);
    $mensaje = htmlspecialchars($_POST["Mensaje"]);

    // Validación de CAPTCHA
    $recaptchaSecret = '6Lc2ShUrAAAAAHj84wOLYXbBtKXHElJ6N0quH1LP';
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verificacion = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}"
    );
    $captcha = json_decode($verificacion);

    if ($captcha->success) {
        // Envío del correo
        $to = "contacto@deveesites.com";
        $subject = "Nuevo mensaje de $nombre";
        $body = "Nombre: $nombre\nCorreo: $email\nTipo: $tipo\n\nMensaje:\n$mensaje";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            header("Location: gracias.html");
            exit();
        } else {
            echo "Error al enviar el mensaje. Inténtalo más tarde.";
        }
    } else {
        echo "Por favor confirma que no eres un robot.";
    }
} else {
    echo "Método no permitido.";
}
?>
