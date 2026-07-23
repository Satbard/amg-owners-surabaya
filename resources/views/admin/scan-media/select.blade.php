@extends('layouts.admin')

@section('content')
    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Pilih Media</h1>

        <a href="/admin/scan-media"
            style="
            padding:10px 16px;
            background:#333;
            color:white;
            border-radius:8px;
            text-decoration:none;
        ">
            ← Cari Lagi
        </a>

    </div>

    <div class="card">

        <h3 style="margin-bottom:15px;">
            Ditemukan {{ $media->count() }} media — pilih untuk menandai hadir
        </h3>

        <div
            style="
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
            gap:12px;
        ">

            @foreach ($media as $item)
                <div
                    style="
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:10px;
                    padding:16px;
                ">

                    <div style="display:flex;align-items:center;gap:12px;">

                        <div style="flex:1;">
                            <h4 style="margin-bottom:2px;">{{ $item->full_name }}</h4>
                            <p style="color:#00e5ff;font-size:14px;font-weight:bold;">
                                {{ $item->media_name }}
                            </p>
                            <p style="color:#888;font-size:12px;margin-top:3px;">
                                {{ $item->competition_category }} · {{ $item->equipment_used }}
                            </p>
                        </div>

                    </div>

                    <div style="margin-top:12px;">
                        <form method="POST" action="/admin/scan-media/confirm">
                            @csrf
                            <input type="hidden" name="media_registration_id" value="{{ $item->id }}">
                            <button type="submit"
                                style="
                                padding:8px 16px;
                                background:#00e5ff;
                                color:black;
                                border:none;
                                border-radius:6px;
                                font-weight:bold;
                                cursor:pointer;
                                width:100%;
                            ">
                                Tandai Hadir
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

        </div>

    </div>
@endsection
