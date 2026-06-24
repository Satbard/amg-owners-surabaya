@extends('layouts.admin')

@section('content')
    <h1 style="
        margin-bottom:10px;
    ">
        Dashboard Admin
    </h1>

    <p style="
        color:#aaa;
        margin-bottom:30px;
    ">
        Selamat datang, {{ auth()->user()->name }}
    </p>

    <div
        style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:20px;
        margin-bottom:30px;
    ">

        <div class="card">

            <h3 style="
                color:#00e5ff;
                margin-bottom:10px;
            ">
                Total Pendaftaran
            </h3>

            <h1>
                {{ $total }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#ffc107;
                margin-bottom:10px;
            ">
                Pending
            </h3>

            <h1>
                {{ $pending }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#4caf50;
                margin-bottom:10px;
            ">
                Approved
            </h3>

            <h1>
                {{ $approved }}
            </h1>

        </div>

        <div class="card">

            <h3 style="
                color:#ff5252;
                margin-bottom:10px;
            ">
                Rejected
            </h3>

            <h1>
                {{ $rejected }}
            </h1>

        </div>

    </div>

    <div class="card">

        <h2 style="
            margin-bottom:20px;
        ">
            Quick Access
        </h2>

        <div style="
            display:flex;
            flex-wrap:wrap;
            gap:15px;
        ">

            <a href="/admin/registrations"
                style="
                padding:12px 20px;
                background:#00e5ff;
                color:black;
                border-radius:8px;
                font-weight:bold;
            ">
                Data Pendaftaran
            </a>

            <a href="/admin/registrations-trash"
                style="
                padding:12px 20px;
                background:#444;
                color:white;
                border-radius:8px;
            ">
                Trash Bin
            </a>

            <a href="/admin/activity-logs"
                style="
                padding:12px 20px;
                background:#444;
                color:white;
                border-radius:8px;
            ">
                Activity Logs
            </a>

            <a href="/admin/content"
                style="
                padding:12px 20px;
                background:#444;
                color:white;
                border-radius:8px;
            ">
                Homepage CMS
            </a>

        </div>

    </div>
@endsection
