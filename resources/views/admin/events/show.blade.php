@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <div>
            <h1>{{ $event->title }}</h1>
            <p style="color:#aaa;margin-top:5px;">
                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y H:i') }}
                @if ($event->location)
                    &nbsp;·&nbsp; {{ $event->location }}
                @endif
            </p>
        </div>

        <div style="display:flex;gap:10px;">

            <a href="/admin/events/{{ $event->id }}/edit"
                style="
                padding:10px 16px;
                background:#555;
                color:white;
                border-radius:8px;
                text-decoration:none;
            ">
                Edit
            </a>

            <a href="/admin/events"
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

    </div>

    @if (session('success'))
        <div
            style="
            background:#2e7d32;
            padding:12px 16px;
            border-radius:8px;
            margin-bottom:20px;
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
        ">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning'))
        <div
            style="
            background:#f9a825;
            color:black;
            padding:12px 16px;
            border-radius:8px;
            margin-bottom:20px;
        ">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Status Badge --}}
    <div style="margin-bottom:20px;">

        @if ($event->status == 'upcoming')
            <span
                style="
                background:#1565c0;
                padding:6px 14px;
                border-radius:20px;
            ">Upcoming</span>
        @elseif($event->status == 'ongoing')
            <span
                style="
                background:#2e7d32;
                padding:6px 14px;
                border-radius:20px;
            ">Ongoing</span>
        @elseif($event->status == 'completed')
            <span
                style="
                background:#555;
                padding:6px 14px;
                border-radius:20px;
            ">Completed</span>
        @else
            <span
                style="
                background:#c62828;
                padding:6px 14px;
                border-radius:20px;
            ">Cancelled</span>
        @endif

    </div>

    @if ($event->description)
        <div class="card" style="margin-bottom:20px;">
            <p>{{ $event->description }}</p>
        </div>
    @endif

    {{-- Scan & Export Section --}}
    <div class="card" style="margin-bottom:20px;">

        <div
            style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:10px;
        ">

            <div>
                <h3 style="margin-bottom:5px;">Scan Barcode</h3>
                <p style="color:#aaa;font-size:14px;">
                    Scan barcode member untuk menandai kehadiran
                </p>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">

                <a href="/admin/events/{{ $event->id }}/export-attendance"
                    style="
                    padding:10px 20px;
                    background:#2e7d32;
                    color:white;
                    border-radius:8px;
                    font-weight:bold;
                    text-decoration:none;
                ">
                    ⬇ Export Excel
                </a>

                <a href="/admin/scan?event_id={{ $event->id }}"
                    style="
                    padding:10px 20px;
                    background:#00e5ff;
                    color:black;
                    border-radius:8px;
                    font-weight:bold;
                    text-decoration:none;
                ">
                    Buka Halaman Scan
                </a>

            </div>

        </div>

    </div>

    {{-- Attendance List --}}
    <div class="card">

        <h2 style="margin-bottom:20px;">
            Daftar Absensi
            <span style="color:#aaa;font-weight:normal;font-size:16px;">
                ({{ $event->attendances->where('status', 'hadir')->count() }}
                /
                {{ $event->attendances->count() }} hadir)
            </span>
        </h2>

        @if ($event->attendances->count())
            <div style="overflow-x:auto;">

                <table id="attendanceTable"
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>
                        <tr style="background:#1d1d1d;">
                            <th style="padding:12px;">No. Member</th>
                            <th style="padding:12px;">Nama</th>
                            <th style="padding:12px;">No HP</th>
                            <th style="padding:12px;">Status</th>
                            <th style="padding:12px;">Scan Time</th>
                            <th style="padding:12px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($event->attendances as $attendance)
                            <tr style="border-top:1px solid #222;">

                                <td style="padding:12px;">
                                    {{ $attendance->registration->member_number ?? '-' }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $attendance->registration->full_name }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $attendance->registration->phone }}
                                </td>

                                <td style="padding:12px;">

                                    @if ($attendance->status == 'hadir')
                                        <span
                                            style="
                                            background:#2e7d32;
                                            padding:4px 10px;
                                            border-radius:20px;
                                            font-size:13px;
                                        ">
                                            ✅ Hadir
                                        </span>
                                    @else
                                        <span
                                            style="
                                            background:#555;
                                            padding:4px 10px;
                                            border-radius:20px;
                                            font-size:13px;
                                        ">
                                            ❌ Tidak Hadir
                                        </span>
                                    @endif

                                </td>

                                <td style="padding:12px;font-size:14px;color:#aaa;">
                                    {{ $attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->format('d M H:i') : '-' }}
                                </td>

                                <td style="padding:12px;">

                                    <form method="POST"
                                        action="/admin/events/{{ $event->id }}/attendance/{{ $attendance->id }}"
                                        style="display:inline;">

                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="status"
                                            value="{{ $attendance->status == 'hadir' ? 'tidak_hadir' : 'hadir' }}">

                                        <button type="submit"
                                            style="
                                            padding:6px 12px;
                                            background:{{ $attendance->status == 'hadir' ? '#555' : '#2e7d32' }};
                                            color:white;
                                            border:none;
                                            border-radius:6px;
                                            cursor:pointer;
                                        ">
                                            {{ $attendance->status == 'hadir' ? 'Tandai Tidak Hadir' : 'Tandai Hadir' }}
                                        </button>

                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
        @else
            <p style="text-align:center;padding:40px;color:#aaa;">
                Belum ada member yang terdaftar di acara ini.
            </p>
        @endif

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#attendanceTable').DataTable({

                    pageLength: 25,

                    order: [
                        [3, 'asc']
                    ],

                    language: {

                        search: "Cari:",

                        lengthMenu: "Tampilkan _MENU_ data",

                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                        paginate: {

                            previous: "←",

                            next: "→"

                        },

                        zeroRecords: "Data tidak ditemukan",

                        infoEmpty: "Belum ada data",

                        emptyTable: "Belum ada absensi"

                    }

                });

            });
        </script>
    @endpush

@endsection
