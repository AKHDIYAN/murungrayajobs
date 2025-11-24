/**
 * Common UI Utilities
 * Reusable functions for forms, file uploads, and UI interactions
 */

window.uiUtils = {
    /**
     * Toggle password visibility
     * @param {string} fieldId - The password field ID
     */
    togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.type = field.type === "password" ? "text" : "password";
        }
    },

    /**
     * Format file size to human-readable format
     * @param {number} bytes - File size in bytes
     * @returns {string} Formatted file size
     */
    formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return (
            Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i]
        );
    },

    /**
     * Restrict input to numbers only
     * @param {string} inputId - The input field ID
     */
    numbersOnly(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener("input", function (e) {
                this.value = this.value.replace(/[^0-9]/g, "");
            });
        }
    },

    /**
     * Initialize file upload with drag & drop support
     * @param {object} config - Configuration object
     * @param {string} config.inputId - File input element ID
     * @param {string} config.dropZoneId - Drop zone element ID
     * @param {string} config.placeholderId - Placeholder element ID
     * @param {string} config.previewId - Preview container ID
     * @param {string} config.previewImageId - Preview image element ID
     * @param {string} config.fileNameId - File name display element ID
     * @param {string} config.fileSizeId - File size display element ID
     * @param {Array} config.validTypes - Valid MIME types (default: images)
     * @param {number} config.maxSizeMB - Maximum file size in MB (default: 2)
     */
    initFileUpload(config) {
        const {
            inputId,
            dropZoneId,
            placeholderId,
            previewId,
            previewImageId,
            fileNameId,
            fileSizeId,
            validTypes = ["image/jpeg", "image/jpg", "image/png"],
            maxSizeMB = 2,
        } = config;

        const input = document.getElementById(inputId);
        const dropZone = document.getElementById(dropZoneId);
        const placeholder = document.getElementById(placeholderId);
        const preview = document.getElementById(previewId);
        const previewImage = document.getElementById(previewImageId);
        const fileName = document.getElementById(fileNameId);
        const fileSize = document.getElementById(fileSizeId);

        if (!input || !dropZone) return;

        // Click to open file dialog
        dropZone.addEventListener("click", () => {
            input.click();
        });

        // Handle file selection
        input.addEventListener("change", function () {
            handleUpload(this);
        });

        // Drag and drop events
        ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ["dragenter", "dragover"].forEach((eventName) => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add("border-orange-500", "bg-orange-100");
            });
        });

        ["dragleave", "drop"].forEach((eventName) => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove("border-orange-500", "bg-orange-100");
            });
        });

        dropZone.addEventListener("drop", (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            input.files = files;
            handleUpload(input);
        });

        function handleUpload(fileInput) {
            const file = fileInput.files[0];
            if (!file) return;

            // Validate file type
            if (!validTypes.includes(file.type)) {
                alert("Format file tidak valid! Gunakan format yang sesuai.");
                fileInput.value = "";
                return;
            }

            // Validate file size
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            if (file.size > maxSizeBytes) {
                alert(`Ukuran file terlalu besar! Maksimal ${maxSizeMB}MB.`);
                fileInput.value = "";
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function (e) {
                if (previewImage) {
                    previewImage.src = e.target.result;
                }
                if (fileName) {
                    fileName.textContent = file.name;
                }
                if (fileSize) {
                    fileSize.textContent = uiUtils.formatFileSize(file.size);
                }
                if (placeholder) {
                    placeholder.classList.add("hidden");
                }
                if (preview) {
                    preview.classList.remove("hidden");
                }
            };
            reader.readAsDataURL(file);
        }

        // Expose remove function
        window[`remove${inputId.charAt(0).toUpperCase() + inputId.slice(1)}`] =
            function () {
                input.value = "";
                if (placeholder) {
                    placeholder.classList.remove("hidden");
                }
                if (preview) {
                    preview.classList.add("hidden");
                }
            };
    },

    /**
     * Show loading state on button
     * @param {string} buttonId - Button element ID
     * @param {string} text - Loading text (default: 'Loading...')
     */
    buttonLoading(buttonId, text = "Loading...") {
        const button = document.getElementById(buttonId);
        if (button) {
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = text;
        }
    },

    /**
     * Reset button state
     * @param {string} buttonId - Button element ID
     */
    buttonReset(buttonId) {
        const button = document.getElementById(buttonId);
        if (button && button.dataset.originalText) {
            button.disabled = false;
            button.textContent = button.dataset.originalText;
        }
    },

    /**
     * Confirm action with custom message
     * @param {string} message - Confirmation message
     * @param {Function} callback - Function to call if confirmed
     */
    confirm(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },
};
