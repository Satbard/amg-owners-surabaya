@extends('layouts.admin')

@section('content')
    <h1 style="
        margin-bottom:10px;
    ">
        Dashboard Admin
    </h1>

    <p style="
        color:#aaa;
        margin-bottom:30px;
    ">
        Selamat datang, {{ auth()->user()->name }}
    </p>

    {{-- Registration Stats --}}
    <h2 style="margin-bottom:15px;color:#ccc;">
        📋 Pendaftaran
    </h2>

    <div
        style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:20px;
        margin-bottom:30px;
    ">

        <div class="card">

            <h3 style="
                color:#00e5ff;
                margin-bottom:10px;
            ">
                Total Pendaftaran
            </h3>

            <h1>
                {{ $total }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#ffc107;
                margin-bottom:10px;
            ">
                Pending
            </h3>

            <h1>
                {{ $pending }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#4caf50;
                margin-bottom:10px;
            ">
                Approved
            </h3>

            <h1>
                {{ $approved }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#ff5252;
                margin-bottom:10px;
            ">
                Rejected
            </h3>

            <h1>
                {{ $rejected }}
            </h1>

        </div>

    </div>

    {{-- Event Stats --}}
    <h2 style="margin-bottom:15px;color:#ccc;">
        📅 Acara
    </h2>

    <div
        style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:20px;
        margin-bottom:30px;
    ">

        <div class="card">

            <h3 style="
                color:#00e5ff;
                margin-bottom:10px;
            ">
                Total Acara
            </h3>

            <h1>
                {{ $totalEvents }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#4caf50;
                margin-bottom:10px;
            ">
                Aktif
            </h3>

            <h1>
                {{ $upcomingEvents }}
            </h1>

        </div>

    </div>

    {{-- Latest Events --}}
    @if ($latestEvents->count())
        <div class="card" style="margin-bottom:30px;">

            <h2 style="margin-bottom:20px;">
                Acara Terbaru
            </h2>

            <div style="overflow-x:auto;">

                <table
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>
                        <tr style="background:#1d1d1d;">
                            <th style="padding:10px;text-align:left;">Acara</th>
                            <th style="padding:10px;text-align:left;">Tanggal</th>
                            <th style="padding:10px;text-align:left;">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($latestEvents as $event)
                            <tr style="border-top:1px solid #222;">
                                <td style="padding:10px;">
                                    <a href="/admin/events/{{ $event->id }}" style="color:#00e5ff;text-decoration:none;">
                                        {{ $event->title }}
                                    </a>
                                </td>
                                <td style="padding:10px;color:#aaa;">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y H:i') }}
                                </td>
                                <td style="padding:10px;">
                                    @if ($event->status == 'upcoming')
                                        <span
                                            style="background:#1565c0;padding:4px 10px;border-radius:20px;font-size:13px;">
                                            Upcoming
                                        </span>
                                    @elseif($event->status == 'ongoing')
                                        <span
                                            style="background:#2e7d32;padding:4px 10px;border-radius:20px;font-size:13px;">
                                            Ongoing
                                        </span>
                                    @elseif($event->status == 'completed')
                                        <span style="background:#555;padding:4px 10px;border-radius:20px;font-size:13px;">
                                            Completed
                                        </span>
                                    @else
                                        <span
                                            style="background:#c62828;padding:4px 10px;border-radius:20px;font-size:13px;">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    @endif

    {{-- Quick Access --}}
    <div class="card">

        <h2 style="
            margin-bottom:20px;
        ">
            Quick Access
        </h2>

        <div style="
            display:flex;
            flex-wrap:wrap;
            gap:15px;
        ">

            <a href="/admin/registrations"
                style="
                padding:12px 20px;
                background:#00e5ff;
                color:black;
                border-radius:8px;
                font-weight:bold;
                text-decoration:none;
            ">
                Data Pendaftaran
            </a>

            <a href="/admin/events"
                style="
                padding:12px 20px;
                background:#1565c0;
                color:white;
                border-radius:8px;
                font-weight:bold;
                text-decoration:none;
            ">
                Kelola Acara
            </a>

            <a href="/admin/scan"
                style="
                padding:12px 20px;
                background:#2e7d32;
                color:white;
                border-radius:8px;
                font-weight:bold;
                text-decoration:none;
            ">
                Scan Barcode
            </a>

            <a href="/admin/registrations-trash"
                style="
                padding:12px 20px;
                background:#444;
                color:white;
                border-radius:8px;
                text-decoration:none;
            ">
                Trash Bin
            </a>

            <a href="/admin/activity-logs"
                style="
                padding:12px 20px;
                background:#444;
                color:white;
                border-radius:8px;
                text-decoration:none;
            ">
                Activity Logs
            </a>

            <a href="/admin/content"
                style="
                padding:12px 20px;
                background:#444;
                color:white;
                border-radius:8px;
                text-decoration:none;
            ">
                Homepage CMS
            </a>

        </div>

    </div>
@endsection
