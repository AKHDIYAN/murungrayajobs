/**
 * Statistics Charts - Ketenagakerjaan Murung Raya
 * Initialized on page load
 */

document.addEventListener("DOMContentLoaded", function () {
    // Chart color palette
    const chartColors = {
        primary: "#f59e0b",
        secondary: "#ea580c",
        success: "#10b981",
        danger: "#ef4444",
        info: "#3b82f6",
        warning: "#f59e0b",
        purple: "#8b5cf6",
        pink: "#ec4899",
    };

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
    };

    /**
     * Initialize Gender Distribution Chart (Doughnut)
     */
    const initGenderChart = (labels, data) => {
        const ctx = document.getElementById("genderChart");
        if (!ctx || !data.length) return;

        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: labels,
                datasets: [
                    {
                        data: data,
                        backgroundColor: [chartColors.info, chartColors.pink],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        });
    };

    /**
     * Initialize Employment Status Chart (Pie)
     */
    const initStatusChart = (bekerja, menganggur) => {
        const ctx = document.getElementById("statusChart");
        if (!ctx) return;

        new Chart(ctx, {
            type: "pie",
            data: {
                labels: ["Bekerja", "Menganggur"],
                datasets: [
                    {
                        data: [bekerja, menganggur],
                        backgroundColor: [
                            chartColors.success,
                            chartColors.danger,
                        ],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        });
    };

    /**
     * Initialize District Chart (Grouped Bar)
     */
    const initKecamatanChart = (labels, dataBekerja, dataMenganggur) => {
        const ctx = document.getElementById("kecamatanChart");
        if (!ctx || !labels.length) return;

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Bekerja",
                        data: dataBekerja,
                        backgroundColor: chartColors.success,
                    },
                    {
                        label: "Menganggur",
                        data: dataMenganggur,
                        backgroundColor: chartColors.danger,
                    },
                ],
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
                scales: {
                    x: {
                        stacked: false,
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true,
                    },
                },
            },
        });
    };

    /**
     * Initialize Education Chart (Horizontal Bar)
     */
    const initPendidikanChart = (labels, dataBekerja, dataMenganggur) => {
        const ctx = document.getElementById("pendidikanChart");
        if (!ctx || !labels.length) return;

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Bekerja",
                        data: dataBekerja,
                        backgroundColor: chartColors.success,
                    },
                    {
                        label: "Menganggur",
                        data: dataMenganggur,
                        backgroundColor: chartColors.danger,
                    },
                ],
            },
            options: {
                ...commonOptions,
                indexAxis: "y",
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                },
            },
        });
    };

    /**
     * Initialize Age Group Chart (Bar)
     */
    const initUsiaChart = (labels, data) => {
        const ctx = document.getElementById("usiaChart");
        if (!ctx || !labels.length) return;

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Total",
                        data: data,
                        backgroundColor: chartColors.primary,
                    },
                ],
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    };

    /**
     * Initialize Sector Chart (Horizontal Bar)
     */
    const initSektorChart = (labels, data) => {
        const ctx = document.getElementById("sektorChart");
        if (!ctx || !labels.length) return;

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Jumlah Pekerja",
                        data: data,
                        backgroundColor: chartColors.info,
                    },
                ],
            },
            options: {
                ...commonOptions,
                indexAxis: "y",
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                },
            },
        });
    };

    // Expose init functions globally for blade to call
    window.statisticsCharts = {
        initGenderChart,
        initStatusChart,
        initKecamatanChart,
        initPendidikanChart,
        initUsiaChart,
        initSektorChart,
    };
});
