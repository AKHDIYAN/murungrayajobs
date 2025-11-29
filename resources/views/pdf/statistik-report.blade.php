<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik Ketenagakerjaan</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 700px; margin: 0 auto; padding: 1rem; }
        .header { text-align: center; margin-bottom: 2rem; }
        .title { font-size: 2rem; font-weight: bold; color: #1e40af; }
        .subtitle { font-size: 1rem; color: #64748b; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 640px) { .grid { grid-template-columns: 1fr 1fr; } }
        @media (min-width: 1024px) { .container { max-width: 900px; } }
        .card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px #e5e7eb; padding: 1.5rem; }
        .card-title { font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; }
        .card-value { font-size: 2rem; font-weight: bold; color: #1e40af; }
        .footer { text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Laporan Statistik Ketenagakerjaan</div>
            <div class="subtitle">Kabupaten Murung Raya, Kalimantan Tengah</div>
        </div>
        <div class="grid">
            <div class="card">
                <div class="card-title">Total Pencari Kerja</div>
                <div class="card-value">{{ $totalUsers }}</div>
            </div>
            <div class="card">
                <div class="card-title">Total Perusahaan</div>
                <div class="card-value">{{ $totalCompanies }}</div>
            </div>
            <div class="card">
                <div class="card-title">Total Lowongan</div>
                <div class="card-value">{{ $totalJobs }}</div>
            </div>
            <div class="card">
                <div class="card-title">Total Lamaran</div>
                <div class="card-value">{{ $totalApplications }}</div>
            </div>
        </div>
        <div class="footer">
            Dicetak pada: {{ date('d F Y, H:i') }} WIB
        </div>
    </div>
</body>
</html>
