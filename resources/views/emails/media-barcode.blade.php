<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Registration Barcode</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #111;
            color: #00e5ff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #00e5ff;
        }

        .body {
            padding: 30px;
            color: #333;
        }

        .barcode-section {
            text-align: center;
            background: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .barcode-section img {
            max-width: 100%;
            height: auto;
        }

        .barcode-token {
            font-size: 28px;
            letter-spacing: 5px;
            font-weight: bold;
            color: #111;
            margin-top: 10px;
        }

        .barcode-label {
            font-size: 14px;
            color: #888;
            margin-bottom: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .info-table td:first-child {
            color: #888;
            width: 40%;
        }

        .info-table td:last-child {
            font-weight: 600;
        }

        .status-pending {
            background: #f9a825;
            color: black;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: bold;
        }

        .footer {
            padding: 20px 30px;
            background: #f5f5f5;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Barcode Media {{ $registration->media_name }}</h1>
        </div>

        <div class="body">
            <p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
            <p>Terima kasih telah mendaftarkan media Anda. Berikut adalah barcode unik untuk registrasi media Anda:</p>

            <div class="barcode-section" style="background:#ffffff;">
                <div class="barcode-label" style="color:#888;">Gunakan kode berikut untuk absensi</div>
                <div class="barcode-token" style="font-size:32px;letter-spacing:8px;color:#111;">
                    {{ $registration->barcode_token }}
                </div>
                <p style="color:#666;font-size:13px;margin-top:15px;">
                    File gambar barcode terlampir sebagai attachment di email ini.
                </p>
            </div>

            <table class="info-table">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>{{ $registration->full_name }}</td>
                </tr>
                <tr>
                    <td>Nama Media</td>
                    <td>{{ $registration->media_name }}</td>
                </tr>
                <tr>
                    <td>Kategori Lomba</td>
                    <td>{{ $registration->competition_category }}</td>
                </tr>
                <tr>
                    <td>Equipment</td>
                    <td>{{ $registration->equipment_used }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <span class="status-pending">{{ $registration->status }}</span>
                    </td>
                </tr>
            </table>

            <p style="margin-top:20px;font-size:13px;color:#888;">
                <strong>Catatan:</strong> Barcode ini hanya dapat digunakan untuk absensi setelah status pendaftaran
                Anda disetujui oleh admin. Anda dapat mengecek status terbaru melalui halaman login media di website
                kami.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} AMG Owners Surabaya. All Rights Reserved.
        </div>
    </div>
</body>

</html>
