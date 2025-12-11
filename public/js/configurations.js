document.addEventListener('DOMContentLoaded', function() {
    // MENU DESPLEGABLE
    const avatarBtn = document.getElementById('avatarBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (avatarBtn && profileDropdown) {
        avatarBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', () => {
            profileDropdown.classList.remove('show');
        });
    }

    // PREVIEW DE IMAGEN
    const photoInput = document.getElementById("foto");
    const previewImg = document.getElementById("photoPreview");

    if (photoInput && previewImg) {
        photoInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        });
    }

});
