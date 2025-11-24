// Handle foto upload with drag & drop
window.handleFotoUpload = function (input) {
    const file = input.files[0];
    if (file) {
        // Validate file type
        const validTypes = ["image/jpeg", "image/jpg", "image/png"];
        if (!validTypes.includes(file.type)) {
            alert("Format file harus JPG, JPEG, atau PNG");
            input.value = "";
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert("Ukuran file maksimal 2MB");
            input.value = "";
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("previewImageFoto").src = e.target.result;
            document.getElementById("fileNameFoto").textContent = file.name;
            document.getElementById("fileSizeFoto").textContent =
                formatFileSize(file.size);
            document.getElementById("uploadPromptFoto").classList.add("hidden");
            document
                .getElementById("previewContainerFoto")
                .classList.remove("hidden");
        };
        reader.readAsDataURL(file);
    }
};

// Remove foto
window.removeFoto = function () {
    document.getElementById("foto").value = "";
    document.getElementById("uploadPromptFoto").classList.remove("hidden");
    document.getElementById("previewContainerFoto").classList.add("hidden");
};

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
}

// Drag and drop for foto
document.addEventListener("DOMContentLoaded", function () {
    const dropZone = document.getElementById("dropZoneFoto");
    const fileInput = document.getElementById("foto");

    if (dropZone && fileInput) {
        // Prevent default drag behaviors
        ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when dragging over
        ["dragenter", "dragover"].forEach((eventName) => {
            dropZone.addEventListener(
                eventName,
                () => {
                    dropZone.classList.add(
                        "border-orange-500",
                        "bg-orange-100"
                    );
                },
                false
            );
        });

        ["dragleave", "drop"].forEach((eventName) => {
            dropZone.addEventListener(
                eventName,
                () => {
                    dropZone.classList.remove(
                        "border-orange-500",
                        "bg-orange-100"
                    );
                },
                false
            );
        });

        // Handle dropped files
        dropZone.addEventListener(
            "drop",
            function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    fileInput.files = files;
                    handleFotoUpload(fileInput);
                }
            },
            false
        );
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
});

// Toggle password visibility
window.togglePasswordVisibility = function (fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.type = field.type === "password" ? "text" : "password";
    }
};

// NIK validation - only numbers
const nikInput = document.getElementById("nik");
if (nikInput) {
    nikInput.addEventListener("input", function (e) {
        this.value = this.value.replace(/[^0-9]/g, "");
    });
}

// Phone validation - only numbers
const phoneInput = document.getElementById("no_telepon");
if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
        this.value = this.value.replace(/[^0-9]/g, "");
    });
}
