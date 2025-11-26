<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kondisi Ketenagakerjaan</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #1e3a8a; padding-bottom: 15px; }
        .header h1 { font-size: 18pt; color: #1e3a8a; margin-bottom: 5px; }
        .header h2 { font-size: 14pt; color: #666; font-weight: normal; }
        .header .info { font-size: 9pt; color: #888; margin-top: 8px; }
        
        .section { margin-bottom: 20px; }
        .section-title { font-size: 12pt; font-weight: bold; color: #1e3a8a; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 2px solid #e5e7eb; }
        
        .stats-grid { display: table; width: 100%; margin-bottom: 15px; }
        .stat-card { display: table-cell; width: 25%; padding: 10px; text-align: center; border: 1px solid #e5e7eb; background: #f9fafb; }
        .stat-card .value { font-size: 20pt; font-weight: bold; color: #1e3a8a; }
        .stat-card .label { font-size: 9pt; color: #666; margin-top: 3px; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background: #1e3a8a; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
        .table tr:nth-child(even) { background: #f9fafb; }
        
        .footer { margin-top: 30px; text-align: center; font-size: 8pt; color: #888; padding-top: 15px; border-top: 1px solid #e5e7eb; }
        
        .chart-placeholder { background: #f0f9ff; border: 2px dashed #3b82f6; padding: 30px; text-align: center; color: #3b82f6; font-weight: bold; margin: 15px 0; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN KONDISI KETENAGAKERJAAN</h1>
        <h2>Kabupaten Murung Raya, Kalimantan Tengah</h2>
        <div class="info">
            Periode: {{ $bulan }} | Dicetak: {{ $generated_at }}
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="section">
        <div class="section-title">I. RINGKASAN STATISTIK</div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="value">{{ number_format($total_pencari_kerja) }}</div>
                <div class="label">Pencari Kerja</div>
            </div>
            <div class="stat-card">
                <div class="value">{{ number_format($total_perusahaan) }}</div>
                <div class="label">Perusahaan</div>
            </div>
            <div class="stat-card">
                <div class="value">{{ number_format($lowongan_aktif) }}</div>
                <div class="label">Lowongan Aktif</div>
            </div>
            <div class="stat-card">
                <div class="value">{{ number_format($total_lamaran) }}</div>
                <div class="label">Total Lamaran</div>
            </div>
        </div>
    </div>

    <!-- Sebaran Per Kecamatan -->
    <div class="section">
        <div class="section-title">II. SEBARAN PENCARI KERJA PER KECAMATAN</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 60%;">Kecamatan</th>
                    <th style="width: 20%;">Jumlah Pencari Kerja</th>
                    <th style="width: 15%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pencari_kerja_per_kecamatan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kecamatan->nama_kecamatan ?? 'Tidak Diketahui' }}</td>
                    <td style="text-align: center;">{{ number_format($item->total) }}</td>
                    <td style="text-align: center;">{{ $total_pencari_kerja > 0 ? number_format(($item->total / $total_pencari_kerja) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
                <tr style="background: #e5e7eb; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td style="text-align: center;">{{ number_format($total_pencari_kerja) }}</td>
                    <td style="text-align: center;">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Lowongan Per Kecamatan -->
    <div class="section">
        <div class="section-title">III. SEBARAN LOWONGAN KERJA PER KECAMATAN</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 60%;">Kecamatan</th>
                    <th style="width: 20%;">Jumlah Lowongan</th>
                    <th style="width: 15%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowongan_per_kecamatan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kecamatan->nama_kecamatan ?? 'Tidak Diketahui' }}</td>
                    <td style="text-align: center;">{{ number_format($item->total) }}</td>
                    <td style="text-align: center;">{{ $total_lowongan > 0 ? number_format(($item->total / $total_lowongan) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
                <tr style="background: #e5e7eb; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td style="text-align: center;">{{ number_format($total_lowongan) }}</td>
                    <td style="text-align: center;">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Lowongan Per Sektor -->
    <div class="section">
        <div class="section-title">IV. LOWONGAN KERJA PER SEKTOR</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 60%;">Sektor/Kategori</th>
                    <th style="width: 20%;">Jumlah Lowongan</th>
                    <th style="width: 15%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowongan_per_sektor as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kategori->nama_kategori ?? 'Tidak Dikategorikan' }}</td>
                    <td style="text-align: center;">{{ number_format($item->total) }}</td>
                    <td style="text-align: center;">{{ $total_lowongan > 0 ? number_format(($item->total / $total_lowongan) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
                <tr style="background: #e5e7eb; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td style="text-align: center;">{{ number_format($total_lowongan) }}</td>
                    <td style="text-align: center;">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Status Lamaran -->
    <div class="section">
        <div class="section-title">V. STATISTIK LAMARAN</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 50%;">Status</th>
                    <th style="width: 20%;">Jumlah</th>
                    <th style="width: 20%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Menunggu Review</td>
                    <td style="text-align: center;">{{ number_format($lamaran_pending) }}</td>
                    <td style="text-align: center;">{{ $total_lamaran > 0 ? number_format(($lamaran_pending / $total_lamaran) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Diterima</td>
                    <td style="text-align: center;">{{ number_format($lamaran_diterima) }}</td>
                    <td style="text-align: center;">{{ $total_lamaran > 0 ? number_format(($lamaran_diterima / $total_lamaran) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Ditolak</td>
                    <td style="text-align: center;">{{ number_format($lamaran_ditolak) }}</td>
                    <td style="text-align: center;">{{ $total_lamaran > 0 ? number_format(($lamaran_ditolak / $total_lamaran) * 100, 1) : 0 }}%</td>
                </tr>
                <tr style="background: #e5e7eb; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td style="text-align: center;">{{ number_format($total_lamaran) }}</td>
                    <td style="text-align: center;">100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pelatihan -->
    <div class="section">
        <div class="section-title">VI. PROGRAM PELATIHAN</div>
        <div class="stats-grid">
            <div class="stat-card" style="width: 50%;">
                <div class="value">{{ number_format($total_pelatihan) }}</div>
                <div class="label">Total Program Pelatihan</div>
            </div>
            <div class="stat-card" style="width: 50%;">
                <div class="value">{{ number_format($total_peserta_pelatihan) }}</div>
                <div class="label">Total Peserta Pelatihan</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Murung Raya</strong></p>
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Informasi Ketenagakerjaan</p>
    </div>
</body>
</html>
