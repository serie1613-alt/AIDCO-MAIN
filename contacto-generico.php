<?php
/**
 * SCRIPT PHP GENÉRICO PARA FORMULARIOS DE CONTACTO
 * 
 * Instrucciones de uso:
 * 1. Cambia la configuración en la sección "CONFIGURACIÓN"
 * 2. Sube este archivo a tu servidor con hosting PHP
 * 3. En tu formulario HTML usa: action="contacto-generico.php"
 * 4. Los campos del formulario deben tener estos names:
 *    - name="nombre"
 *    - name="email" 
 *    - name="asunto"
 *    - name="mensaje"
 *    - name="telefono" (opcional)
 */

// ==================== CONFIGURACIÓN ====================
// CAMBIA ESTOS VALORES POR LOS TUYOS:

$config = array(
    // Email donde quieres recibir los mensajes
    'email_destino' => 'tu-email@tudominio.com',
    
    // Asunto base para los emails
    'asunto_base' => 'Nuevo mensaje desde tu sitio web',
    
    // Página a la que redirigir después del envío exitoso
    'pagina_exito' => 'index.html?mensaje=enviado',
    
    // Página a la que redirigir si hay error
    'pagina_error' => 'index.html?mensaje=error',
    
    // Nombre de tu sitio web
    'nombre_sitio' => 'Tu Sitio Web'
);

// ==================== NO MODIFICAR DEBAJO DE ESTA LÍNEA ====================

// Verificar que el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener y limpiar datos del formulario
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $asunto = isset($_POST['asunto']) ? trim($_POST['asunto']) : '';
    $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    
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
    
    // Validación anti-spam básica
    if (strlen($mensaje) < 10) {
        $errores[] = "El mensaje es muy corto";
    }
    
    if (strlen($mensaje) > 5000) {
        $errores[] = "El mensaje es muy largo";
    }
    
    // Si no hay errores, enviar email
    if (empty($errores)) {
        
        // Preparar el contenido del email
        $contenido_email = "
===== NUEVO MENSAJE DESDE {$config['nombre_sitio']} =====

Nombre: $nombre
Email: $email" . (!empty($telefono) ? "\nTeléfono: $telefono" : "") . "
Asunto: $asunto

Mensaje:
$mensaje

==========================================
Información técnica:
Sitio web: " . $_SERVER['HTTP_HOST'] . "
IP del remitente: " . $_SERVER['REMOTE_ADDR'] . "
User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "
Fecha y hora: " . date('Y-m-d H:i:s') . "
        ";
        
        // Configurar headers del email
        $headers = array();
        $headers[] = "From: noreply@" . $_SERVER['HTTP_HOST'];
        $headers[] = "Reply-To: $email";
        $headers[] = "X-Mailer: PHP/" . phpversion();
        $headers[] = "Content-Type: text/plain; charset=UTF-8";
        $headers[] = "X-Priority: 3";
        
        $headers_string = implode("\r\n", $headers);
        
        // Enviar email
        $email_enviado = mail(
            $config['email_destino'], 
            $config['asunto_base'] . " - " . $asunto, 
            $contenido_email, 
            $headers_string
        );
        
        if ($email_enviado) {
            // Email enviado exitosamente
            header("Location: " . $config['pagina_exito']);
            exit();
        } else {
            // Error al enviar email
            $error_mensaje = "Error del servidor al enviar el mensaje. Por favor intenta de nuevo más tarde.";
        }
        
    } else {
        // Hay errores de validación
        $error_mensaje = "Por favor corrige los siguientes errores: " . implode(", ", $errores);
    }
    
} else {
    // Si no es POST, redirigir al formulario
    header("Location: " . $config['pagina_error']);
    exit();
}

// Si llegamos aquí, hubo un error - mostrar página de error
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - <?php echo htmlspecialchars($config['nombre_sitio']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .error-icon {
            font-size: 48px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .error-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .error-message {
            color: #e74c3c;
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 16px;
        }
        
        .back-button {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            font-weight: 500;
            transition: transform 0.3s ease;
        }
        
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 480px) {
            .error-container {
                padding: 30px 20px;
            }
            
            .error-title {
                font-size: 20px;
            }
            
            .error-message {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h2 class="error-title">Error al enviar mensaje</h2>
        <p class="error-message">
            <?php echo isset($error_mensaje) ? htmlspecialchars($error_mensaje) : 'Ha ocurrido un error inesperado. Por favor intenta de nuevo.'; ?>
        </p>
        <a href="<?php echo htmlspecialchars($config['pagina_error']); ?>" class="back-button">
            ← Volver al formulario
        </a>
    </div>
</body>
</html>