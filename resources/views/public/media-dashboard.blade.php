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

            @if (session('error'))
                <div
                    style="
                    background:#c62828;
                    padding:12px 16px;
                    border-radius:8px;
                    margin-bottom:20px;
                    font-size:14px;
                ">
                    {{ session('error') }}
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

            @if ($registration->barcode_token)
                {{-- Resend ID Card --}}
                <div class="card" style="text-align:center;">

                    <h2 style="color:#00e5ff;margin-bottom:15px;">
                        Kirim Ulang ID Card
                    </h2>

                    <p style="color:#aaa;font-size:14px;margin-bottom:15px;">
                        Jika Anda tidak menerima email ID Card, klik tombol di bawah untuk mengirim ulang.
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
                            📧 Kirim Ulang ID Card ke Email
                        </button>
                    </form>

                </div>
            @endif

        </div>

    </div>
@endsection
