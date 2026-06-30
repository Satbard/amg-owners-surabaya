@extends('layouts.admin')

@section('content')
    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Detail Pendaftaran</h1>

        <a href="/admin/registrations"
            style="
            padding:10px 16px;
            background:#333;
            color:white;
            border-radius:8px;
            text-decoration:none;
        ">
            ← Kembali
        </a>

    </div>

    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
    ">

        <div class="card">

            <h2 style="
                color:#00e5ff;
                margin-bottom:20px;
            ">
                Data Diri
            </h2>

            @if ($registration->member_number)
                <p style="margin-bottom:15px;">
                    <strong>Nomor Member:</strong><br>
                    <span
                        style="
                        color:#00e5ff;
                        font-size:20px;
                        letter-spacing:2px;
                        font-weight:bold;
                    ">
                        {{ $registration->member_number }}
                    </span>
                </p>
            @endif

            <p><strong>Nama Lengkap:</strong><br>
                {{ $registration->full_name }}
            </p>

            <br>

            <p><strong>Nama Panggilan:</strong><br>
                {{ $registration->nickname }}
            </p>

            <br>

            <p><strong>Tempat Lahir:</strong><br>
                {{ $registration->birth_place }}
            </p>

            <br>

            <p><strong>Tanggal Lahir:</strong><br>
                {{ $registration->birth_date }}
            </p>

            <br>

            <p><strong>Alamat:</strong><br>
                {{ $registration->address }}
            </p>

            <br>

            <p><strong>No HP / WhatsApp:</strong><br>
                {{ $registration->phone }}
            </p>

            <br>

            <p><strong>Email:</strong><br>
                {{ $registration->email ?: '-' }}
            </p>

            <br>

            <p><strong>Instagram:</strong><br>
                {{ $registration->instagram ?: '-' }}
            </p>

            <br>

            <p><strong>Pekerjaan:</strong><br>
                {{ $registration->occupation }}
            </p>

            <br>

            <p><strong>Ukuran Kemeja / Kaos:</strong><br>
                {{ $registration->shirt_size }}
            </p>

        </div>

        <div>

            <div class="card" style="margin-bottom:20px;">

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Data Kendaraan
                </h2>

                <p><strong>Model Kendaraan:</strong><br>
                    {{ $registration->vehicle_model }}
                </p>

                <br>

                <p><strong>Tahun:</strong><br>
                    {{ $registration->vehicle_year }}
                </p>

                <br>

                <p><strong>No. Rangka / VIN:</strong><br>
                    {{ $registration->vehicle_color }}
                </p>

                <br>

                <p><strong>Nomor Polisi:</strong><br>
                    {{ $registration->license_plate }}
                </p>

                <br><br>

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:15px;
                ">
                    Status Keanggotaan
                </h2>

                @if ($registration->membership_status == 'Approved')
                    <span
                        style="
                        background:#2e7d32;
                        padding:10px 16px;
                        border-radius:20px;
                    ">
                        ✅ Approved
                    </span>
                @elseif($registration->membership_status == 'Rejected')
                    <span
                        style="
                        background:#c62828;
                        padding:10px 16px;
                        border-radius:20px;
                    ">
                        ❌ Rejected
                    </span>
                @else
                    <span
                        style="
                        background:#f9a825;
                        color:black;
                        padding:10px 16px;
                        border-radius:20px;
                    ">
                        ⏳ Pending
                    </span>
                @endif

                <br><br><br>

                <a href="/admin/registrations/{{ $registration->id }}/edit"
                    style="
                    display:inline-block;
                    padding:12px 20px;
                    background:#00e5ff;
                    color:black;
                    border-radius:8px;
                    font-weight:bold;
                    text-decoration:none;
                ">
                    Edit Data
                </a>

            </div>

            {{-- Barcode Card --}}
            @if ($registration->member_number)
                <div class="card" style="text-align:center;">

                    <h2
                        style="
                        color:#00e5ff;
                        margin-bottom:15px;
                    ">
                        Barcode Member
                    </h2>

                    @php
                        $barcodeSrc = null;

                        try {
                            if (function_exists('imagecreate')) {
                                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                $barcodeData = $generator->getBarcode(
                                    $registration->member_number,
                                    $generator::TYPE_CODE_128,
                                );
                                $barcodeSrc = 'data:image/png;base64,' . base64_encode($barcodeData);
                            }
                        } catch (\Exception $e) {
                            $barcodeSrc = null;
                        }
                    @endphp

                    @if ($barcodeSrc)
                        <img src="{{ $barcodeSrc }}" alt="Barcode {{ $registration->member_number }}"
                            style="
                            max-width:280px;
                            width:100%;
                            height:auto;
                        ">
                    @else
                        <div
                            style="
                            padding:30px 20px;
                            background:#1d1d1d;
                            border-radius:10px;
                            border:1px dashed #444;
                        ">
                            <span style="font-size:40px;display:block;margin-bottom:10px;">📱</span>
                            <p style="color:#888;font-size:13px;">
                                Barcode tidak dapat ditampilkan.
                                <br>Namun nomor member tetap bisa digunakan untuk scan manual.
                            </p>
                        </div>
                    @endif

                    <p
                        style="
                        margin-top:10px;
                        font-size:16px;
                        letter-spacing:3px;
                        color:#00e5ff;
                        font-weight:bold;
                    ">
                        {{ $registration->member_number }}
                    </p>

                    <p style="color:#888;font-size:13px;margin-top:5px;">
                        Tunjukkan barcode ini saat absensi acara
                    </p>

                </div>
            @endif

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
