// public/js/campagne.js
function initMapPreview(inputId) {
    const fileInput = document.getElementById(inputId);
    const previewImg = document.getElementById('mapPreview');
    const placeholder = document.getElementById('mapPlaceholder');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}