/**
 * Admin Dashboard Charts
 * Handles Chart.js initialization for dashboard statistics
 */

window.adminDashboard = {
    /**
     * Chart color palette
     */
    colors: {
        blue: "rgba(59, 130, 246, 0.8)",
        green: "rgba(16, 185, 129, 0.8)",
        purple: "rgba(139, 92, 246, 0.8)",
        orange: "rgba(251, 146, 60, 0.8)",
        red: "rgba(239, 68, 68, 0.8)",
    },

    /**
     * Common chart options
     */
    getCommonOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: "bold",
                    },
                    bodyFont: {
                        size: 13,
                    },
                    borderColor: "rgba(255, 255, 255, 0.2)",
                    borderWidth: 1,
                },
            },
        };
    },

    /**
     * Initialize Jobs per Company Chart (Bar Chart)
     *
     * @param {Array} companyNames - Array of company names
     * @param {Array} jobCounts - Array of job counts per company
     */
    initJobsPerCompanyChart(companyNames, jobCounts) {
        const ctx = document.getElementById("jobsPerCompanyChart");
        if (!ctx) return;

        new Chart(ctx.getContext("2d"), {
            type: "bar",
            data: {
                labels: companyNames,
                datasets: [
                    {
                        label: "Jumlah Lowongan",
                        data: jobCounts,
                        backgroundColor: this.colors.blue,
                        borderColor: "rgba(59, 130, 246, 1)",
                        borderWidth: 2,
                        borderRadius: 8,
                    },
                ],
            },
            options: {
                ...this.getCommonOptions(),
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                        grid: {
                            color: "rgba(0, 0, 0, 0.05)",
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    },

    /**
     * Initialize User Registration Chart (Line Chart)
     *
     * @param {Array} months - Array of month labels
     * @param {Array} userCounts - Array of user counts per month
     */
    initUserRegistrationChart(months, userCounts) {
        const ctx = document.getElementById("userRegistrationChart");
        if (!ctx) return;

        new Chart(ctx.getContext("2d"), {
            type: "line",
            data: {
                labels: months,
                datasets: [
                    {
                        label: "Pendaftaran User",
                        data: userCounts,
                        backgroundColor: "rgba(16, 185, 129, 0.1)",
                        borderColor: this.colors.green,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: "white",
                        pointBorderColor: this.colors.green,
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    },
                ],
            },
            options: {
                ...this.getCommonOptions(),
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                        },
                        grid: {
                            color: "rgba(0, 0, 0, 0.05)",
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    },
};
