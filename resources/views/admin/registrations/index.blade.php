@extends('layouts.admin')

@section('content')

    <h1 style="margin-bottom:20px;">
        Data Pendaftaran
    </h1>

    <div style="
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:20px;
    ">

        <a href="/admin/registrations-export"
            style="
        padding:10px 18px;
        background:#00e5ff;
        color:black;
        border-radius:8px;
        font-weight:bold;
        text-decoration:none;
        ">
            Export Excel
        </a>

        <a href="/admin/registrations-trash"
            style="
        padding:10px 18px;
        background:#333;
        color:white;
        border-radius:8px;
        text-decoration:none;
        ">
            Trash Bin
        </a>

    </div>

    @if ($registrations->count())
        <div class="card">

            <div style="overflow-x:auto;">

                <table id="registrationTable"
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>

                        <tr style="background:#1d1d1d;">

                            <th style="padding:12px;">ID</th>
                            <th style="padding:12px;">Nama</th>
                            <th style="padding:12px;">No HP</th>
                            <th style="padding:12px;">Plat</th>
                            <th style="padding:12px;">Status</th>
                            <th style="padding:12px;">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($registrations as $registration)
                            <tr style="border-top:1px solid #222;">

                                <td style="padding:12px;">
                                    {{ $registration->id }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $registration->full_name }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $registration->phone }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $registration->license_plate }}
                                </td>

                                <td style="padding:12px;">

                                    @if ($registration->membership_status == 'Approved')
                                        <span
                                            style="
                            background:#2e7d32;
                            padding:6px 10px;
                            border-radius:20px;
                        ">
                                            Approved
                                        </span>
                                    @elseif($registration->membership_status == 'Rejected')
                                        <span
                                            style="
                            background:#c62828;
                            padding:6px 10px;
                            border-radius:20px;
                        ">
                                            Rejected
                                        </span>
                                    @else
                                        <span
                                            style="
                            background:#f9a825;
                            color:black;
                            padding:6px 10px;
                            border-radius:20px;
                        ">
                                            Pending
                                        </span>
                                    @endif

                                </td>

                                <td style="padding:12px;">

                                    <div
                                        style="
                        display:flex;
                        gap:8px;
                        flex-wrap:wrap;
                    ">

                                        <a href="/admin/registrations/{{ $registration->id }}"
                                            style="
                                padding:6px 10px;
                                background:#1976d2;
                                color:white;
                                border-radius:6px;
                                text-decoration:none;
                            ">
                                            Detail
                                        </a>

                                        <a href="/admin/registrations/{{ $registration->id }}/edit"
                                            style="
                                padding:6px 10px;
                                background:#555;
                                color:white;
                                border-radius:6px;
                                text-decoration:none;
                            ">
                                            Edit
                                        </a>

                                        <form method="POST" action="/admin/registrations/{{ $registration->id }}"
                                            style="display:inline;">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Hapus data ini?')"
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

        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {

                    $('#registrationTable').DataTable({

                        pageLength: 10,

                        order: [
                            [0, 'desc']
                        ],

                        language: {

                            search: "Cari:",

                            lengthMenu: "Tampilkan _MENU_ data",

                            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

                            paginate: {

                                previous: "←",

                                next: "→"

                            },

                            zeroRecords: "Data tidak ditemukan",

                            infoEmpty: "Belum ada data",

                            emptyTable: "Belum ada data pendaftaran"

                        }

                    });

                });
            </script>
        @endpush
    @else
        <div class="card" style="
        text-align:center;
        padding:60px;
    ">

            <h3>
                📋 Belum Ada Data Pendaftaran
            </h3>

            <p>
                Data pendaftaran yang masuk akan muncul di sini.
            </p>

        </div>
    @endif

@endsection
