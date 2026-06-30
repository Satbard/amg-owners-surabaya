@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Daftar Acara</h1>

        <a href="/admin/events/create"
            style="
            padding:10px 18px;
            background:#00e5ff;
            color:black;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
        ">
            + Buat Acara Baru
        </a>

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

    @if ($events->count())
        <div class="card">

            <div style="overflow-x:auto;">

                <table id="eventTable"
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>
                        <tr style="background:#1d1d1d;">
                            <th style="padding:12px;">Acara</th>
                            <th style="padding:12px;">Tanggal</th>
                            <th style="padding:12px;">Lokasi</th>
                            <th style="padding:12px;">Status</th>
                            <th style="padding:12px;">Hadir</th>
                            <th style="padding:12px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($events as $event)
                            <tr style="border-top:1px solid #222;">

                                <td style="padding:12px;">
                                    <strong>{{ $event->title }}</strong>
                                </td>

                                <td style="padding:12px;">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y H:i') }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $event->location ?: '-' }}
                                </td>

                                <td style="padding:12px;">

                                    @if ($event->status == 'upcoming')
                                        <span
                                            style="
                                            background:#1565c0;
                                            padding:4px 10px;
                                            border-radius:20px;
                                            font-size:13px;
                                        ">Upcoming</span>
                                    @elseif($event->status == 'ongoing')
                                        <span
                                            style="
                                            background:#2e7d32;
                                            padding:4px 10px;
                                            border-radius:20px;
                                            font-size:13px;
                                        ">Ongoing</span>
                                    @elseif($event->status == 'completed')
                                        <span
                                            style="
                                            background:#555;
                                            padding:4px 10px;
                                            border-radius:20px;
                                            font-size:13px;
                                        ">Completed</span>
                                    @else
                                        <span
                                            style="
                                            background:#c62828;
                                            padding:4px 10px;
                                            border-radius:20px;
                                            font-size:13px;
                                        ">Cancelled</span>
                                    @endif

                                </td>

                                <td style="padding:12px;">
                                    @php
                                        $total = $event->attendances->count();
                                        $hadir = $event->attendances->where('status', 'hadir')->count();
                                    @endphp
                                    {{ $hadir }} / {{ $total }}
                                </td>

                                <td style="padding:12px;">

                                    <div
                                        style="
                                        display:flex;
                                        gap:8px;
                                        flex-wrap:wrap;
                                    ">

                                        <a href="/admin/events/{{ $event->id }}"
                                            style="
                                            padding:6px 10px;
                                            background:#1976d2;
                                            color:white;
                                            border-radius:6px;
                                            text-decoration:none;
                                        ">
                                            Detail
                                        </a>

                                        <a href="/admin/events/{{ $event->id }}/edit"
                                            style="
                                            padding:6px 10px;
                                            background:#555;
                                            color:white;
                                            border-radius:6px;
                                            text-decoration:none;
                                        ">
                                            Edit
                                        </a>

                                        <form method="POST" action="/admin/events/{{ $event->id }}"
                                            style="display:inline;"
                                            onsubmit="return confirm('Hapus acara ini? Semua data absensi akan ikut terhapus.')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                style="
                                                padding:6px 10px;
                                                background:#c62828;
                                                color:white;
                                                border:none;
                                                border-radius:6px;
                                                cursor:pointer;
                                            ">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {

                    $('#eventTable').DataTable({

                        pageLength: 10,

                        order: [
                            [1, 'desc']
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

                            emptyTable: "Belum ada acara"

                        }

                    });

                });
            </script>
        @endpush
    @else
        <div class="card" style="
            text-align:center;
            padding:60px;
        ">

            <h3>📅 Belum Ada Acara</h3>

            <p>Buat acara pertama Anda untuk mulai mengelola absensi.</p>

            <br>

            <a href="/admin/events/create"
                style="
                display:inline-block;
                padding:12px 24px;
                background:#00e5ff;
                color:black;
                border-radius:8px;
                font-weight:bold;
                text-decoration:none;
            ">
                Buat Acara Baru
            </a>

        </div>
    @endif

@endsection
