@extends('layouts.admin')

@section('content')
    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Hasil Scan Media</h1>

        <a href="/admin/scan-media"
            style="
            padding:10px 16px;
            background:#333;
            color:white;
            border-radius:8px;
            text-decoration:none;
        ">
            ← Scan Lagi
        </a>

    </div>

    @php
        // Generate barcode inline for display
        $barcodeSrc = null;
        try {
            if (function_exists('imagecreate') && $media->barcode_token) {
                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                $barcodeData = $generator->getBarcode($media->barcode_token, $generator::TYPE_CODE_128, 2, 50);
                $barcodeSrc = 'data:image/png;base64,' . base64_encode($barcodeData);
            }
        } catch (\Exception $e) {
            $barcodeSrc = null;
        }
    @endphp

    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
    ">

        <div class="card">

            <h2 style="color:#00e5ff;margin-bottom:20px;">
                Informasi Media
            </h2>

            <p><strong>Nama Lengkap:</strong><br>
                {{ $media->full_name }}
            </p>

            <br>

            <p><strong>Nama Media:</strong><br>
                {{ $media->media_name }}
            </p>

            <br>

            <p><strong>Kategori Lomba:</strong><br>
                {{ $media->competition_category }}
            </p>

            <br>

            <p><strong>Equipment:</strong><br>
                {{ $media->equipment_used }}
            </p>

            <br>

            <p><strong>Status:</strong><br>
                <span
                    style="
                    display:inline-block;
                    background:#2e7d32;
                    padding:6px 12px;
                    border-radius:20px;
                    font-weight:bold;
                ">
                    ✅ Approved
                </span>
            </p>

        </div>

        <div>

            <div class="card" style="text-align:center;margin-bottom:20px;">

                <h2 style="color:#00e5ff;margin-bottom:15px;">
                    Barcode Media {{ $media->media_name }}
                </h2>

                @if ($barcodeSrc)
                    <div
                        style="
                        background:white;
                        border-radius:8px;
                        padding:15px;
                        display:inline-block;
                    ">
                        <img src="{{ $barcodeSrc }}" alt="Barcode"
                            style="max-width:240px;width:100%;height:auto;display:block;">
                    </div>

                    <p
                        style="
                        margin-top:8px;
                        font-size:16px;
                        letter-spacing:3px;
                        color:#00e5ff;
                        font-weight:bold;
                    ">
                        {{ $media->barcode_token }}
                    </p>
                @endif

            </div>

            <div class="card" style="text-align:center;">

                <h2 style="color:#00e5ff;margin-bottom:15px;">
                    Tandai Kehadiran
                </h2>

                <p style="color:#aaa;font-size:14px;margin-bottom:15px;">
                    Konfirmasi bahwa <strong>{{ $media->full_name }}</strong>
                    telah hadir sebagai media.
                </p>

                <form method="POST" action="/admin/scan-media/confirm">
                    @csrf
                    <input type="hidden" name="media_registration_id" value="{{ $media->id }}">

                    <button type="submit"
                        style="
                        padding:14px 28px;
                        background:#2e7d32;
                        color:white;
                        border:none;
                        border-radius:8px;
                        font-weight:bold;
                        cursor:pointer;
                        font-size:16px;
                        width:100%;
                    ">
                        ✅ Tandai Hadir
                    </button>
                </form>

            </div>

        </div>

    </div>

    <style>
        @media(max-width:768px) {
            div[style*="grid-template-columns:1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
