<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Pelatihan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', serif;
            background: #f0f0f0;
        }
        .certificate {
            width: 100%;
            max-width: 267mm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px;
            margin: 0 auto;
        }
        .border-frame {
            border: 6px solid #FFD700;
            background: white;
            padding: 20px;
        }
        .inner-border {
            border: 2px solid #667eea;
            padding: 30px 40px;
            text-align: center;
        }
        .certificate-title {
            font-size: 42px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }
        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            border-bottom: 3px solid #FFD700;
            padding-bottom: 5px;
            display: inline-block;
        }
        .description {
            font-size: 14px;
            color: #555;
            margin: 15px 0;
        }
        .training-name {
            font-size: 22px;
            font-weight: bold;
            color: #667eea;
            margin: 15px 0;
        }
        .details {
            font-size: 13px;
            color: #666;
            line-height: 1.8;
            margin: 15px 0;
        }
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-line {
            width: 150px;
            border-top: 1px solid #333;
            margin: 50px auto 5px;
        }
        .signature-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .signature-title {
            font-size: 12px;
            color: #666;
        }
        .certificate-number {
            margin-top: 20px;
            font-size: 10px;
            color: #999;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-frame">
            <div class="inner-border">
                <div class="certificate-title">SERTIFIKAT</div>
                <div class="subtitle">Diberikan Kepada</div>
                
                <div class="recipient-name">{{ $user->nama }}</div>
                
                <div class="description">
                    Telah berhasil menyelesaikan program pelatihan
                </div>
                
                <div class="training-name">{{ $pelatihan->nama_pelatihan }}</div>
                
                <div class="details">
                    Diselenggarakan oleh <strong>{{ $pelatihan->penyelenggara }}</strong><br>
                    Periode {{ $pelatihan->tanggal_mulai->format('d F Y') }} - {{ $pelatihan->tanggal_selesai->format('d F Y') }}<br>
                    Durasi {{ $pelatihan->durasi_hari }} Hari
                </div>
                
                <div class="signature-section">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $pelatihan->instruktur }}</div>
                        <div class="signature-title">Instruktur</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">Kepala Dinas</div>
                        <div class="signature-title">Dinas Tenaga Kerja Kab. Murung Raya</div>
                    </div>
                </div>
                
                <div class="certificate-number">
                    No. Sertifikat: MURA/PELATIHAN/{{ $pelatihan->id_pelatihan }}/{{ $peserta->id_peserta_pelatihan }}/{{ date('Y') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
