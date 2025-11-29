<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelamar</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 700px; margin: 0 auto; padding: 1rem; }
        .header { text-align: center; margin-bottom: 2rem; }
        .title { font-size: 2rem; font-weight: bold; color: #0f172a; }
        .subtitle { font-size: 1rem; color: #64748b; }
        .grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 640px) { .grid { grid-template-columns: 1fr 1fr; } }
        .card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px #e5e7eb; padding: 1.5rem; }
        .card-title { font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; }
        .card-value { font-size: 1rem; color: #334155; }
        .footer { text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Detail Pelamar</div>
            <div class="subtitle">Lowongan: {{ $jobTitle }}<br>Perusahaan: {{ $companyName }}</div>
        </div>
        <div class="grid">
            <div class="card">
                <div class="card-title">Nama</div>
                <div class="card-value">{{ $applicant->nama }}</div>
            </div>
            <div class="card">
                <div class="card-title">Email</div>
                <div class="card-value">{{ $applicant->email }}</div>
            </div>
            <div class="card">
                <div class="card-title">Status Lamaran</div>
                <div class="card-value">{{ $applicant->status }}</div>
            </div>
            <div class="card">
                <div class="card-title">Tanggal Melamar</div>
                <div class="card-value">{{ $applicant->tanggal_terkirim->format('d F Y, H:i') }} WIB</div>
            </div>
            <div class="card">
                <div class="card-title">CV</div>
                <div class="card-value"><a href="{{ Storage::url($applicant->cv) }}" target="_blank">Download CV</a></div>
            </div>
            <div class="card">
                <div class="card-title">KTP</div>
                <div class="card-value"><a href="{{ Storage::url($applicant->ktp) }}" target="_blank">Download KTP</a></div>
            </div>
            @if($applicant->sertifikat)
            <div class="card">
                <div class="card-title">Sertifikat</div>
                <div class="card-value"><a href="{{ Storage::url($applicant->sertifikat) }}" target="_blank">Download Sertifikat</a></div>
            </div>
            @endif
            <div class="card">
                <div class="card-title">Foto Diri</div>
                <div class="card-value"><a href="{{ Storage::url($applicant->foto_diri) }}" target="_blank">Lihat Foto</a></div>
            </div>
        </div>
        <div class="footer">
            Dicetak pada: {{ date('d F Y, H:i') }} WIB
        </div>
    </div>
</body>
</html>
