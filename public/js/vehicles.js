// ===============================
//     vehicles.js (Laravel)
// ===============================

// --- Submenú del perfil ---
function initProfileMenu() {
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

        profileDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }
}


// --- Botones de EDITAR ---
function initEditButtons() {
    const editButtons = document.querySelectorAll('[data-edit]');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {

            const id = this.getAttribute('data-edit');

            // Redirección hacia la ruta edit correcta de Laravel
            window.location.href = `/driver/vehicles/${id}/edit`;
        });
    });
}


// --- Botones de ELIMINAR ---
function initDeleteButtons() {
    const deleteButtons = document.querySelectorAll('[data-delete]');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {

            const id = this.getAttribute('data-delete');
            const plate = this.closest('.veh-card').querySelector('.veh-plate').textContent;

            if (!confirm(`Are you sure you want to delete the vehicle ${plate}?`)) return;

            fetch(`/driver/vehicles/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    showMessage("Vehicle deleted successfully!", "success");
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showMessage("Error deleting vehicle.", "error");
                }

            })
            .catch(() => showMessage("Server error.", "error"));
        });
    });
}


// --- Mostrar mensajes temporales ---
function showMessage(text, type) {

    const div = document.createElement('div');
    div.className = `alert alert-${type}`;
    div.textContent = text;

    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: 600;
        color: white;
        z-index: 2000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        transition: opacity .3s;
    `;

    div.style.background = type === "success" ? "#4CAF50" : "#f44336";

    document.body.appendChild(div);

    setTimeout(() => {
        div.style.opacity = "0";
        setTimeout(() => div.remove(), 300);
    }, 3000);
}


// --- Inicialización general ---
document.addEventListener("DOMContentLoaded", function () {
    initProfileMenu();
    initEditButtons();
    initDeleteButtons();
});
