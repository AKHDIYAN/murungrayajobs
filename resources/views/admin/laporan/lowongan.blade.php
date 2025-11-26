<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lowongan Kerja</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }
        
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px solid #ea580c; padding-bottom: 10px; }
        .header h1 { font-size: 16pt; color: #ea580c; margin-bottom: 3px; }
        .header .info { font-size: 8pt; color: #888; margin-top: 5px; }
        
        .summary { background: #fff7ed; border: 2px solid #ea580c; padding: 10px; margin-bottom: 15px; }
        .summary div { display: inline-block; margin-right: 20px; }
        .summary .label { font-size: 8pt; color: #666; }
        .summary .value { font-size: 14pt; font-weight: bold; color: #ea580c; }
        
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: #ea580c; color: white; padding: 6px; text-align: left; font-size: 8pt; }
        .table td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; font-size: 8pt; }
        .table tr:nth-child(even) { background: #fef3c7; }
        
        .footer { margin-top: 20px; text-align: center; font-size: 7pt; color: #888; padding-top: 10px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LOWONGAN KERJA</h1>
        <div class="info">Periode: {{ $tanggal_mulai }} s/d {{ $tanggal_akhir }} | Dicetak: {{ $generated_at }}</div>
    </div>

    <div class="summary">
        <div>
            <div class="label">Total Lowongan:</div>
            <div class="value">{{ number_format($total_lowongan) }}</div>
        </div>
        <div>
            <div class="label">Total Perusahaan:</div>
            <div class="value">{{ number_format($total_perusahaan) }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 25%;">Posisi</th>
                <th style="width: 20%;">Perusahaan</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 12%;">Kecamatan</th>
                <th style="width: 8%;">Gaji</th>
                <th style="width: 10%;">Tgl Posting</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowongan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->posisi }}</strong></td>
                <td>{{ $item->perusahaan->nama_perusahaan ?? '-' }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                <td>{{ $item->gaji_display }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_posting)->format('d/m/Y') }}</td>
                <td>
                    @if($item->is_active)
                    <span style="color: green; font-weight: bold;">Aktif</span>
                    @else
                    <span style="color: red;">Tidak Aktif</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Murung Raya</strong></p>
        <p>Komp. Bupati Murung Raya, Beriwit, Kec. Murung, Kabupaten Murung Raya, Kalimantan Tengah 73911</p>
    </div>
</body>
</html>
