/**
 * Job Detail Page JavaScript
 */

/**
 * Copy job link to clipboard
 * @param {string} url - The job URL to copy
 */
function copyJobLink(url) {
    navigator.clipboard
        .writeText(url)
        .then(() => {
            showToast("Link berhasil disalin!", "success");
        })
        .catch(() => {
            // Fallback for older browsers
            const textarea = document.createElement("textarea");
            textarea.value = url;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            showToast("Link berhasil disalin!", "success");
        });
}

/**
 * Show filename when file is selected
 * @param {HTMLInputElement} input - File input element
 * @param {string} targetId - ID of element to show filename
 */
function showFileName(input, targetId) {
    const target = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
        target.textContent = `✓ ${fileName} (${fileSize} MB)`;

        // Check file size
        if (input.files[0].size > 2 * 1024 * 1024) {
            target.textContent = `✗ File terlalu besar (${fileSize} MB). Maksimal 2 MB`;
            target.classList.remove("text-blue-600");
            target.classList.add("text-red-500");
            input.value = "";
        } else {
            target.classList.remove("text-red-500");
            target.classList.add("text-blue-600");
        }
    }
}

/**
 * Show toast notification
 * @param {string} message - Message to display
 * @param {string} type - Type of toast (info, success, error)
 */
function showToast(message, type = "info") {
    const toast = document.createElement("div");
    const bgColor =
        type === "success"
            ? "bg-green-500"
            : type === "error"
            ? "bg-red-500"
            : "bg-blue-500";
    toast.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("animate-fade-out");
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

/**
 * Initialize smooth scroll for apply form links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href="#apply-form"]').forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("apply-form").scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        });
    });
}

// Initialize on DOM load
document.addEventListener("DOMContentLoaded", initSmoothScroll);
