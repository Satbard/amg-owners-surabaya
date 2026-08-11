@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
        <h1>Manajemen Panitia</h1>

        <a href="/admin/panitia/create"
            style="
            padding:10px 18px;
            background:#00e5ff;
            color:black;
            border-radius:8px;
            font-weight:bold;
            text-decoration:none;
        ">
            + Tambah Panitia
        </a>
    </div>

    @if (session('success'))
        <div class="card"
            style="
            margin-bottom:20px;
            padding:14px 18px;
            background:#1b3a2a;
            border:1px solid #2e7d32;
            color:#7ef0a8;
        ">
            {{ session('success') }}
        </div>
    @endif

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

    <div class="card">

        <div style="overflow-x:auto;">

            <table id="panitiaTable"
                style="
                width:100%;
                border-collapse:collapse;
            ">

                <thead>
                    <tr style="background:#1d1d1d;">
                        <th style="padding:12px;">No</th>
                        <th style="padding:12px;">Nama</th>
                        <th style="padding:12px;">Username</th>
                        <th style="padding:12px;">Dibuat Pada</th>
                        <th style="padding:12px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($panitia as $index => $user)
                        <tr style="border-top:1px solid #222;">
                            <td style="padding:12px;">
                                {{ $index + 1 }}
                            </td>
                            <td style="padding:12px;">
                                {{ $user->name }}
                            </td>
                            <td style="padding:12px;font-family:monospace;color:#00e5ff;">
                                {{ $user->username }}
                            </td>
                            <td style="padding:12px;">
                                {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td style="padding:12px;">
                                @if ($user->id === auth()->id())
                                    <span style="color:#888;font-size:13px;">
                                        Akun Anda
                                    </span>
                                @else
                                    <form method="POST" action="/admin/panitia/{{ $user->id }}"
                                        style="display:inline;">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus panitia {{ $user->name }}?')"
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
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            @if ($panitia->isEmpty())
                <div style="
                    text-align:center;
                    padding:60px;
                ">
                    <h3>👥 Belum Ada Panitia</h3>
                    <p style="color:#888;margin-top:10px;">
                        Klik "Tambah Panitia" untuk membuat akun panitia baru.
                    </p>
                </div>
            @endif

        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                if ($('#panitiaTable tbody tr').length) {
                    $('#panitiaTable').DataTable({
                        pageLength: 10,
                        order: [
                            [0, 'asc']
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
                            emptyTable: "Belum ada data panitia"
                        }
                    });
                }
            });
        </script>
    @endpush

@endsection
