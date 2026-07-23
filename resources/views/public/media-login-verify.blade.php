@extends('layouts.app')

@section('content')
    <div
        style="
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:40px 20px;
        background:#0a0a0a;
    ">

        <div
            style="
            max-width:440px;
            width:100%;
            background:rgba(17,17,17,0.92);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:16px;
            padding:40px;
        ">

            <h1
                style="
                text-align:center;
                margin-bottom:10px;
                color:#00e5ff;
                font-size:24px;
            ">
                Verifikasi OTP
            </h1>

            <p
                style="
                text-align:center;
                margin-bottom:30px;
                color:#bbb;
                font-size:14px;
            ">
                Masukkan kode 6 digit yang telah dikirim ke email Anda
            </p>

            @if (session('success'))
                <div
                    style="
                    background:#1d1d1d;
                    border:1px solid #f9a825;
                    border-radius:8px;
                    padding:14px 16px;
                    margin-bottom:20px;
                    font-size:13px;
                    color:#f9a825;
                    line-height:1.6;
                ">
                    📧 {{ session('success') }} Silahkan
                    periksa folder <strong>Spam</strong> atau klik
                    <strong>Kirim Ulang OTP</strong> di bawah jika belum menerima.
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="
                    border:1px solid #c62828;
                    border-radius:8px;
                    padding:12px 16px;
                    margin-bottom:20px;
                ">
                    @foreach ($errors->all() as $error)
                        <p style="color:#ff6b6b;font-size:13px;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/media-login/verify">
                @csrf

                <input type="text" name="otp" placeholder="000000" maxlength="6" inputmode="numeric"
                    autocomplete="one-time-code" required autofocus
                    style="
                    width:100%;
                    padding:16px;
                    border-radius:10px;
                    border:1px solid #333;
                    background:#1a1a1a;
                    color:white;
                    font-size:28px;
                    letter-spacing:8px;
                    text-align:center;
                    margin-bottom:20px;
                "
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')">

                <button type="submit"
                    style="
                    width:100%;
                    padding:14px;
                    background:#00e5ff;
                    color:black;
                    border:none;
                    border-radius:8px;
                    font-weight:bold;
                    font-size:16px;
                    cursor:pointer;
                ">
                    Verifikasi
                </button>
            </form>

            <form method="POST" action="/media-login/resend-otp" style="margin-top:15px;">
                @csrf
                <button type="submit"
                    style="
                    width:100%;
                    padding:12px;
                    background:#333;
                    color:white;
                    border:1px solid #555;
                    border-radius:8px;
                    font-size:14px;
                    cursor:pointer;
                ">
                    🔄 Kirim Ulang OTP
                </button>
            </form>

            <p
                style="
                text-align:center;
                margin-top:15px;
                font-size:13px;
                color:#888;
            ">
                <a href="/media-login" style="color:#00e5ff;">Gunakan email lain</a>
            </p>

        </div>

    </div>
@endsection
