@extends('layouts.app')

@section('content')
    <div
        style="
        min-height:85vh;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        text-align:center;
        padding:40px;
        position:relative;
        overflow:hidden;

        @if ($content->background) background-image:
                linear-gradient(
                    rgba(0,0,0,.55),
                    rgba(0,0,0,.55)
                ),
                url('{{ asset('storage/' . $content->background) }}');

            background-size:cover;

            background-position:center;

            background-repeat:no-repeat;

            background-attachment:fixed;

        @else

            background:#111; @endif
    ">

        <div style="
            max-width:900px;
            z-index:2;
        ">

            <h1 class="homepage-title"
                style="
                margin-bottom:25px;
                color:white;
                text-shadow:
                    0 3px 15px rgba(0,0,0,.6);
            ">
                {{ $content->title }}
            </h1>

            <p
                style="
                font-size:16px;
                line-height:1.8;
                color:#f5f5f5;
                margin-bottom:45px;
                text-shadow:
                    0 2px 10px rgba(0,0,0,.6);
            ">
                {{ $content->description }}
            </p>

            <a href="/register" class="btn-primary"
                style="
                font-size:18px;
                padding:16px 36px;
            ">
                {{ $content->button_text }}
            </a>

        </div>

    </div>

    <style>
        @media (max-width:768px) {

            .homepage-title {

                font-size: 38px !important;

                line-height: 1.2;

            }

        }
    </style>

    {{-- Barcode Success Modal --}}
    @if (session('barcode_sent'))
        <div id="barcodeModal"
            style="
            position:fixed;
            top:0;left:0;right:0;bottom:0;
            background:rgba(0,0,0,0.7);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:9999;
            padding:20px;
        ">
            <div
                style="
                max-width:480px;
                width:100%;
                background:#161616;
                border:1px solid #333;
                border-radius:16px;
                padding:35px;
                text-align:center;
            ">
                <div style="font-size:60px;margin-bottom:15px;">🎉</div>

                <h2 style="color:#00e5ff;margin-bottom:15px;">
                    Pendaftaran Media Berhasil!
                </h2>

                <p style="color:#ccc;font-size:15px;line-height:1.7;margin-bottom:5px;">
                    Barcode unik media Anda telah dikirim ke
                </p>

                <p style="color:#00e5ff;font-weight:bold;font-size:16px;margin-bottom:20px;">
                    {{ session('media_email') }}
                </p>

                <div
                    style="
                    background:#1d1d1d;
                    border-radius:8px;
                    padding:15px;
                    margin-bottom:20px;
                    font-size:14px;
                    color:#bbb;
                    line-height:1.6;
                ">
                    <p>📧 Jika tidak menerima email, silakan menuju ke halaman
                        <strong>Request Barcode Media</strong> untuk mengirim ulang barcode.
                    </p>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
                    <a href="/media-login"
                        style="
                        padding:12px 24px;
                        background:#00e5ff;
                        color:black;
                        border-radius:8px;
                        text-decoration:none;
                        font-weight:bold;
                    ">
                        Media Login
                    </a>

                    <button onclick="document.getElementById('barcodeModal').style.display='none'"
                        style="
                        padding:12px 24px;
                        background:#555;
                        color:white;
                        border:none;
                        border-radius:8px;
                        cursor:pointer;
                        font-weight:bold;
                    ">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
