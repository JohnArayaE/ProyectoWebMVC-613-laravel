document.addEventListener('DOMContentLoaded', function () {

    // ELEMENTOS
    const form = document.getElementById('vehicleForm');
    const submitBtn = document.getElementById('submitBtn');

    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('foto');
    const previewImage = document.getElementById('previewImage');
    const previewContainer = document.getElementById('previewContainer');
    const removePhotoBtn = document.getElementById('removePhoto');

    // Inputs requeridos (coinciden con la BD)
    const requiredFields = [
        'placa',
        'color',
        'marca',
        'modelo',
        'anio',
        'capacidad'
    ];

    // ==============================
    //     INICIALIZACIÓN
    // ==============================
    function initialize() {
        previewContainer.classList.remove("show");
        uploadArea.classList.remove("hidden");

        setupPhotoUpload();
        setupFormValidation();

        console.log("CreateVehicle.js initialized ✔");
    }


    // ==============================
    //     MANEJO DE FOTO
    // ==============================
    function setupPhotoUpload() {

        // Click → abrir selector
        uploadArea.addEventListener('click', () => fileInput.click());

        // Selección de archivo
        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) handleFile(file);
        });

        // Drag Over
        uploadArea.addEventListener('dragover', e => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        // Drop
        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            const file = e.dataTransfer.files[0];

            if (file) {
                // Guardamos el archivo dentro del input real
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                handleFile(file);
            }
        });

        // Botón eliminar foto
        removePhotoBtn.addEventListener('click', resetPhoto);
    }

    function handleFile(file) {

        // Tipo incorrecto
        if (!file.type.startsWith("image/")) {
            showMessage("Please select a valid image file.", "error");
            return;
        }

        // Tamaño muy grande
        if (file.size > 5 * 1024 * 1024) {
            showMessage("Image must be less than 5MB.", "error");
            return;
        }

        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            showPreview();
        };
        reader.readAsDataURL(file);
    }

    function showPreview() {
        uploadArea.style.display = 'none';
        previewContainer.classList.add("show");
    }

    function resetPhoto() {
        fileInput.value = "";
        previewImage.src = "";

        previewContainer.classList.remove("show");
        uploadArea.style.display = 'flex';
    }


    // ==============================
    //     VALIDACIÓN DEL FORMULARIO
    // ==============================
    function setupFormValidation() {

        form.addEventListener('submit', function (e) {

            if (!validateForm()) {
                e.preventDefault();
                showMessage("Please fix the errors before submitting.", "error");
                return;
            }

            submitBtn.innerHTML = "Creating Vehicle...";
            submitBtn.disabled = true;
        });
    }

    function validateForm() {
        let valid = true;

        requiredFields.forEach(fieldId => {
            const input = document.getElementById(fieldId);

            if (!input || !input.value.trim()) {
                valid = false;
                input.style.borderColor = "#ff4d4d";

                input.addEventListener('input', () => {
                    input.style.borderColor = "";
                }, { once: true });
            }
        });

        // Validación del año
        const year = document.getElementById('anio');
        const currentYear = new Date().getFullYear();

        if (year.value < 1980 || year.value > currentYear + 1) {
            valid = false;
            year.style.borderColor = "#ff4d4d";
        }

        // Validación capacidad
        const seats = document.getElementById('capacidad');

        if (seats.value < 1 || seats.value > 9) {
            valid = false;
            seats.style.borderColor = "#ff4d4d";
        }

        return valid;
    }


    // ==============================
    //     MENSAJES VISIBLES
    // ==============================
    function showMessage(message, type) {

        let box = document.createElement('div');
        box.className = `alert ${type}`;
        box.textContent = message;

        box.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'error' ? '#e74c3c' : '#2ecc71'};
            color: white;
            padding: 14px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 2000;
            font-weight: bold;
        `;

        document.body.appendChild(box);

        setTimeout(() => box.remove(), 3200);
    }


    // Ejecutar
    initialize();
});
