@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Buat Acara Baru</h1>

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

    @if ($errors->any())
        <div
            style="
            background:#c62828;
            padding:12px 16px;
            border-radius:8px;
            margin-bottom:20px;
        ">
            <ul style="margin-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <form method="POST" action="/admin/events">

            @csrf

            <div style="margin-bottom:20px;">

                <label
                    style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                ">
                    Nama Acara <span style="color:#ff5252;">*</span>
                </label>

                <input type="text" name="title" value="{{ old('title') }}" required
                    style="
                    width:100%;
                    padding:12px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">

            </div>

            <div style="margin-bottom:20px;">

                <label
                    style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                ">
                    Deskripsi
                </label>

                <textarea name="description" rows="4"
                    style="
                    width:100%;
                    padding:12px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">{{ old('description') }}</textarea>

            </div>

            <div style="margin-bottom:20px;">

                <label
                    style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                ">
                    Tanggal & Waktu Acara <span style="color:#ff5252;">*</span>
                </label>

                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required
                    style="
                    width:100%;
                    padding:12px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">

            </div>

            <div style="margin-bottom:20px;">

                <label
                    style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                ">
                    Lokasi
                </label>

                <input type="text" name="location" value="{{ old('location') }}"
                    style="
                    width:100%;
                    padding:12px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">

            </div>

            <button type="submit"
                style="
                padding:12px 24px;
                background:#00e5ff;
                color:black;
                border:none;
                border-radius:8px;
                font-weight:bold;
                cursor:pointer;
            ">
                Simpan Acara
            </button>

        </form>

    </div>

@endsection
