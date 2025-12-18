// Función para abrir modal de galería
function openModal(img) {
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImage');
  const caption = document.getElementById('caption');
  
  modal.style.display = 'block';
  modalImg.src = img.src;
  caption.innerHTML = img.alt;
}

// Función para cerrar modal
function closeModal() {
  document.getElementById('imageModal').style.display = 'none';
}

// Función scroll to top
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

// Event listeners cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
  
  // Cerrar modal con tecla Escape
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeModal();
    }
  });

  // Mostrar/ocultar botón de scroll según posición
  window.addEventListener('scroll', function() {
    const scrollButton = document.getElementById('scrollToTop');
    if (scrollButton) {
      if (window.pageYOffset > 300) {
        scrollButton.style.display = 'block';
      } else {
        scrollButton.style.display = 'none';
      }
    }
  });

  // Verificar si hay mensaje de confirmación en la URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('mensaje') === 'enviado') {
    mostrarMensajeExito();
  }

});

// Función para mostrar mensaje de éxito
function mostrarMensajeExito() {
  const mensaje = document.createElement('div');
  mensaje.innerHTML = `
    <div style="
      position: fixed;
      top: 20px;
      right: 20px;
      background: #4CAF50;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      z-index: 10000;
      font-weight: bold;
    ">
      ✅ ¡Mensaje enviado correctamente! Te contactaremos pronto.
    </div>
  `;
  
  document.body.appendChild(mensaje);
  
  // Remover mensaje después de 5 segundos
  setTimeout(() => {
    mensaje.remove();
    // Limpiar URL
    window.history.replaceState({}, document.title, window.location.pathname);
  }, 5000);
}

// Inicializar el mapa de Leaflet
function initializeLeafletMap() {
  // Esperar un poco para que el DOM esté completamente cargado
  setTimeout(function() {
    // Coordenadas de AIDCO
    var lat = 32.52605927889032;
    var lng = -116.98369938225481;
    
    // Crear el mapa
    var map = L.map('mapa-leaflet').setView([lat, lng], 15);
    
    // Agregar capa de tiles (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Agregar marcador
    var marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup('<b>AIDCO</b><br>Avenida Ejército Nacional 3<br>Tomas Aquino, 22414 Tijuana, B.C.').openPopup();
    
    // Forzar que el mapa se redibuje correctamente
    setTimeout(function() {
      map.invalidateSize();
    }, 100);
  }, 100);
}

// Agregar la inicialización del mapa al evento DOMContentLoaded existente
document.addEventListener('DOMContentLoaded', function() {
  initializeLeafletMap();
});