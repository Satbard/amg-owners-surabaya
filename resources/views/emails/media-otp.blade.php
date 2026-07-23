<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Login OTP</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #0a0a0a;
            color: #00e5ff;
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #00e5ff;
        }

        .header .brand {
            color: #ffffff;
            font-size: 13px;
            margin-top: 5px;
            opacity: 0.7;
        }

        .body {
            padding: 30px;
            text-align: center;
            color: #333;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        .otp-code {
            font-size: 38px;
            letter-spacing: 10px;
            font-weight: bold;
            color: #0a0a0a;
            background: #f0f0f0;
            padding: 18px 30px;
            border-radius: 10px;
            display: inline-block;
            margin: 20px 0;
        }

        .info-text {
            font-size: 14px;
            color: #888;
            margin-top: 20px;
            line-height: 1.6;
        }

        .footer {
            padding: 20px 30px;
            background: #f9f9f9;
            text-align: center;
            font-size: 12px;
            color: #aaa;
            line-height: 1.6;
        }

        .footer a {
            color: #00e5ff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Kode Verifikasi</h1>
            <div class="brand">AMG Owners Surabaya — Media Dashboard</div>
        </div>

        <div class="body">
            <div class="greeting">Halo <strong>{{ $registration->full_name }}</strong>,</div>

            <p style="color:#666;">Masukkan kode berikut untuk mengakses dashboard media Anda:</p>

            <div class="otp-code">{{ $otp }}</div>

            <p class="info-text">
                Kode ini berlaku selama <strong>10 menit</strong>.<br>
                Jika Anda tidak meminta kode ini, abaikan email ini.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} AMG Owners Surabaya. All Rights Reserved.<br>
            Jl. Demak No.166-168, Gundih, Kec. Bubutan, Surabaya, Jawa Timur 60172<br>
            <a href="{{ url('/media-login') }}">Media Login</a>
        </div>
    </div>
</body>

</html>
