<?php
// Configuración de email
$email_destino = "ar@aidco.com.mx"; // Cambia por tu email
$asunto_base = "Nuevo mensaje desde AIDCO.com.mx";

// Verificar que el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener y limpiar datos del formulario
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $asunto = isset($_POST['asunto']) ? trim($_POST['asunto']) : '';
    $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
    
    // Validar campos requeridos
    $errores = array();
    
    if (empty($nombre)) {
        $errores[] = "El nombre es requerido";
    }
    
    if (empty($email)) {
        $errores[] = "El email es requerido";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido";
    }
    
    if (empty($asunto)) {
        $errores[] = "El asunto es requerido";
    }
    
    if (empty($mensaje)) {
        $errores[] = "El mensaje es requerido";
    }
    
    // Si no hay errores, enviar email
    if (empty($errores)) {
        
        // Preparar el contenido del email
        $contenido_email = "
        ===== NUEVO MENSAJE DESDE AIDCO.COM.MX =====
        
        Nombre: $nombre
        Email: $email
        Asunto: $asunto
        
        Mensaje:
        $mensaje
        
        ==========================================
        Enviado desde: " . $_SERVER['HTTP_HOST'] . "
        IP del remitente: " . $_SERVER['REMOTE_ADDR'] . "
        Fecha: " . date('Y-m-d H:i:s') . "
        ";
        
        // Configurar headers del email
        $headers = array();
        $headers[] = "From: noreply@" . $_SERVER['HTTP_HOST'];
        $headers[] = "Reply-To: $email";
        $headers[] = "X-Mailer: PHP/" . phpversion();
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
        
        $headers_string = implode("\r\n", $headers);
        
        // Enviar email
        $email_enviado = mail($email_destino, "$asunto_base - $asunto", $contenido_email, $headers_string);
        
        if ($email_enviado) {
            // Redirigir con mensaje de éxito
            header("Location: index.html?mensaje=enviado");
            exit();
        } else {
            $error_mensaje = "Error al enviar el mensaje. Por favor intenta de nuevo.";
        }
        
    } else {
        $error_mensaje = "Por favor corrige los siguientes errores: " . implode(", ", $errores);
    }
    
} else {
    // Si no es POST, redirigir al formulario
    header("Location: index.html");
    exit();
}

// Si llegamos aquí, hubo un error
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - AIDCO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .error-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-message {
            color: #d32f2f;
            margin-bottom: 20px;
        }
        .back-button {
            background: #1a365d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .back-button:hover {
            background: #2d5a87;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h2>Error al enviar mensaje</h2>
        <p class="error-message"><?php echo isset($error_mensaje) ? htmlspecialchars($error_mensaje) : 'Error desconocido'; ?></p>
        <a href="index.html" class="back-button">Volver al formulario</a>
    </div>
</body>
</html>