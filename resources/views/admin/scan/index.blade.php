@extends('layouts.admin')

@section('content')

    @if ($preselectedEventId)
        @php
            $preselectedEvent = \App\Models\Event::find($preselectedEventId);
        @endphp
        @if ($preselectedEvent)
            <div
                style="
                background:#1565c0;
                padding:12px 16px;
                border-radius:8px;
                margin-bottom:20px;
                display:flex;
                justify-content:space-between;
                align-items:center;
            ">
                <span>
                    📌 Mode scan untuk acara:
                    <strong>{{ $preselectedEvent->title }}</strong>
                </span>
                <a href="/admin/events/{{ $preselectedEvent->id }}"
                    style="color:white;text-decoration:underline;font-size:14px;">
                    Kembali ke acara →
                </a>
            </div>
        @endif
    @endif

    <h1 style="margin-bottom:10px;">Scan Barcode</h1>

    <p style="color:#aaa;margin-bottom:30px;">
        Scan barcode member untuk menandai kehadiran.
        @if (!$preselectedEventId)
            Pilih event terlebih dahulu atau scan dulu lalu pilih event.
        @else
            Scan akan langsung menandai hadir untuk acara yang dipilih.
        @endif
    </p>

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

    <div class="card" style="max-width:600px;margin-bottom:30px;">

        <h3 style="margin-bottom:15px;">
            Scan Barcode Member
            @if ($preselectedEventId)
                <span style="color:#aaa;font-weight:normal;font-size:14px;">
                    — scan untuk menandai hadir
                </span>
            @endif
        </h3>

        <form method="POST" action="/admin/scan/lookup" id="scanForm">

            @csrf

            @if ($preselectedEventId)
                <input type="hidden" name="event_id" value="{{ $preselectedEventId }}">
            @endif

            <div style="display:flex;gap:10px;">

                <input type="text" name="member_number" id="barcodeInput"
                    placeholder="Scan atau ketik nomor member di sini..." autofocus autocomplete="off"
                    style="
                    flex:1;
                    padding:14px;
                    background:#1d1d1d;
                    border:2px solid #00e5ff;
                    border-radius:8px;
                    color:white;
                    font-size:18px;
                    letter-spacing:2px;
                    text-transform:uppercase;
                ">

                <button type="submit"
                    style="
                    padding:14px 24px;
                    background:#00e5ff;
                    color:black;
                    border:none;
                    border-radius:8px;
                    font-weight:bold;
                    cursor:pointer;
                ">
                    Cari
                </button>

            </div>

        </form>

        <p style="color:#888;font-size:13px;margin-top:10px;">
            💡 Barcode scanner akan otomatis mengirim setelah scan.
            Anda juga bisa mengetik nomor member secara manual.
        </p>

    </div>

    {{-- Quick Access: Active Events --}}
    @if (!$preselectedEventId && $events->count())
        <div class="card">

            <h3 style="margin-bottom:15px;">Acara Aktif</h3>

            <p style="color:#aaa;font-size:14px;margin-bottom:15px;">
                Atau buka halaman scan untuk acara tertentu:
            </p>

            <div
                style="
                display:flex;
                flex-wrap:wrap;
                gap:10px;
            ">

                @foreach ($events as $event)
                    <a href="/admin/scan?event_id={{ $event->id }}"
                        style="
                        padding:10px 16px;
                        background:#1d1d1d;
                        border:1px solid #333;
                        border-radius:8px;
                        color:white;
                        text-decoration:none;
                    ">
                        {{ $event->title }}
                        <span style="color:#aaa;font-size:13px;">
                            ({{ \Carbon\Carbon::parse($event->event_date)->format('d M') }})
                        </span>
                    </a>
                @endforeach

            </div>

        </div>
    @endif

    @if (!$preselectedEventId && !$events->count())
        <div class="card" style="text-align:center;padding:40px;">
            <h3 style="color:#aaa;">Belum Ada Acara Aktif</h3>
            <p style="color:#666;margin-top:10px;">
                Buat acara terlebih dahulu untuk mulai scan barcode.
            </p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('barcodeInput');

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('scanForm').submit();
                }
            });

            // Keep focus for continuous scanning
            input.focus();
        });
    </script>

@endsection
