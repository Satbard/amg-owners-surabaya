@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Hasil Scan</h1>

        <a href="/admin/scan"
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

    {{-- Member Info Card --}}
    <div class="card" style="margin-bottom:20px;">

        <div
            style="
            display:flex;
            align-items:center;
            gap:20px;
            flex-wrap:wrap;
        ">

            <div
                style="
                background:#1d1d1d;
                padding:20px;
                border-radius:12px;
                text-align:center;
                min-width:200px;
            ">

                <p style="color:#888;font-size:13px;margin-bottom:5px;">
                    Nomor Member
                </p>

                <h2 style="color:#00e5ff;letter-spacing:3px;">
                    {{ $member->member_number }}
                </h2>

            </div>

            <div>
                <h2>{{ $member->full_name }}</h2>
                <p style="color:#aaa;">
                    {{ $member->nickname }}
                </p>
                <p style="color:#888;font-size:14px;margin-top:5px;">
                    {{ $member->phone }}
                    @if ($member->vehicle_model)
                        &nbsp;·&nbsp; {{ $member->vehicle_model }}
                    @endif
                </p>
            </div>

        </div>

    </div>

    {{-- Select Event --}}
    <div class="card">

        <h3 style="margin-bottom:15px;">
            Pilih Acara untuk Menandai Kehadiran
        </h3>

        @if ($activeEvents->count())
            <div
                style="
                display:grid;
                grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
                gap:15px;
            ">

                @foreach ($activeEvents as $event)
                    @php
                        $alreadyAttended = \App\Models\EventAttendance::where([
                            'event_id' => $event->id,
                            'registration_id' => $member->id,
                            'status' => 'hadir',
                        ])->exists();
                    @endphp

                    <div
                        style="
                        background:#1d1d1d;
                        border:1px solid {{ $alreadyAttended ? '#2e7d32' : '#333' }};
                        border-radius:10px;
                        padding:16px;
                    ">

                        <h4 style="margin-bottom:5px;">
                            {{ $event->title }}
                        </h4>

                        <p style="color:#aaa;font-size:13px;margin-bottom:10px;">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y H:i') }}
                        </p>

                        @if ($alreadyAttended)
                            <span
                                style="
                                background:#2e7d32;
                                padding:6px 12px;
                                border-radius:20px;
                                font-size:13px;
                            ">
                                ✅ Sudah Hadir
                            </span>
                        @else
                            <form method="POST" action="/admin/scan/confirm">

                                @csrf

                                <input type="hidden" name="registration_id" value="{{ $member->id }}">

                                <input type="hidden" name="event_id" value="{{ $event->id }}">

                                <button type="submit"
                                    style="
                                    padding:8px 16px;
                                    background:#00e5ff;
                                    color:black;
                                    border:none;
                                    border-radius:6px;
                                    font-weight:bold;
                                    cursor:pointer;
                                ">
                                    Tandai Hadir
                                </button>

                            </form>
                        @endif

                    </div>
                @endforeach

            </div>
        @else
            <p style="text-align:center;padding:40px;color:#aaa;">
                Tidak ada acara aktif (upcoming / ongoing) saat ini.
            </p>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus doesn't apply here since user needs to click
        });
    </script>

@endsection
