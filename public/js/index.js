// Sort change
document.getElementById('ordenSelect').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('orden', this.value);
    window.location.href = url.toString();
});

let rideActual = null;

// Book ride function
function reservarRide(rideId) {
    console.log('Booking ride ID:', rideId);

    fetch('/ride/info/' + rideId)
        .then(response => {
            if (!response.ok) throw new Error('Server error: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                rideActual = data.ride;
                mostrarModalReserva();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error loading ride information: ' + error.message, 'error');
        });
}

// Show reservation modal
function mostrarModalReserva() {
    const modal = document.getElementById('reservaModal');
    const rideInfo = document.getElementById('modalRideInfo');

    if (!rideActual) {
        showNotification('Error: Could not load ride information', 'error');
        return;
    }

    const maxEspacios = Math.min(rideActual.espacios_disponibles, rideActual.capacidad - 1);

    rideInfo.innerHTML = `
        <div class="ride-info-modal">
            <h4>${rideActual.nombre_ride || 'Ride'}</h4>
            <p><strong>Route:</strong> ${rideActual.lugar_salida} → ${rideActual.lugar_llegada}</p>
            <p><strong>Day:</strong> ${rideActual.dia_semana}</p>
            <p><strong>Time:</strong> ${rideActual.hora.substring(0,5)}</p>
            <p><strong>Vehicle:</strong> ${rideActual.marca} ${rideActual.modelo}</p>
            <p><strong>Available spaces:</strong> ${rideActual.espacios_disponibles}</p>
        </div>
    `;

    const cantidadInput = document.getElementById('cantidadEspacios');
    cantidadInput.value = 1;
    cantidadInput.max = maxEspacios;

    document.getElementById('maxEspaciosInfo').textContent = `Maximum: ${maxEspacios} spaces`;
    document.getElementById('costoPorEspacio').textContent = `₡${parseFloat(rideActual.costo).toFixed(2)}`;

    actualizarPrecioTotal();
    modal.showModal();
}

// Update total price
function actualizarPrecioTotal() {
    if (!rideActual) return;

    const cantidad = parseInt(document.getElementById('cantidadEspacios').value) || 1;
    const total = cantidad * rideActual.costo;

    document.getElementById('costoTotal').textContent = `₡${total.toFixed(2)}`;
}

// Notification system
function showNotification(message, type) {
    const div = document.createElement('div');
    div.className = `notification ${type}`;
    div.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(div);

    setTimeout(() => div.remove(), 4000);
}

// Modal configuration
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reservaModal');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelarReserva');
    const confirmBtn = document.getElementById('confirmarReserva');
    const cantidadInput = document.getElementById('cantidadEspacios');

    closeBtn.onclick = cancelBtn.onclick = function() {
        modal.close();
        rideActual = null;
    };

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.close();
            rideActual = null;
        }
    });

    cantidadInput.addEventListener('input', actualizarPrecioTotal);

    cantidadInput.addEventListener('change', function() {
        let val = parseInt(this.value) || 1;
        const max = parseInt(this.max);

        if (val < 1) val = 1;
        if (val > max) val = max;

        this.value = val;
        actualizarPrecioTotal();
    });

    // Confirm booking
    confirmBtn.onclick = function() {
        const cantidad = parseInt(cantidadInput.value) || 1;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        fetch('/ride/reserve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                ride_id: rideActual.id,
                cantidad_espacios: cantidad
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                modal.close();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(err => {
            showNotification('Error making reservation: ' + err.message, 'error');
        })
        .finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = 'Confirm Booking';
        });
    };
});
