@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
        <h1>Tambah Panitia</h1>

        <a href="/admin/panitia"
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
        <div class="card"
            style="
            margin-bottom:20px;
            padding:14px 18px;
            background:#3a1b1b;
            border:1px solid #c62828;
            color:#ff8a8a;
        ">
            <ul style="margin-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="max-width:560px;">

        <p style="color:#888;font-size:14px;margin-bottom:20px;">
            Buat akun baru untuk panitia. Panitia hanya dapat melihat data pendaftaran member.
        </p>

        <form method="POST" action="/admin/panitia">
            @csrf

            <div style="margin-bottom:18px;">
                <label style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama panitia"
                    style="
                    width:100%;
                    padding:10px 14px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">
                    Username
                </label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="username panitia"
                    style="
                    width:100%;
                    padding:10px 14px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">
                <small style="color:#666;display:block;margin-top:5px;">
                    Username digunakan untuk login dan harus unik.
                </small>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">
                    Password
                </label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter"
                    style="
                    width:100%;
                    padding:10px 14px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                    style="
                    width:100%;
                    padding:10px 14px;
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
                Simpan Panitia
            </button>

        </form>

    </div>

@endsection
