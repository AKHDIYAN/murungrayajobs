<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelatihan</title>
    <style>
        @page { size: A4 portrait; margin: 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #9333ea; padding-bottom: 15px; }
        .header h1 { font-size: 18pt; color: #9333ea; }
        .summary { background: #faf5ff; border: 2px solid #9333ea; padding: 15px; margin-bottom: 20px; text-align: center; }
        .summary .value { font-size: 24pt; font-weight: bold; color: #9333ea; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th { background: #9333ea; color: white; padding: 8px; font-size: 9pt; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
        .pelatihan-block { margin-bottom: 25px; page-break-inside: avoid; }
        .pelatihan-title { background: #f3e8ff; padding: 8px; font-weight: bold; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PELATIHAN</h1>
        <div>Periode: {{ $tanggal_mulai }} s/d {{ $tanggal_akhir }} | Dicetak: {{ $generated_at }}</div>
    </div>
    <div class="summary">
        <div>Total Program Pelatihan</div>
        <div class="value">{{ $total_pelatihan }}</div>
        <div>Total Peserta</div>
        <div class="value">{{ $total_peserta }}</div>
    </div>
    @foreach($pelatihan as $p)
    <div class="pelatihan-block">
        <div class="pelatihan-title">{{ $p->nama_pelatihan }} - {{ $p->sektor->nama_kategori ?? '-' }}</div>
        <table class="table">
            <tr><td style="width: 30%; font-weight: bold;">Penyelenggara:</td><td>{{ $p->penyelenggara }}</td></tr>
            <tr><td style="font-weight: bold;">Lokasi:</td><td>{{ $p->lokasi }}</td></tr>
            <tr><td style="font-weight: bold;">Tanggal:</td><td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }} ({{ $p->durasi_hari }} hari)</td></tr>
            <tr><td style="font-weight: bold;">Kuota:</td><td>{{ $p->jumlah_peserta }}/{{ $p->kuota_peserta }} peserta</td></tr>
        </table>
        @if($p->peserta->count() > 0)
        <table class="table" style="margin-top: 10px;">
            <thead><tr><th>No</th><th>Nama Peserta</th><th>Email</th><th>Tanggal Daftar</th></tr></thead>
            <tbody>
                @foreach($p->peserta as $i => $ps)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $ps->user->nama }}</td>
                    <td>{{ $ps->user->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($ps->tanggal_daftar)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endforeach
</body>
</html>