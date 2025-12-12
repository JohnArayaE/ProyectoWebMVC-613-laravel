// Sort change
document.getElementById('ordenSelect')?.addEventListener('change', function () {
    const url = new URL(window.location);
    url.searchParams.set('orden', this.value);
    window.location.href = url.toString();
});

let rideActual = null;

// =====================
// OBTENER INFO DEL RIDE
// =====================
function reservarRide(rideId) {

    fetch(`/ride/info/${rideId}`)
        .then(async response => {
            if (!response.ok) throw new Error("Error fetching ride info.");
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

// ==========================
// MOSTRAR MODAL DE RESERVA
// ==========================
function mostrarModalReserva() {
    const modal = document.getElementById('reservaModal');
    const rideInfo = document.getElementById('modalRideInfo');

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

// =======================
// CALCULAR PRECIO TOTAL
// =======================
function actualizarPrecioTotal() {
    if (!rideActual) return;

    const cantidad = parseInt(document.getElementById('cantidadEspacios').value) || 1;
    const total = cantidad * rideActual.costo;

    document.getElementById('costoTotal').textContent = `₡${total.toFixed(2)}`;
}

// =================
// NOTIFICACIONES
// =================
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

// ========================
// CONFIG DEL MODAL + ENVÍO
// ========================
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('reservaModal');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelarReserva');
    const confirmBtn = document.getElementById('confirmarReserva');
    const cantidadInput = document.getElementById('cantidadEspacios');

    closeBtn.onclick = cancelBtn.onclick = function () {
        modal.close();
        rideActual = null;
    };

    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.close();
            rideActual = null;
        }
    });

    cantidadInput.addEventListener('input', actualizarPrecioTotal);

    cantidadInput.addEventListener('change', function () {
        let val = parseInt(this.value) || 1;
        const max = parseInt(this.max);
        if (val < 1) val = 1;
        if (val > max) val = max;
        this.value = val;
        actualizarPrecioTotal();
    });

    // ===========================
    // CONFIRMAR RESERVA
    // ===========================
    confirmBtn.onclick = function () {

        const cantidad = parseInt(cantidadInput.value) || 1; // <-- LO QUE FALTABA!!

        fetch('/ride/reserve', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                ride_id: rideActual.id,
                cantidad_espacios: cantidad
            })
        })
            .then(async res => {
                const text = await res.text();

                try {
                    const data = JSON.parse(text);

                    if (data.success) {
                        showNotification(data.message, 'success');
                        modal.close();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        console.error("SERVER ERROR:", data);
                        showNotification(data.message + " | " + (data.error ?? ""), 'error');
                    }

                } catch (e) {
                    console.error("RAW RESPONSE:", text);
                    showNotification("Respuesta no válida del servidor.", 'error');
                }
            })
            .catch(err => {
                showNotification('Error making reservation: ' + err.message, 'error');
            });
    };
});