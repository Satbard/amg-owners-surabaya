<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card Pass Access</title>
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
            <h1>ID Card Pass Access – {{ $registration->media_name }}</h1>
        </div>

        <div class="body">
            <p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
            <p>Terima kasih telah mendaftarkan media Anda. Berikut adalah ID Card Pass Access untuk registrasi media
                Anda:</p>

            <p style="background:#f0f0f0;padding:15px;border-radius:8px;text-align:center;font-size:14px;color:#555;">
                📎 File ID Card (JPG) terlampir sebagai attachment di email ini.
                Silakan download dan cetak untuk digunakan sebagai akses masuk acara.
            </p>

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
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} AMG Owners Surabaya. All Rights Reserved.
        </div>
    </div>
</body>

</html>
