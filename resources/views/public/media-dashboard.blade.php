@extends('layouts.app')

@section('content')
    <div style="
        min-height:100vh;
        padding:40px 20px;
        background:#0a0a0a;
    ">

        <div style="max-width:800px;margin:auto;">

            <div
                style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:30px;
            ">

                <h1 style="color:#00e5ff;font-size:28px;margin:0;">
                    Dashboard Media
                </h1>

                <form method="POST" action="/media-logout">
                    @csrf
                    <button type="submit"
                        style="
                        padding:10px 20px;
                        background:#c62828;
                        color:white;
                        border:none;
                        border-radius:8px;
                        cursor:pointer;
                        font-weight:bold;
                    ">
                        Logout
                    </button>
                </form>

            </div>

            @if (session('success'))
                <div
                    style="
                    background:#2e7d32;
                    padding:12px 16px;
                    border-radius:8px;
                    margin-bottom:20px;
                    font-size:14px;
                ">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Status Card --}}
            <div class="card" style="margin-bottom:20px;">

                <h2 style="color:#00e5ff;margin-bottom:20px;">
                    Status Pendaftaran
                </h2>

                <div style="display:grid;gap:15px;">

                    <p><strong>Nama Lengkap:</strong><br>
                        {{ $registration->full_name }}
                    </p>

                    <p><strong>Nama Media:</strong><br>
                        {{ $registration->media_name }}
                    </p>

                    <p><strong>Email:</strong><br>
                        {{ $registration->email }}
                    </p>

                    <p><strong>Kategori Lomba:</strong><br>
                        {{ $registration->competition_category }}
                    </p>

                    <p><strong>Status:</strong><br>
                        @if ($registration->status == 'Approved')
                            <span
                                style="
                                display:inline-block;
                                background:#2e7d32;
                                padding:8px 16px;
                                border-radius:20px;
                                font-weight:bold;
                            ">✅
                                Approved</span>
                        @elseif($registration->status == 'Rejected')
                            <span
                                style="
                                display:inline-block;
                                background:#c62828;
                                padding:8px 16px;
                                border-radius:20px;
                                font-weight:bold;
                            ">❌
                                Rejected</span>
                        @else
                            <span
                                style="
                                display:inline-block;
                                background:#f9a825;
                                color:black;
                                padding:8px 16px;
                                border-radius:20px;
                                font-weight:bold;
                            ">⏳
                                Pending</span>
                        @endif
                    </p>

                </div>

            </div>

            {{-- Barcode Card --}}
            @if ($registration->barcode_token)
                <div class="card" style="margin-bottom:20px;text-align:center;">

                    <h2 style="color:#00e5ff;margin-bottom:15px;">
                        Barcode Media {{ $registration->media_name }}
                    </h2>

                    @if ($barcodeSrc)
                        <div
                            style="
                            background:white;
                            border-radius:8px;
                            padding:20px;
                            display:inline-block;
                        ">
                            <img src="{{ $barcodeSrc }}" alt="Barcode {{ $registration->barcode_token }}"
                                style="max-width:280px;width:100%;height:auto;display:block;">
                        </div>
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
                            </p>
                        </div>
                    @endif

                    <p
                        style="
                        margin-top:10px;
                        font-size:18px;
                        letter-spacing:3px;
                        color:#00e5ff;
                        font-weight:bold;
                    ">
                        {{ $registration->barcode_token }}
                    </p>

                    @if ($registration->status == 'Approved')
                        <p style="color:#4caf50;font-size:13px;margin-top:5px;">
                            ✅ Barcode aktif — dapat digunakan untuk absensi
                        </p>
                    @else
                        <p style="color:#f9a825;font-size:13px;margin-top:5px;">
                            ⏳ Barcode akan aktif setelah pendaftaran disetujui admin
                        </p>
                    @endif

                </div>

                {{-- Resend Barcode --}}
                <div class="card" style="text-align:center;">

                    <h2 style="color:#00e5ff;margin-bottom:15px;">
                        Kirim Ulang Barcode
                    </h2>

                    <p style="color:#aaa;font-size:14px;margin-bottom:15px;">
                        Jika Anda tidak menerima email barcode, klik tombol di bawah untuk mengirim ulang.
                    </p>

                    <form method="POST" action="/media-dashboard/resend-barcode">
                        @csrf
                        <button type="submit"
                            style="
                            padding:12px 24px;
                            background:#00e5ff;
                            color:black;
                            border:none;
                            border-radius:8px;
                            font-weight:bold;
                            cursor:pointer;
                            font-size:15px;
                        ">
                            📧 Kirim Ulang Barcode ke Email
                        </button>
                    </form>

                </div>
            @endif

        </div>

    </div>
@endsection
