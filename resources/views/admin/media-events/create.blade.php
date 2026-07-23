@extends('layouts.admin')

@section('content')

    <h1 style="margin-bottom:20px;">Buat Acara Media</h1>

    <a href="/admin/media-events"
        style="display:inline-block;margin-bottom:20px;padding:10px 16px;background:#333;color:white;border-radius:8px;text-decoration:none;">
        ← Kembali
    </a>

    @if ($errors->any())
        <div class="card" style="border:1px solid #c62828;margin-bottom:20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/media-events" class="card" style="max-width:600px;">
        @csrf

        <div class="form-group">
            <label>Judul Acara</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-input" style="min-height:100px;">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Tanggal & Waktu</label>
            <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" value="{{ old('location') }}" class="form-input">
        </div>

        <button type="submit"
            style="width:100%;padding:14px;background:#00e5ff;color:black;border:none;border-radius:8px;font-weight:bold;cursor:pointer;">
            Simpan
        </button>
    </form>

    <style>
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #333;
            background: #1a1a1a;
            color: white;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #00e5ff;
        }
    </style>

@endsection
