/**
 * Map Page JavaScript
 * Handles Leaflet map initialization and kecamatan markers
 */

// Global map instance
window.mapInstance = null;

// Kecamatan coordinates data
const kecamatanCoordinates = [
    {
        nama: "Muara Laung",
        lat: -0.95,
        lng: 114.75,
        jumlah_lowongan: 0,
        slug: "muara-laung",
    },
    {
        nama: "Laung Tuhup",
        lat: -1.02,
        lng: 114.82,
        jumlah_lowongan: 0,
        slug: "laung-tuhup",
    },
    {
        nama: "Barito Tuhup Raya",
        lat: -0.88,
        lng: 114.68,
        jumlah_lowongan: 0,
        slug: "barito-tuhup-raya",
    },
    {
        nama: "Permata Intan",
        lat: -1.1,
        lng: 114.78,
        jumlah_lowongan: 0,
        slug: "permata-intan",
    },
    {
        nama: "Sumber Barito",
        lat: -0.98,
        lng: 114.62,
        jumlah_lowongan: 0,
        slug: "sumber-barito",
    },
    {
        nama: "Sungai Babuat",
        lat: -1.05,
        lng: 114.72,
        jumlah_lowongan: 0,
        slug: "sungai-babuat",
    },
    {
        nama: "Tanah Siang",
        lat: -0.92,
        lng: 114.88,
        jumlah_lowongan: 0,
        slug: "tanah-siang",
    },
    {
        nama: "Tanah Siang Selatan",
        lat: -1.0,
        lng: 114.92,
        jumlah_lowongan: 0,
        slug: "tanah-siang-selatan",
    },
    {
        nama: "Seribu Riam",
        lat: -0.85,
        lng: 114.78,
        jumlah_lowongan: 0,
        slug: "seribu-riam",
    },
    {
        nama: "Uut Murung",
        lat: -0.9,
        lng: 114.65,
        jumlah_lowongan: 0,
        slug: "uut-murung",
    },
];

/**
 * Get marker style based on job count
 * @param {number} jumlah - Number of jobs
 * @returns {Object} Style configuration
 */
function getMarkerStyle(jumlah) {
    if (jumlah === 0 || jumlah <= 5) {
        return { color: "#9ca3af", size: "small", radius: 8, weight: 2 }; // Gray
    } else if (jumlah <= 15) {
        return { color: "#fbbf24", size: "medium", radius: 12, weight: 3 }; // Yellow
    } else if (jumlah <= 30) {
        return { color: "#f97316", size: "large", radius: 16, weight: 4 }; // Orange
    } else {
        return { color: "#ef4444", size: "extra-large", radius: 20, weight: 4 }; // Red
    }
}

/**
 * Create popup content HTML
 * @param {Object} kec - Kecamatan data
 * @param {string} jobsRoute - Route URL for jobs listing
 * @returns {string} HTML content
 */
function createPopupContent(kec, jobsRoute) {
    return `
        <div style="min-width: 200px;">
            <h3 style="font-size: 18px; font-weight: 700; color: #1e40af; margin-bottom: 8px;">
                ${kec.nama}
            </h3>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding: 8px; background: #eff6ff; border-radius: 6px;">
                <svg style="width: 20px; height: 20px; color: #3b82f6;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <div style="font-size: 12px; color: #64748b;">Lowongan Aktif</div>
                    <div style="font-size: 24px; font-weight: 800; color: #1e40af;">${
                        kec.jumlah_lowongan
                    }</div>
                </div>
            </div>
            ${
                kec.jumlah_lowongan > 0
                    ? `
            <a href="${jobsRoute}?kecamatan=${kec.slug}" 
               style="display: block; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; 
                      text-align: center; padding: 10px 16px; border-radius: 8px; text-decoration: none; 
                      font-weight: 600; font-size: 14px; transition: all 0.3s;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(30,64,175,0.4)';"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                🔍 Lihat Lowongan di ${kec.nama}
            </a>
            `
                    : `
            <div style="text-align: center; padding: 10px; background: #f3f4f6; border-radius: 8px; color: #6b7280; font-size: 13px;">
                Belum ada lowongan tersedia
            </div>
            `
            }
        </div>
    `;
}

/**
 * Initialize map with kecamatan markers
 * @param {Array} kecamatanStatsFromServer - Server data with job counts
 * @param {string} jobsRoute - Route URL for jobs listing
 */
function initializeMap(kecamatanStatsFromServer, jobsRoute) {
    const kecamatanData = [...kecamatanCoordinates];

    // Update jumlah lowongan from server data
    kecamatanData.forEach((kec) => {
        const serverData = kecamatanStatsFromServer.find(
            (s) => s.nama_kecamatan.toLowerCase() === kec.nama.toLowerCase()
        );
        if (serverData) {
            kec.jumlah_lowongan = parseInt(serverData.total) || 0;
        }
    });

    // Initialize map centered on Murung Raya
    const map = L.map("map").setView([-0.95, 114.75], 10);

    // Add OpenStreetMap tiles
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
        minZoom: 8,
    }).addTo(map);

    // Add markers for each kecamatan
    kecamatanData.forEach(function (kec) {
        const style = getMarkerStyle(kec.jumlah_lowongan);

        // Create circle marker
        const marker = L.circleMarker([kec.lat, kec.lng], {
            radius: style.radius,
            fillColor: style.color,
            color: "#fff",
            weight: style.weight,
            opacity: 1,
            fillOpacity: 0.8,
            className: kec.jumlah_lowongan > 30 ? "marker-pulse" : "",
        }).addTo(map);

        // Bind popup
        marker.bindPopup(createPopupContent(kec, jobsRoute), {
            maxWidth: 300,
            className: "custom-popup",
        });

        // Hover effects
        marker.on("mouseover", function () {
            this.setStyle({
                radius: style.radius + 3,
                fillOpacity: 1,
            });
        });

        marker.on("mouseout", function () {
            this.setStyle({
                radius: style.radius,
                fillOpacity: 0.8,
            });
        });
    });

    // Store map reference globally
    window.mapInstance = map;

    // Disable scroll zoom until map is clicked
    map.scrollWheelZoom.disable();
    map.on("click", function () {
        map.scrollWheelZoom.enable();
    });

    // Re-disable when mouse leaves map
    map.getContainer().addEventListener("mouseleave", function () {
        map.scrollWheelZoom.disable();
    });
}

/**
 * Reset map view to default center and zoom
 */
function resetMapView() {
    if (window.mapInstance) {
        window.mapInstance.setView([-0.95, 114.75], 10);
    }
}

// Export functions for use in views
if (typeof module !== "undefined" && module.exports) {
    module.exports = {
        initializeMap,
        resetMapView,
        getMarkerStyle,
        createPopupContent,
    };
}
