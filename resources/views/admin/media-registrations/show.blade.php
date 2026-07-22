@extends('layouts.admin')

@section('content')
    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Detail Pendaftaran Media</h1>

        <a href="/admin/media-registrations"
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
                Personal Information
            </h2>

            <p><strong>Nama Lengkap:</strong><br>
                {{ $mediaRegistration->full_name }}
            </p>

            <br>

            <p><strong>Nama Media:</strong><br>
                {{ $mediaRegistration->media_name }}
            </p>

            <br>

            <p><strong>Posisi:</strong><br>
                @if (is_array($mediaRegistration->position))
                    @foreach ($mediaRegistration->position as $pos)
                        <span
                            style="
                            display:inline-block;
                            padding:4px 10px;
                            margin:2px;
                            background:#333;
                            border-radius:6px;
                            font-size:13px;
                        ">{{ $pos }}</span>
                    @endforeach
                @else
                    {{ $mediaRegistration->position }}
                @endif
            </p>

            <br>

            <p><strong>No HP / WhatsApp:</strong><br>
                {{ $mediaRegistration->phone }}
            </p>

            <br>

            <p><strong>Email:</strong><br>
                {{ $mediaRegistration->email ?: '-' }}
            </p>

        </div>

        <div>

            <div class="card" style="margin-bottom:20px;">

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Media Information
                </h2>

                <p><strong>Akun Media Sosial:</strong><br>
                    {{ $mediaRegistration->social_media ?: '-' }}
                </p>

                <br>

                <p><strong>Jumlah Followers / Subscribers:</strong><br>
                    {{ $mediaRegistration->followers ?: '-' }}
                </p>

                <br>

                <p><strong>Jenis Media:</strong><br>
                    {{ $mediaRegistration->media_type }}
                </p>

            </div>

            <div class="card" style="margin-bottom:20px;">

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Competition Registration
                </h2>

                <p><strong>Kategori Lomba:</strong><br>
                    {{ $mediaRegistration->competition_category }}
                </p>

                <br>

                <p><strong>Equipment Digunakan:</strong><br>
                    {{ $mediaRegistration->equipment_used }}
                </p>

            </div>

            <div class="card" style="margin-bottom:20px;">

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:15px;
                ">
                    Terms & Agreement
                </h2>

                @if ($mediaRegistration->terms_agreed)
                    <span style="color:#4caf50;font-weight:bold;">
                        ✅ All terms agreed
                    </span>
                @else
                    <span style="color:#888;">
                        ❌ Terms not agreed
                    </span>
                @endif

            </div>

            <div class="card">

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:15px;
                ">
                    Status
                </h2>

                @if ($mediaRegistration->status == 'Approved')
                    <span
                        style="
                        background:#2e7d32;
                        padding:10px 16px;
                        border-radius:20px;
                    ">
                        ✅ Approved
                    </span>
                @elseif($mediaRegistration->status == 'Rejected')
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

                <br><br>

                <a href="/admin/media-registrations/{{ $mediaRegistration->id }}/edit"
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
