document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('vehicleForm');
    const submitBtn = document.getElementById('submitBtn');

    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('foto');
    const previewImage = document.getElementById('previewImage');
    const previewContainer = document.getElementById('previewContainer');
    const removePhotoBtn = document.getElementById('removePhoto');

    function initialize() {
        detectExistingPhoto();
        setupPhotoUpload();
        setupValidation();
        console.log("EditVehicle.js initialized ✔");
    }

    // Detecta si ya hay foto guardada
    function detectExistingPhoto() {
        if (previewImage && previewImage.getAttribute("src") !== "") {

            previewContainer.classList.add("show");
            previewContainer.style.display = "flex";
            uploadArea.style.display = "none";

        } else {

            previewContainer.classList.remove("show");
            previewContainer.style.display = "none";
            uploadArea.style.display = "flex";

        }
    }

    function setupPhotoUpload() {

        uploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (file) handleFile(file);
        });

        uploadArea.addEventListener('dragover', e => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', e => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            const file = e.dataTransfer.files[0];
            if (file) handleFile(file);
        });

        removePhotoBtn.addEventListener('click', resetPreview);
    }

    function handleFile(file) {

        if (!file.type.startsWith("image/")) {
            alert("Invalid image.");
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert("Image must be < 5MB.");
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            previewImage.src = e.target.result;
            showPreview();
        };

        reader.readAsDataURL(file);
    }

    function showPreview() {
        uploadArea.style.display = "none";
        previewContainer.classList.add("show");
        previewContainer.style.display = "flex";
    }

    function resetPreview() {

        previewImage.src = "";
        previewContainer.classList.remove("show");
        previewContainer.style.display = "none";

        uploadArea.style.display = "flex";
        fileInput.value = "";
    }

    function setupValidation() {
        form.addEventListener('submit', e => {
            submitBtn.innerHTML = "Updating...";
            submitBtn.disabled = true;
        });
    }

    initialize();
});
