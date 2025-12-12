document.addEventListener("DOMContentLoaded", () => {

    const vehicleSelect = document.getElementById('id_vehiculo');
    const seatsInput = document.getElementById('espacios_totales');
    const rideForm = document.getElementById('rideForm');
    const dayInputs = document.querySelectorAll('.day-input');
    const clientMessage = document.getElementById('clientMessage');
    const submitBtn = document.getElementById('submitBtn');


    // === VEHICLE CAPACITY ===
    if (vehicleSelect && seatsInput) {
        vehicleSelect.addEventListener('change', () => {
            const opt = vehicleSelect.options[vehicleSelect.selectedIndex];

            if (!opt.value) return;

            const capacity = parseInt(opt.getAttribute('data-capacity'));

            seatsInput.max = capacity - 1;
            seatsInput.placeholder = `Max ${capacity - 1}`;

            if (Number(seatsInput.value) > capacity - 1) {
                seatsInput.value = capacity - 1;
            }
        });
    }


    // === FORM VALIDATION ===
    rideForm.addEventListener("submit", e => {
        e.preventDefault();

        if (!validateDay()) return;

        submitBtn.disabled = true;
        submitBtn.textContent = "Updating...";

        rideForm.submit();
    });


    function validateDay() {
        const selected = Array.from(dayInputs).some(r => r.checked);

        if (!selected) {
            showMessage("Please select a day of the week", "error");
            return false;
        }

        return true;
    }


    function showMessage(text, type) {
        clientMessage.innerHTML = `
            <div class="alert ${type}">
                ${text}
            </div>
        `;
    }

});
