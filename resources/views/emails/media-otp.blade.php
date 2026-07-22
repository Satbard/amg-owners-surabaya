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
            background: #111;
            color: #00e5ff;
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .body {
            padding: 30px;
            text-align: center;
            color: #333;
        }

        .otp-code {
            font-size: 36px;
            letter-spacing: 8px;
            font-weight: bold;
            color: #111;
            background: #f0f0f0;
            padding: 15px 25px;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
        }

        .footer {
            padding: 20px;
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
            <h1>Media Login – Kode Verifikasi</h1>
        </div>

        <div class="body">
            <p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
            <p>Gunakan kode OTP berikut untuk masuk ke dashboard media Anda:</p>

            <div class="otp-code">{{ $otp }}</div>

            <p style="font-size:13px;color:#888;margin-top:20px;">
                Kode ini berlaku selama 10 menit. Jangan bagikan kode ini kepada siapa pun.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} AMG Owners Surabaya. All Rights Reserved.
        </div>
    </div>
</body>

</html>
