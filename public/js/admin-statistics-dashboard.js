/**
 * Admin Statistics Dashboard Charts
 * Handles Chart.js initialization for admin statistics page
 */

window.adminStatistics = {
    /**
     * Chart color palette
     */
    colors: {
        primary: "rgb(249, 115, 22)", // orange-500
        secondary: "rgb(251, 146, 60)", // orange-400
        success: "rgb(34, 197, 94)", // green-500
        warning: "rgb(251, 191, 36)", // amber-500
        danger: "rgb(239, 68, 68)", // red-500
        info: "rgb(59, 130, 246)", // blue-500
        purple: "rgb(168, 85, 247)", // purple-500
    },

    /**
     * Initialize Applications by Month Chart (Line Chart)
     *
     * @param {Array} months - Array of month labels
     * @param {Array} totals - Array of application counts
     */
    initApplicationsChart(months, totals) {
        const ctx = document.getElementById("applicationsChart");
        if (!ctx) return;

        new Chart(ctx.getContext("2d"), {
            type: "line",
            data: {
                labels: months,
                datasets: [
                    {
                        label: "Jumlah Lamaran",
                        data: totals,
                        borderColor: this.colors.primary,
                        backgroundColor: "rgba(249, 115, 22, 0.1)",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                        },
                    },
                },
            },
        });
    },

    /**
     * Initialize Jobs by Category Chart (Bar Chart)
     *
     * @param {Array} categories - Array of category names
     * @param {Array} totals - Array of job counts
     */
    initCategoriesChart(categories, totals) {
        const ctx = document.getElementById("categoriesChart");
        if (!ctx) return;

        new Chart(ctx.getContext("2d"), {
            type: "bar",
            data: {
                labels: categories,
                datasets: [
                    {
                        label: "Jumlah Lowongan",
                        data: totals,
                        backgroundColor: [
                            this.colors.primary,
                            this.colors.secondary,
                            this.colors.info,
                            this.colors.success,
                            this.colors.warning,
                            this.colors.purple,
                        ],
                        borderRadius: 8,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    },

    /**
     * Initialize Users by District Chart (Doughnut Chart)
     *
     * @param {Array} districts - Array of district names
     * @param {Array} totals - Array of user counts
     */
    initDistrictsChart(districts, totals) {
        const ctx = document.getElementById("districtsChart");
        if (!ctx) return;

        new Chart(ctx.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: districts,
                datasets: [
                    {
                        data: totals,
                        backgroundColor: [
                            this.colors.primary,
                            this.colors.info,
                            this.colors.success,
                            this.colors.warning,
                            this.colors.danger,
                            this.colors.purple,
                            this.colors.secondary,
                        ],
                        borderWidth: 2,
                        borderColor: "#fff",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "right",
                        labels: {
                            padding: 15,
                            boxWidth: 12,
                        },
                    },
                },
            },
        });
    },

    /**
     * Initialize Application Status Chart (Pie Chart)
     *
     * @param {number} pending - Count of pending applications
     * @param {number} accepted - Count of accepted applications
     * @param {number} rejected - Count of rejected applications
     */
    initStatusChart(pending, accepted, rejected) {
        const ctx = document.getElementById("statusChart");
        if (!ctx) return;

        new Chart(ctx.getContext("2d"), {
            type: "pie",
            data: {
                labels: ["Pending", "Diterima", "Ditolak"],
                datasets: [
                    {
                        data: [pending, accepted, rejected],
                        backgroundColor: [
                            this.colors.warning,
                            this.colors.success,
                            this.colors.danger,
                        ],
                        borderWidth: 2,
                        borderColor: "#fff",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            padding: 15,
                            boxWidth: 12,
                        },
                    },
                },
            },
        });
    },
};
