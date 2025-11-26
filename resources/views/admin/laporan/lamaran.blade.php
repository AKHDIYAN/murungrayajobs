<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lamaran</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 3px solid: #10b981; padding-bottom: 10px; }
        .header h1 { font-size: 16pt; color: #10b981; }
        .summary { background: #f0fdf4; border: 2px solid #10b981; padding: 10px; margin-bottom: 15px; }
        .summary div { display: inline-block; margin-right: 15px; }
        .summary .value { font-size: 14pt; font-weight: bold; color: #10b981; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: #10b981; color: white; padding: 6px; font-size: 8pt; }
        .table td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; font-size: 8pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LAMARAN</h1>
        <div>Periode: {{ $tanggal_mulai }} s/d {{ $tanggal_akhir }}</div>
    </div>
    <div class="summary">
        <div><div>Total:</div><div class="value">{{ $total_lamaran }}</div></div>
        <div><div>Pending:</div><div class="value">{{ $total_pending }}</div></div>
        <div><div>Diterima:</div><div class="value">{{ $total_diterima }}</div></div>
        <div><div>Ditolak:</div><div class="value">{{ $total_ditolak }}</div></div>
    </div>
    <table class="table">
        <thead><tr>
            <th>No</th><th>Nama</th><th>Posisi</th><th>Perusahaan</th><th>Tanggal</th><th>Status</th>
        </tr></thead>
        <tbody>
            @foreach($lamaran as $i => $l)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $l->user->nama }}</td>
                <td>{{ $l->pekerjaan->posisi }}</td>
                <td>{{ $l->pekerjaan->perusahaan->nama_perusahaan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($l->tanggal_terkirim)->format('d/m/Y') }}</td>
                <td style="color: @if($l->status == 'Diterima') green @elseif($l->status == 'Ditolak') red @else orange @endif">{{ $l->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>