@extends('layouts.admin')

@section('content')
    <h1 style="margin-bottom:10px;">Scan Barcode Media</h1>

    <p style="color:#aaa;margin-bottom:30px;">
        Scan atau masukkan barcode media untuk menandai kehadiran.
        Hanya media dengan status <strong>Approved</strong> yang dapat di-scan.
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
            Masukkan Barcode Media
        </h3>

        <form method="POST" action="/admin/scan-media/lookup">

            @csrf

            <div style="display:flex;gap:10px;">

                <input type="text" name="barcode" id="barcodeInput" placeholder="Ketik atau scan barcode media..."
                    autofocus autocomplete="off"
                    style="
                    flex:1;
                    padding:14px;
                    background:#1d1d1d;
                    border:2px solid #555;
                    border-radius:8px;
                    color:white;
                    font-size:16px;
                    letter-spacing:3px;
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
            💡 Masukkan kode barcode 8 karakter yang tertera pada email pendaftaran media.
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

                <form method="POST" action="/admin/scan-media/lookup" style="margin-top:15px;">

                    @csrf

                    <input type="hidden" name="barcode" id="detectedCodeInput">

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
                        Konfirmasi & Cari Media
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
            📱 Arahkan kamera ke barcode media. Scanner akan otomatis mendeteksi.
        </p>

    </div>

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
                }, config, onScanSuccess)
                .then(() => {
                    isScannerRunning = true;
                    loading.style.display = 'none';
                    startBtn.style.display = 'none';
                    stopBtn.style.display = 'inline-block';
                })
                .catch((err) => {
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

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('barcodeInput');
            if (input) {
                input.focus();
            }
        });
    </script>
@endsection
