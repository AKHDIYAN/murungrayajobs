<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelamar</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 700px; margin: 0 auto; padding: 1rem; }
        .header { text-align: center; margin-bottom: 2rem; }
        .title { font-size: 2rem; font-weight: bold; color: #0f172a; }
        .subtitle { font-size: 1rem; color: #64748b; }
        .table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .table th, .table td { padding: 0.75rem; border: 1px solid #e5e7eb; text-align: left; font-size: 0.95rem; }
        .table th { background: #f1f5f9; font-weight: 600; color: #1e293b; }
        .table tr:nth-child(even) { background: #f9fafb; }
        .footer { text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #64748b; }
        @media (max-width: 640px) { .table th, .table td { font-size: 0.85rem; padding: 0.5rem; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Daftar Pelamar</div>
            <div class="subtitle">Lowongan: {{ $jobTitle }}<br>Perusahaan: {{ $companyName }}</div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Tanggal Melamar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicants as $i => $applicant)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $applicant->nama }}</td>
                    <td>{{ $applicant->email }}</td>
                    <td>{{ $applicant->status }}</td>
                    <td>{{ $applicant->tanggal_terkirim->format('d F Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            Dicetak pada: {{ date('d F Y, H:i') }} WIB
        </div>
    </div>
</body>
</html>
