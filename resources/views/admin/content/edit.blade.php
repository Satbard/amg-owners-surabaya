@extends('layouts.admin')

@section('content')
    <h1 style="margin-bottom:20px;">
        Homepage CMS
    </h1>

    @if (session('success'))
        <div class="card" style="margin-bottom:20px;border-left:4px solid #4caf50;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="card" style="margin-bottom:20px;border-left:4px solid #c62828;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/content" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div
            style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
        gap:20px;
    ">

            <!-- Logo Homepage -->

            <div class="card">

                <h2 style="color:#00e5ff;margin-bottom:20px;">
                    Logo Homepage
                </h2>

                @if ($content->logo)
                    <div style="text-align:center;margin-bottom:20px;">

                        <img src="{{ asset('storage/' . $content->logo) }}"
                            style="
                        max-width:220px;
                        max-height:150px;
                        object-fit:contain;
                    ">

                    </div>
                @endif

                <input type="file" name="logo" class="form-input">

                <small style="color:#aaa;">
                    Logo yang tampil di tengah Homepage.
                </small>

            </div>



            <!-- Logo Header -->

            <div class="card">

                <h2 style="color:#00e5ff;margin-bottom:20px;">
                    Logo Header
                </h2>

                @if ($content->header_logo)
                    <div style="text-align:center;margin-bottom:20px;">

                        <img src="{{ asset('storage/' . $content->header_logo) }}"
                            style="
                        max-width:220px;
                        max-height:80px;
                        object-fit:contain;
                    ">

                    </div>
                @endif

                <input type="file" name="header_logo" class="form-input">

                <small style="color:#aaa;">
                    Logo yang tampil di kiri dan kanan Header Website.
                </small>

            </div>



            <!-- Background Homepage -->

            <div class="card">

                <h2 style="color:#00e5ff;margin-bottom:20px;">
                    Background Homepage
                </h2>

                @if ($content->background)
                    <div style="text-align:center;margin-bottom:20px;">

                        <img src="{{ asset('storage/' . $content->background) }}"
                            style="
                        width:100%;
                        max-height:180px;
                        object-fit:cover;
                        border-radius:8px;
                    ">

                    </div>
                @endif

                <input type="file" name="background" class="form-input">

                <small style="color:#aaa;">
                    Background halaman Homepage.
                </small>

            </div>



            <!-- Background Form -->

            <div class="card">

                <h2 style="color:#00e5ff;margin-bottom:20px;">
                    Background Form Pendaftaran
                </h2>

                @if ($content->registration_background)
                    <div style="text-align:center;margin-bottom:20px;">

                        <img src="{{ asset('storage/' . $content->registration_background) }}"
                            style="
                        width:100%;
                        max-height:180px;
                        object-fit:cover;
                        border-radius:8px;
                    ">

                    </div>
                @endif

                <input type="file" name="registration_background" class="form-input">

                <small style="color:#aaa;">
                    Background khusus halaman Form Pendaftaran.
                </small>

            </div>

        </div>

        <br>

        <div class="card">

            <h2 style="color:#00e5ff;margin-bottom:20px;">
                Konten Homepage
            </h2>

            <div class="form-group">

                <label>Judul Homepage</label>

                <input type="text" name="title" value="{{ old('title', $content->title) }}" class="form-input">

            </div>

            <div class="form-group">

                <label>Deskripsi Homepage</label>

                <textarea name="description" class="form-input" style="min-height:160px;">{{ old('description', $content->description) }}</textarea>

            </div>

            <div class="form-group">

                <label>Tulisan Tombol Daftar</label>

                <input type="text" name="button_text" value="{{ old('button_text', $content->button_text) }}"
                    class="form-input">

            </div>

        </div>

        <br>

        <button type="submit"
            style="
        padding:14px 28px;
        background:#00e5ff;
        color:black;
        border:none;
        border-radius:8px;
        font-weight:bold;
        cursor:pointer;
    ">
            Simpan Perubahan
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

        @media(max-width:768px) {

            form>div:first-of-type {

                grid-template-columns: 1fr !important;

            }

        }
    </style>
@endsection
