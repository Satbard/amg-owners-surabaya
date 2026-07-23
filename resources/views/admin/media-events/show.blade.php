@extends('layouts.admin')

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h1>{{ $mediaEvent->title }}</h1>
        <a href="/admin/media-events"
            style="padding:10px 16px;background:#333;color:white;border-radius:8px;text-decoration:none;">
            ← Kembali
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        <div class="card">
            <h2 style="color:#00e5ff;margin-bottom:20px;">Informasi Acara</h2>

            <p><strong>Tanggal:</strong><br>
                {{ $mediaEvent->event_date->format('d M Y H:i') }}
            </p>
            <br>
            <p><strong>Lokasi:</strong><br>
                {{ $mediaEvent->location ?: '-' }}
            </p>
            <br>
            <p><strong>Deskripsi:</strong><br>
                {{ $mediaEvent->description ?: '-' }}
            </p>
            <br>
            <p><strong>Status:</strong><br>
                @if ($mediaEvent->status == 'upcoming')
                    <span style="background:#1976d2;padding:6px 12px;border-radius:12px;">Upcoming</span>
                @elseif($mediaEvent->status == 'ongoing')
                    <span style="background:#f9a825;color:black;padding:6px 12px;border-radius:12px;">Ongoing</span>
                @elseif($mediaEvent->status == 'completed')
                    <span style="background:#2e7d32;padding:6px 12px;border-radius:12px;">Completed</span>
                @else
                    <span style="background:#c62828;padding:6px 12px;border-radius:12px;">Cancelled</span>
                @endif
            </p>
            <br>
            <a href="/admin/media-events/{{ $mediaEvent->id }}/edit"
                style="display:inline-block;padding:12px 20px;background:#00e5ff;color:black;border-radius:8px;font-weight:bold;text-decoration:none;">
                Edit Acara
            </a>
        </div>

        <div class="card">
            <h2 style="color:#00e5ff;margin-bottom:20px;">
                Media Terdaftar
                <span style="color:#aaa;font-size:14px;">
                    ({{ $mediaEvent->attendances->where('status', 'hadir')->count() }}
                    /{{ $mediaEvent->attendances->count() }} hadir)
                </span>
            </h2>

            @if ($mediaEvent->attendances->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1d1d1d;">
                                <th style="padding:8px;">Nama</th>
                                <th style="padding:8px;">Media</th>
                                <th style="padding:8px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mediaEvent->attendances as $att)
                                <tr style="border-top:1px solid #222;">
                                    <td style="padding:8px;">{{ $att->mediaRegistration->full_name }}</td>
                                    <td style="padding:8px;">{{ $att->mediaRegistration->media_name }}</td>
                                    <td style="padding:8px;">
                                        @if ($att->status == 'hadir')
                                            <span
                                                style="background:#2e7d32;padding:4px 8px;border-radius:10px;font-size:12px;white-space:nowrap;">
                                                ✅ Hadir
                                            </span>
                                        @else
                                            <span
                                                style="background:#555;padding:4px 8px;border-radius:10px;font-size:12px;white-space:nowrap;">
                                                ❌ Tidak Hadir
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="color:#aaa;text-align:center;padding:30px;">Belum ada media terdaftar.</p>
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
