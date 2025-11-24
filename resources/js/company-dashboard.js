// Company Dashboard Interactive Features

/**
 * Update current date and time
 */
function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    const dateStr = now.toLocaleDateString("id-ID", options);
    const timeStr = now.toLocaleTimeString("id-ID");

    const dateElement = document.getElementById("currentDate");
    const timeElement = document.getElementById("currentTime");

    if (dateElement) dateElement.textContent = dateStr;
    if (timeElement) timeElement.textContent = timeStr;
}

/**
 * Auto-hide notifications after 5 seconds
 */
function autoHideNotifications() {
    setTimeout(() => {
        const alerts = document.querySelectorAll(
            ".animate-bounce-in, .animate-shake"
        );
        alerts.forEach((alert) => {
            alert.style.transition = "opacity 0.5s, transform 0.5s";
            alert.style.opacity = "0";
            alert.style.transform = "translateX(100%)";
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    // Start date/time updates
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Auto-hide notifications
    autoHideNotifications();
});
