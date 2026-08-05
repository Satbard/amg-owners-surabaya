@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <div>
            <h1>{{ $guestbookEvent->title }}</h1>
            <p style="color:#aaa;margin-top:5px;">
                {{ \Carbon\Carbon::parse($guestbookEvent->event_date)->format('d M Y H:i') }}
                @if ($guestbookEvent->location)
                    &nbsp;·&nbsp; {{ $guestbookEvent->location }}
                @endif
            </p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">

            <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/create"
                style="
                padding:10px 16px;
                background:#00e5ff;
                color:black;
                border-radius:8px;
                font-weight:bold;
                text-decoration:none;
            ">
                + Tambahkan Guestbook
            </a>

            <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/edit"
                style="
                padding:10px 16px;
                background:#555;
                color:white;
                border-radius:8px;
                text-decoration:none;
            ">
                Edit
            </a>

            <a href="/admin/guestbooks"
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

    @if ($guestbookEvent->description)
        <div class="card" style="margin-bottom:20px;">
            <p>{{ $guestbookEvent->description }}</p>
        </div>
    @endif

    <div class="card">

        <h2 style="margin-bottom:20px;">
            Daftar Guestbook
            <span style="color:#aaa;font-weight:normal;font-size:16px;">
                ({{ $guestbookEvent->guestbooks->count() }})
            </span>
        </h2>

        @if ($guestbookEvent->guestbooks->count())
            <div style="overflow-x:auto;">

                <table id="guestbookTable"
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>
                        <tr style="background:#1d1d1d;">
                            <th style="padding:12px;">Nama Guestbook</th>
                            <th style="padding:12px;">Jumlah Kolom</th>
                            <th style="padding:12px;">Jumlah Entri</th>
                            <th style="padding:12px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($guestbookEvent->guestbooks as $guestbook)
                            <tr style="border-top:1px solid #222;">

                                <td style="padding:12px;">
                                    <strong>{{ $guestbook->name }}</strong>
                                </td>

                                <td style="padding:12px;">
                                    {{ $guestbook->fields->count() }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $guestbook->entries->count() }}
                                </td>

                                <td style="padding:12px;">

                                    <div
                                        style="
                                        display:flex;
                                        gap:8px;
                                        flex-wrap:wrap;
                                    ">

                                        <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}"
                                            style="
                                            padding:6px 10px;
                                            background:#1976d2;
                                            color:white;
                                            border-radius:6px;
                                            text-decoration:none;
                                        ">
                                            Buka
                                        </a>

                                        <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}/edit"
                                            style="
                                            padding:6px 10px;
                                            background:#555;
                                            color:white;
                                            border-radius:6px;
                                            text-decoration:none;
                                        ">
                                            Edit
                                        </a>

                                        <form method="POST"
                                            action="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}"
                                            style="display:inline;"
                                            onsubmit="return confirm('Hapus Guestbook ini? Semua kolom dan entri di dalamnya akan ikut terhapus.')">

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
        @else
            <p style="text-align:center;padding:40px;color:#aaa;">
                Belum ada Guestbook untuk acara ini. Klik
                <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/create" style="color:#00e5ff;">
                    "+ Tambahkan Guestbook"
                </a>
                untuk membuatnya.
            </p>
        @endif

    </div>

@endsection
