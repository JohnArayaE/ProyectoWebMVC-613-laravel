document.addEventListener('DOMContentLoaded', function () {
    const modal = document.createElement('dialog');
    modal.id = "confirmModal";
    modal.innerHTML = `
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <p id="modalMessage"></p>
            <div class="modal-actions">
                <button class="btn outline" id="cancelAction">Cancel</button>
                <button class="btn neon" id="confirmAction">Confirm</button>
            </div>
        </div>`;
    document.body.appendChild(modal);

    let currentBooking = null;
    let currentAction = null;

    // Detectar botones dentro de cada tarjeta
    document.querySelectorAll('.booking-card').forEach(card => {
        card.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', () => {

                currentBooking = card.dataset.id;
                currentAction = btn.dataset.action;

                modal.querySelector('#modalTitle').textContent =
                    currentAction.charAt(0).toUpperCase() + currentAction.slice(1) + " Booking";

                modal.querySelector('#modalMessage').textContent =
                    "Are you sure you want to " + currentAction + " this booking?";

                modal.showModal();
            });
        });
    });

    // Cerrar modal
    document.getElementById('cancelAction').onclick = () => modal.close();

    // CONFIRMAR ACCIÓN
    document.getElementById('confirmAction').onclick = () => {

        fetch("/bookings/update", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                booking_id: currentBooking,
                action: currentAction
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                modal.close();
                location.reload();
            } else {
                alert("Error: " + res.error);
                modal.close();
            }
        })
        .catch(err => {
            alert("Request failed");
            modal.close();
        });
    };


    // SUBMENU
    const avatarBtn = document.getElementById('avatarBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (avatarBtn && profileDropdown) {
        avatarBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function () {
            profileDropdown.classList.remove('show');
        });
    }
});