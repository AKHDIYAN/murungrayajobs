/**
 * Admin Modal & Common Functions
 * Reusable modal and confirmation functions for admin pages
 */

window.adminCommon = {
    /**
     * Open modal by ID
     * @param {string} modalId - The modal element ID
     */
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove("hidden");
        }
    },

    /**
     * Close modal by ID
     * @param {string} modalId - The modal element ID
     */
    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add("hidden");
        }
    },

    /**
     * Open edit modal with data
     * @param {string} modalId - The modal element ID
     * @param {string} formId - The form element ID
     * @param {string} actionUrl - The form action URL (use :id placeholder)
     * @param {object} data - Object containing field IDs and values
     */
    openEditModal(modalId, formId, actionUrl, data) {
        // Update form action
        const form = document.getElementById(formId);
        if (form && data.id) {
            form.action = actionUrl.replace(":id", data.id);
        }

        // Fill form fields
        for (const [key, value] of Object.entries(data)) {
            const field = document.getElementById(key);
            if (field) {
                field.value = value;
            }
        }

        this.openModal(modalId);
    },

    /**
     * Confirm delete action
     * @param {string|number} id - The record ID
     * @param {string} message - Custom confirmation message
     * @param {string} formIdPrefix - Prefix for delete form ID (default: 'delete-form-')
     */
    confirmDelete(
        id,
        message = "Yakin ingin menghapus data ini?",
        formIdPrefix = "delete-form-"
    ) {
        if (confirm(message)) {
            const form = document.getElementById(formIdPrefix + id);
            if (form) {
                form.submit();
            }
        }
    },

    /**
     * Initialize escape key listener for modals
     * @param {Array} modalIds - Array of modal IDs to close on escape
     */
    initEscapeKey(modalIds) {
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                modalIds.forEach((modalId) => this.closeModal(modalId));
            }
        });
    },

    /**
     * Filter table rows based on search input
     * @param {string} searchInputId - The search input element ID
     * @param {string} tableBodyId - The table body element ID
     * @param {number} columnIndex - The column index to search in
     */
    filterTable(searchInputId, tableBodyId, columnIndex = 0) {
        const searchInput = document.getElementById(searchInputId);
        const tableBody = document.getElementById(tableBodyId);

        if (!searchInput || !tableBody) return;

        searchInput.addEventListener("input", function () {
            const filter = this.value.toLowerCase();
            const rows = tableBody.getElementsByTagName("tr");

            Array.from(rows).forEach((row) => {
                const cell = row.getElementsByTagName("td")[columnIndex];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    row.style.display = textValue.toLowerCase().includes(filter)
                        ? ""
                        : "none";
                }
            });
        });
    },

    /**
     * Show success message (Tailwind toast)
     * @param {string} message - Success message to display
     */
    showSuccess(message) {
        this.showToast(message, "success");
    },

    /**
     * Show error message (Tailwind toast)
     * @param {string} message - Error message to display
     */
    showError(message) {
        this.showToast(message, "error");
    },

    /**
     * Show toast notification
     * @param {string} message - Message to display
     * @param {string} type - Toast type (success, error, info)
     */
    showToast(message, type = "info") {
        const colors = {
            success: "bg-green-500",
            error: "bg-red-500",
            info: "bg-blue-500",
        };

        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },
};

// Master Data specific functions
window.masterData = {
    /**
     * Open add modal for master data
     */
    openAddModal() {
        adminCommon.openModal("addModal");
    },

    /**
     * Close add modal for master data
     */
    closeAddModal() {
        adminCommon.closeModal("addModal");
    },

    /**
     * Open edit modal for master data (kecamatan, sektor, pendidikan, usia)
     * @param {number} id - Record ID
     * @param {string} nama - Record name
     * @param {string} updateRoute - Laravel route name for update
     * @param {string} fieldId - Optional field ID to populate
     */
    openEditModal(id, nama, updateRoute, fieldId = null) {
        const form = document.getElementById("editForm");
        if (form) {
            // Create action URL from route
            form.action = updateRoute;
        }

        // Try to find the field - either specified or auto-detect
        const nameField = fieldId
            ? document.getElementById(fieldId)
            : document.getElementById("edit_nama_kecamatan") ||
              document.getElementById("edit_nama_kategori") ||
              document.getElementById("edit_tingkatan_pendidikan") ||
              document.getElementById("edit_kelompok_usia");

        if (nameField) {
            nameField.value = nama;
        }

        adminCommon.openModal("editModal");
    },

    /**
     * Close edit modal for master data
     */
    closeEditModal() {
        adminCommon.closeModal("editModal");
    },

    /**
     * Confirm delete for master data
     * @param {number} id - Record ID
     * @param {string} type - Type of data (kecamatan, sektor, etc.)
     */
    confirmDelete(id, type = "data") {
        adminCommon.confirmDelete(id, `Yakin ingin menghapus ${type} ini?`);
    },
};

// Initialize escape key for common modals
document.addEventListener("DOMContentLoaded", function () {
    adminCommon.initEscapeKey([
        "addModal",
        "editModal",
        "viewModal",
        "deleteModal",
    ]);
});
