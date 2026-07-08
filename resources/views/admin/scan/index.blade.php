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

    {{-- Tab: Manual Input / Camera --}}
    <div style="
        display:flex;
        gap:10px;
        margin-bottom:20px;
    ">
        <button id="tabManualBtn" onclick="switchTab('manual')"
            style="
            padding:10px 20px;
            background:#00e5ff;
            color:black;
            border:none;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        ">
            ⌨️ Input Manual
        </button>

        <button id="tabCameraBtn" onclick="switchTab('camera')"
            style="
            padding:10px 20px;
            background:#333;
            color:white;
            border:none;
            border-radius:8px;
            font-weight:bold;
            cursor:pointer;
        ">
            📷 Scan dengan Kamera
        </button>
    </div>

    {{-- Panel: Manual Input --}}
    <div id="manualPanel" class="card" style="max-width:600px;margin-bottom:30px;">

        <h3 style="margin-bottom:15px;">
            Input Nomor Member Manual
        </h3>

        <form method="POST" action="/admin/scan/lookup" id="scanForm">

            @csrf

            @if ($preselectedEventId)
                <input type="hidden" name="event_id" value="{{ $preselectedEventId }}">
            @endif

            <div style="display:flex;gap:10px;">

                <input type="text" name="member_number" id="barcodeInput"
                    placeholder="Scan atau ketik barcode / nomor member" autofocus autocomplete="off"
                    style="
                    flex:1;
                    padding:14px;
                    background:#1d1d1d;
                    border:2px solid #555;
                    border-radius:8px;
                    color:white;
                    font-size:16px;
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
            💡 Barcode scanner USB akan otomatis mengirim setelah scan.
            Barcode sudah diamankan — tidak bisa digandakan secara manual.
        </p>

    </div>

    {{-- Panel: Camera Scanner --}}
    <div id="cameraPanel" class="card" style="max-width:600px;margin-bottom:30px;display:none;">

        <h3 style="margin-bottom:15px;">
            Scan dengan Kamera
        </h3>

        <div
            style="
            position:relative;
            background:#000;
            border-radius:8px;
            overflow:hidden;
            min-height:300px;
        ">

            <div id="scannerContainer" style="
                width:100%;
                min-height:300px;
            ">
            </div>

            <div id="scannerLoading"
                style="
                position:absolute;
                top:50%;
                left:50%;
                transform:translate(-50%,-50%);
                color:#888;
                text-align:center;
            ">
                <p style="font-size:40px;margin-bottom:10px;">📷</p>
                <p>Mengakses kamera...</p>
                <p style="font-size:13px;margin-top:5px;">
                    Pastikan browser memiliki izin akses kamera
                </p>
            </div>

        </div>

        <div id="scanResult" style="display:none;margin-top:15px;">

            <div
                style="
                background:#1d1d1d;
                padding:15px;
                border-radius:8px;
                border:2px solid #2e7d32;
            ">

                <p style="color:#4caf50;font-weight:bold;margin-bottom:8px;">
                    ✅ Barcode Terdeteksi
                </p>

                <p style="font-size:24px;letter-spacing:3px;color:#00e5ff;font-weight:bold;" id="detectedCode"></p>

                <form method="POST" action="/admin/scan/lookup" id="cameraScanForm" style="margin-top:15px;">

                    @csrf

                    @if ($preselectedEventId)
                        <input type="hidden" name="event_id" value="{{ $preselectedEventId }}">
                    @endif

                    <input type="hidden" name="member_number" id="detectedCodeInput">

                    <button type="submit"
                        style="
                        padding:10px 20px;
                        background:#2e7d32;
                        color:white;
                        border:none;
                        border-radius:8px;
                        font-weight:bold;
                        cursor:pointer;
                        width:100%;
                    ">
                        Konfirmasi & Cari Member
                    </button>

                </form>

            </div>

        </div>

        <div style="margin-top:15px;display:flex;gap:10px;">

            <button id="startScannerBtn" onclick="startScanner()"
                style="
                padding:10px 20px;
                background:#00e5ff;
                color:black;
                border:none;
                border-radius:8px;
                font-weight:bold;
                cursor:pointer;
            ">
                ▶️ Mulai Kamera
            </button>

            <button id="stopScannerBtn" onclick="stopScanner()"
                style="
                display:none;
                padding:10px 20px;
                background:#c62828;
                color:white;
                border:none;
                border-radius:8px;
                font-weight:bold;
                cursor:pointer;
            ">
                ⏹ Stop Kamera
            </button>

        </div>

        <p style="color:#888;font-size:13px;margin-top:10px;">
            📱 Arahkan kamera ke barcode member. Scanner akan otomatis mendeteksi.
            Barcode sudah diamankan dengan enkripsi.
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

    {{-- html5-qrcode library --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;
        let isScannerRunning = false;

        function switchTab(tab) {
            const manualPanel = document.getElementById('manualPanel');
            const cameraPanel = document.getElementById('cameraPanel');
            const manualBtn = document.getElementById('tabManualBtn');
            const cameraBtn = document.getElementById('tabCameraBtn');

            if (tab === 'manual') {
                manualPanel.style.display = 'block';
                cameraPanel.style.display = 'none';
                manualBtn.style.background = '#00e5ff';
                manualBtn.style.color = 'black';
                cameraBtn.style.background = '#333';
                cameraBtn.style.color = 'white';
                stopScanner();
            } else {
                manualPanel.style.display = 'none';
                cameraPanel.style.display = 'block';
                cameraBtn.style.background = '#00e5ff';
                cameraBtn.style.color = 'black';
                manualBtn.style.background = '#333';
                manualBtn.style.color = 'white';
            }
        }

        function startScanner() {
            const container = document.getElementById('scannerContainer');
            const loading = document.getElementById('scannerLoading');
            const startBtn = document.getElementById('startScannerBtn');
            const stopBtn = document.getElementById('stopScannerBtn');
            const scanResult = document.getElementById('scanResult');

            if (isScannerRunning) return;

            loading.style.display = 'block';
            scanResult.style.display = 'none';
            document.getElementById('detectedCode').textContent = '';
            document.getElementById('detectedCodeInput').value = '';

            // Make sure previous instance is cleaned up
            if (html5QrCode) {
                html5QrCode.clear();
                html5QrCode = null;
            }

            html5QrCode = new Html5Qrcode("scannerContainer");

            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 120
                },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.EAN_8,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.UPC_E,
                    Html5QrcodeSupportedFormats.CODE_93,
                    Html5QrcodeSupportedFormats.ITF,
                ]
            };

            html5QrCode.start({
                    facingMode: "environment"
                },
                config,
                onScanSuccess
            ).then(() => {
                isScannerRunning = true;
                loading.style.display = 'none';
                startBtn.style.display = 'none';
                stopBtn.style.display = 'inline-block';
            }).catch((err) => {
                loading.innerHTML = `
                    <p style="font-size:40px;margin-bottom:10px;">⚠️</p>
                    <p style="color:#ff5252;">Gagal mengakses kamera</p>
                    <p style="font-size:13px;margin-top:5px;color:#aaa;">
                        ${err.message || 'Pastikan browser memiliki izin kamera'}
                    </p>
                `;
                startBtn.style.display = 'inline-block';
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Pause scanner to prevent multiple triggers
            if (html5QrCode && isScannerRunning) {
                html5QrCode.pause();
                isScannerRunning = false;
            }

            const scanResult = document.getElementById('scanResult');
            const stopBtn = document.getElementById('stopScannerBtn');
            const startBtn = document.getElementById('startScannerBtn');

            document.getElementById('detectedCode').textContent = decodedText;
            document.getElementById('detectedCodeInput').value = decodedText;
            scanResult.style.display = 'block';

            stopBtn.style.display = 'none';
            startBtn.textContent = '🔄 Scan Lagi';
            startBtn.style.display = 'inline-block';
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    isScannerRunning = false;
                }).catch((err) => {
                    console.log(err);
                });
            }

            document.getElementById('startScannerBtn').style.display = 'inline-block';
            document.getElementById('startScannerBtn').textContent = '▶️ Mulai Kamera';
            document.getElementById('stopScannerBtn').style.display = 'none';
            document.getElementById('scannerLoading').style.display = 'none';
            document.getElementById('scanResult').style.display = 'none';
        }

        // Auto-submit manual input on Enter
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('barcodeInput');

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('scanForm').submit();
                }
            });

            input.focus();

            // Clean up scanner on page unload
            window.addEventListener('beforeunload', function() {
                if (html5QrCode) {
                    html5QrCode.stop();
                }
            });
        });
    </script>

@endsection
