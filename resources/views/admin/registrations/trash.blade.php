@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <h1>Trash Bin</h1>

        <a href="/admin/registrations"
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

    @if (session('success'))
        <div class="card" style="
        margin-bottom:20px;
        border-left:4px solid #4caf50;
    ">
            {{ session('success') }}
        </div>
    @endif

    @if ($registrations->count())
        <div class="card">

            <div style="overflow-x:auto;">

                <table id="trashTable" style="
            width:100%;
            border-collapse:collapse;
        ">

                    <thead>

                        <tr style="
                    background:#1d1d1d;
                ">

                            <th style="padding:12px;">
                                ID
                            </th>

                            <th style="padding:12px;">
                                Nama
                            </th>

                            <th style="padding:12px;">
                                Plat
                            </th>

                            <th style="padding:12px;">
                                Dihapus Pada
                            </th>

                            <th style="padding:12px;">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($registrations as $registration)
                            <tr style="
                    border-top:1px solid #222;
                ">

                                <td style="padding:12px;">
                                    {{ $registration->id }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $registration->full_name }}
                                </td>

                                <td style="padding:12px;">
                                    {{ $registration->license_plate }}
                                </td>

                                <td style="padding:12px;">

                                    {{ $registration->deleted_at ? $registration->deleted_at->format('d M Y H:i') : '-' }}

                                </td>

                                <td style="padding:12px;">

                                    <div
                                        style="
                            display:flex;
                            gap:8px;
                            flex-wrap:wrap;
                        ">

                                        <form action="/admin/registrations-trash/{{ $registration->id }}/restore"
                                            method="POST">

                                            @csrf

                                            <button type="submit"
                                                style="
                                    padding:8px 12px;
                                    border:none;
                                    border-radius:6px;
                                    background:#2e7d32;
                                    color:white;
                                    cursor:pointer;
                                ">
                                                Restore
                                            </button>

                                        </form>

                                        <form action="/admin/registrations-trash/{{ $registration->id }}/force-delete"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" onclick="return confirm('Hapus permanen data ini?')"
                                                style="
                                    padding:8px 12px;
                                    border:none;
                                    border-radius:6px;
                                    background:#c62828;
                                    color:white;
                                    cursor:pointer;
                                ">
                                                Delete Permanen
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

                    $('#trashTable').DataTable({

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

                            }

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
                🗑️ Trash Bin Kosong
            </h3>

            <p>
                Belum ada data yang dihapus.
            </p>

        </div>
    @endif

@endsection
