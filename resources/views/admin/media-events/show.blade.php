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

            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 12px;color:#aaa;width:30%;vertical-align:top;">Tanggal</td>
                    <td style="padding:8px 12px;">{{ $mediaEvent->event_date->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 12px;color:#aaa;vertical-align:top;">Lokasi</td>
                    <td style="padding:8px 12px;">{{ $mediaEvent->location ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 12px;color:#aaa;vertical-align:top;">Deskripsi</td>
                    <td style="padding:8px 12px;">{{ $mediaEvent->description ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 12px;color:#aaa;vertical-align:top;">Status</td>
                    <td style="padding:8px 12px;">
                        @if ($mediaEvent->status == 'upcoming')
                            <span
                                style="display:inline-block;background:#1976d2;padding:4px 12px;border-radius:12px;font-size:13px;">Upcoming</span>
                        @elseif($mediaEvent->status == 'ongoing')
                            <span
                                style="display:inline-block;background:#f9a825;color:black;padding:4px 12px;border-radius:12px;font-size:13px;">Ongoing</span>
                        @elseif($mediaEvent->status == 'completed')
                            <span
                                style="display:inline-block;background:#2e7d32;padding:4px 12px;border-radius:12px;font-size:13px;">Completed</span>
                        @else
                            <span
                                style="display:inline-block;background:#c62828;padding:4px 12px;border-radius:12px;font-size:13px;">Cancelled</span>
                        @endif
                    </td>
                </tr>
            </table>

            <br>

            <a href="/admin/media-events/{{ $mediaEvent->id }}/edit"
                style="display:inline-block;padding:12px 20px;background:#00e5ff;color:black;border-radius:8px;font-weight:bold;text-decoration:none;">
                Edit Acara
            </a>
        </div>

        <div class="card">
            <h2 style="color:#00e5ff;margin-bottom:20px;">
                Media Terdaftar
                <span style="color:#aaa;font-size:14px;font-weight:normal;">
                    ({{ $mediaEvent->attendances->where('status', 'hadir')->count() }}
                    /{{ $mediaEvent->attendances->count() }} hadir)
                </span>
            </h2>

            @if ($mediaEvent->attendances->count())
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1d1d1d;border-bottom:2px solid #333;">
                                <th style="padding:10px 12px;text-align:left;font-size:13px;color:#aaa;">Nama</th>
                                <th style="padding:10px 12px;text-align:left;font-size:13px;color:#aaa;">Media</th>
                                <th style="padding:10px 12px;text-align:center;font-size:13px;color:#aaa;">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mediaEvent->attendances as $att)
                                <tr style="border-bottom:1px solid #222;">
                                    <td style="padding:10px 12px;">{{ $att->mediaRegistration->full_name }}</td>
                                    <td style="padding:10px 12px;color:#00e5ff;">{{ $att->mediaRegistration->media_name }}
                                    </td>
                                    <td style="padding:10px 12px;text-align:center;">
                                        @if ($att->status == 'hadir')
                                            <span
                                                style="display:inline-block;background:#2e7d32;padding:4px 12px;border-radius:10px;font-size:12px;font-weight:bold;">
                                                ✅ Hadir
                                            </span>
                                        @else
                                            <span
                                                style="display:inline-block;background:#444;padding:4px 12px;border-radius:10px;font-size:12px;">
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
