# 📧 Script PHP para Formularios de Contacto

## 🚀 Instrucciones de Uso

### 1. **Configuración del Script**
Abre el archivo `contacto-generico.php` y modifica la sección de configuración:

```php
$config = array(
    // Cambia por tu email real
    'email_destino' => 'tu-email@tudominio.com',
    
    // Personaliza el asunto
    'asunto_base' => 'Nuevo mensaje desde tu sitio web',
    
    // Páginas de redirección
    'pagina_exito' => 'index.html?mensaje=enviado',
    'pagina_error' => 'index.html?mensaje=error',
    
    // Nombre de tu sitio
    'nombre_sitio' => 'Tu Sitio Web'
);
```

### 2. **HTML del Formulario**
Tu formulario HTML debe tener esta estructura:

```html
<form action="contacto-generico.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="telefono" placeholder="Teléfono (opcional)">
    <input type="text" name="asunto" placeholder="Asunto" required>
    <textarea name="mensaje" placeholder="Mensaje" required></textarea>
    <button type="submit">Enviar mensaje</button>
</form>
```

### 3. **Subir Archivos al Servidor**
1. Sube `contacto-generico.php` a tu hosting de GoDaddy
2. Asegúrate que esté en la misma carpeta que tu `index.html`
3. Verifica que tu hosting tenga PHP habilitado

### 4. **Configurar Mensajes de Éxito/Error**
Puedes mostrar mensajes usando JavaScript:

```javascript
// Agregar al final de tu script.js
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    
    if (mensaje === 'enviado') {
        alert('¡Mensaje enviado correctamente! Te contactaremos pronto.');
        // O mostrar un modal de éxito
    } else if (mensaje === 'error') {
        alert('Hubo un error al enviar el mensaje. Por favor intenta de nuevo.');
    }
});
```

## ✅ Características del Script

### **Validaciones Incluidas:**
- ✅ Campos requeridos (nombre, email, asunto, mensaje)
- ✅ Validación de email válido
- ✅ Longitud mínima y máxima del mensaje
- ✅ Protección básica anti-spam

### **Información que Recibe el Email:**
- 📝 Nombre del remitente
- 📧 Email de contacto
- 📞 Teléfono (si se proporciona)
- 📋 Asunto del mensaje
- 💬 Mensaje completo
- 🌐 Información técnica (IP, navegador, fecha)

### **Seguridad:**
- 🔒 Sanitización de datos de entrada
- 🛡️ Protección contra inyección de headers
- 🚫 Validación anti-spam básica
- 📊 Logging de información técnica

## 🔧 Personalización Avanzada

### **Cambiar el Diseño de la Página de Error:**
Modifica la sección CSS en `contacto-generico.php` línea 120+

### **Agregar Más Campos:**
1. Agrega el campo en tu HTML: `<input name="empresa">`
2. En el PHP, agrega: `$empresa = isset($_POST['empresa']) ? trim($_POST['empresa']) : '';`
3. Incluye en el email: `"Empresa: $empresa\n"`

### **Enviar Copia al Remitente:**
Agrega después del `mail()` principal:
```php
// Enviar copia al remitente
$mensaje_copia = "Gracias por contactarnos. Hemos recibido tu mensaje: \n\n" . $mensaje;
mail($email, "Copia de tu mensaje - " . $config['nombre_sitio'], $mensaje_copia, $headers_string);
```

## 🚨 Solución de Problemas

### **El formulario no envía emails:**
1. Verifica que tu hosting tenga PHP habilitado
2. Revisa que el email de destino sea correcto
3. Algunos hostings requieren que el "From" sea del mismo dominio

### **Los emails van a spam:**
1. Configura SPF y DKIM en tu dominio
2. Usa un email "From" del mismo dominio
3. Evita palabras spam en el asunto

### **Error 500:**
1. Revisa los logs de error de tu servidor
2. Verifica la sintaxis del PHP
3. Asegúrate que los permisos del archivo sean correctos (644)

## 📞 Soporte

Si necesitas ayuda con la implementación:
- Revisa los logs de error de tu hosting
- Contacta al soporte técnico de GoDaddy
- Verifica que la función `mail()` esté habilitada

---

**💡 Tip:** Siempre prueba el formulario después de subirlo al servidor para asegurarte que funciona correctamente.