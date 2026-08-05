@extends('layouts.admin')

@section('content')

    @php $fields = collect(); @endphp

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <div>
            <h1>Tambahkan Guestbook</h1>
            <p style="color:#aaa;margin-top:5px;">
                Acara: <strong>{{ $guestbookEvent->title }}</strong>
            </p>
        </div>

        <a href="/admin/guestbooks/{{ $guestbookEvent->id }}"
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

    <form method="POST" action="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks">

        @csrf

        <div class="card" style="margin-bottom:20px;">

            <h3 style="margin-bottom:15px;">Informasi Guestbook</h3>

            <div>

                <label
                    style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                ">
                    Nama Guestbook <span style="color:#ff5252;">*</span>
                </label>

                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="mis. Buku Tamu Kopdar, Guestbook Gathering" required
                    style="
                    width:100%;
                    padding:12px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">

            </div>

        </div>

        <div class="card" style="margin-bottom:20px;">

            <div
                style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:15px;
                flex-wrap:wrap;
                gap:10px;
            ">

                <div>
                    <h3 style="margin-bottom:5px;">Kolom Isian Guestbook</h3>
                    <p style="color:#aaa;font-size:14px;">
                        Tentukan parameter yang harus diisi oleh admin. Tandai <strong>Wajib</strong> jika tidak boleh
                        kosong.
                    </p>
                </div>

            </div>

            @include('admin.guestbooks._fields')

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
            Buat Guestbook
        </button>

    </form>

@endsection
