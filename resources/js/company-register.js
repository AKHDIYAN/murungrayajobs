// Company Registration Form - Interactive Features
// File: resources/js/company-register.js

// Wait for DOM to be ready
document.addEventListener("DOMContentLoaded", function () {
    initializeDragDrop();
    initializeFormFields();
    initializeFormValidation();
});

// ===================================
// DRAG & DROP FILE UPLOAD
// ===================================
function initializeDragDrop() {
    const dropZone = document.getElementById("dropZone");
    const logoInput = document.getElementById("logo");

    if (!dropZone || !logoInput) return;

    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    ["dragenter", "dragover"].forEach((eventName) => {
        dropZone.addEventListener(
            eventName,
            () => {
                dropZone.classList.add("drag-over");
            },
            false
        );
    });

    ["dragleave", "drop"].forEach((eventName) => {
        dropZone.addEventListener(
            eventName,
            () => {
                dropZone.classList.remove("drag-over");
            },
            false
        );
    });

    dropZone.addEventListener(
        "drop",
        function (e) {
            const files = e.dataTransfer.files;
            if (files.length) {
                logoInput.files = files;
                previewLogo(logoInput);
            }
        },
        false
    );
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// ===================================
// LOGO PREVIEW & VALIDATION
// ===================================
window.previewLogo = function (input) {
    const preview = document.getElementById("logoPreview");
    const previewImg = document.getElementById("logoPreviewImg");
    const uploadPrompt = document.getElementById("uploadPrompt");

    if (input.files && input.files[0]) {
        // Validasi ukuran file (max 2MB)
        const fileSize = input.files[0].size / 1024 / 1024; // MB
        if (fileSize > 2) {
            showNotification("❌ Ukuran file maksimal 2MB!", "error");
            input.value = "";
            preview.classList.add("hidden");
            uploadPrompt.classList.remove("hidden");
            return;
        }

        // Validasi tipe file
        const fileType = input.files[0].type;
        if (!["image/jpeg", "image/jpg", "image/png"].includes(fileType)) {
            showNotification(
                "❌ Format file harus JPG, JPEG, atau PNG!",
                "error"
            );
            input.value = "";
            preview.classList.add("hidden");
            uploadPrompt.classList.remove("hidden");
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            uploadPrompt.classList.add("hidden");
            preview.classList.remove("hidden");
            showNotification("✅ Logo berhasil dipilih!", "success");
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add("hidden");
        uploadPrompt.classList.remove("hidden");
    }
};

window.removeLogo = function () {
    const logoInput = document.getElementById("logo");
    const preview = document.getElementById("logoPreview");
    const uploadPrompt = document.getElementById("uploadPrompt");

    logoInput.value = "";
    preview.classList.add("hidden");
    uploadPrompt.classList.remove("hidden");
    showNotification("🗑️ Logo dihapus", "info");
};

// ===================================
// PASSWORD STRENGTH CHECKER
// ===================================
window.checkPasswordStrength = function (password) {
    const strengthDiv = document.getElementById("password-strength");
    const strengthText = document.getElementById("strength-text");
    const bars = [
        document.getElementById("strength-bar-1"),
        document.getElementById("strength-bar-2"),
        document.getElementById("strength-bar-3"),
        document.getElementById("strength-bar-4"),
    ];

    if (password.length === 0) {
        strengthDiv.classList.add("hidden");
        return;
    }

    strengthDiv.classList.remove("hidden");

    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    // Reset bars
    bars.forEach((bar) => {
        bar.style.backgroundColor = "#e5e7eb";
    });

    const colors = ["#ef4444", "#f97316", "#eab308", "#22c55e"];
    const texts = ["Lemah", "Sedang", "Baik", "Kuat"];

    for (let i = 0; i < strength; i++) {
        bars[i].style.backgroundColor = colors[strength - 1];
    }

    strengthText.textContent = texts[strength - 1] || "";
    strengthText.style.color = colors[strength - 1] || "";
};

// ===================================
// PASSWORD MATCH CHECKER
// ===================================
window.checkPasswordMatch = function () {
    const password = document.getElementById("password").value;
    const confirmation = document.getElementById("password_confirmation").value;
    const matchText = document.getElementById("password-match");

    if (confirmation.length === 0) {
        matchText.classList.add("hidden");
        return;
    }

    matchText.classList.remove("hidden");

    if (password === confirmation) {
        matchText.textContent = "✅ Password cocok";
        matchText.className =
            "mt-2 text-xs text-green-600 font-medium flex items-center gap-1";
    } else {
        matchText.textContent = "❌ Password tidak cocok";
        matchText.className =
            "mt-2 text-xs text-red-600 font-medium flex items-center gap-1";
    }
};

// ===================================
// TOGGLE PASSWORD VISIBILITY
// ===================================
window.togglePassword = function (fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + "-eye");

    if (field.type === "password") {
        field.type = "text";
        eye.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
        `;
    } else {
        field.type = "password";
        eye.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
};

// ===================================
// CHARACTER COUNTER
// ===================================
window.updateCharCount = function (textarea, maxLength) {
    const counter = document.getElementById(textarea.id + "-count");
    const currentLength = textarea.value.length;

    counter.textContent = `${currentLength} / ${maxLength} karakter`;

    if (currentLength > maxLength) {
        counter.classList.add("text-red-600", "font-bold");
        counter.classList.remove("text-gray-500");
    } else if (currentLength > maxLength * 0.9) {
        counter.classList.add("text-orange-600", "font-semibold");
        counter.classList.remove("text-gray-500", "text-red-600");
    } else {
        counter.classList.add("text-gray-500");
        counter.classList.remove(
            "text-red-600",
            "text-orange-600",
            "font-bold",
            "font-semibold"
        );
    }
};

// ===================================
// NOTIFICATION SYSTEM
// ===================================
function showNotification(message, type = "info") {
    const colors = {
        success: "bg-green-500",
        error: "bg-red-500",
        info: "bg-blue-500",
    };

    const notification = document.createElement("div");
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300 translate-x-0 opacity-100`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = "translateX(400px)";
        notification.style.opacity = "0";
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ===================================
// FORM INITIALIZATION
// ===================================
function initializeFormFields() {
    // Initialize character counter
    const deskripsiField = document.getElementById("deskripsi");
    if (deskripsiField && deskripsiField.value) {
        updateCharCount(deskripsiField, 1000);
    }

    // Auto-save form data to localStorage
    const formFields = [
        "username",
        "email",
        "nama_perusahaan",
        "no_telepon",
        "alamat",
        "deskripsi",
    ];

    formFields.forEach((fieldId) => {
        const field = document.getElementById(fieldId);
        if (field) {
            // Load saved data
            const savedValue = localStorage.getItem("register_" + fieldId);
            if (savedValue && !field.value) {
                field.value = savedValue;
            }

            // Save on input
            field.addEventListener("input", function () {
                localStorage.setItem("register_" + fieldId, this.value);
            });
        }
    });

    // Clear localStorage on successful submission
    window.addEventListener("beforeunload", function (e) {
        const form = document.getElementById("registerForm");
        if (form && form.checkValidity()) {
            formFields.forEach((fieldId) => {
                localStorage.removeItem("register_" + fieldId);
            });
        }
    });
}

// ===================================
// FORM VALIDATION
// ===================================
function initializeFormValidation() {
    const form = document.getElementById("registerForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        const terms = document.getElementById("terms");
        const logo = document.getElementById("logo");
        const submitBtn = document.getElementById("submitBtn");

        if (!terms.checked) {
            e.preventDefault();
            showNotification(
                "⚠️ Anda harus menyetujui syarat dan ketentuan!",
                "error"
            );
            terms.focus();
            terms.classList.add("animate-shake");
            setTimeout(() => terms.classList.remove("animate-shake"), 500);
            return false;
        }

        if (!logo.files || logo.files.length === 0) {
            e.preventDefault();
            showNotification("⚠️ Logo perusahaan wajib diupload!", "error");
            logo.focus();
            const dropZone = document.getElementById("dropZone");
            dropZone.classList.add("animate-shake");
            setTimeout(() => dropZone.classList.remove("animate-shake"), 500);
            return false;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Mendaftar...</span>
        `;

        showNotification("🚀 Sedang memproses pendaftaran...", "info");
    });
}
