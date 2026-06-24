@extends('layouts.admin')

@section('content')

    <h1 style="
    margin-bottom:20px;
">
        Activity Logs
    </h1>

    @if ($logs->count())
        <div class="card">

            <div style="overflow-x:auto;">

                <table id="activityTable" style="
            width:100%;
            border-collapse:collapse;
        ">

                    <thead>

                        <tr style="
                background:#1d1d1d;
            ">

                            <th style="padding:12px;">
                                Waktu
                            </th>

                            <th style="padding:12px;">
                                Admin
                            </th>

                            <th style="padding:12px;">
                                Aktivitas
                            </th>

                            <th style="padding:12px;">
                                IP Address
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($logs as $log)
                            <tr style="
                border-top:1px solid #222;
            ">

                                <td style="padding:12px;">

                                    @if ($log->created_at instanceof \Carbon\Carbon)
                                        {{ $log->created_at->format('d M Y H:i') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}
                                    @endif

                                </td>

                                <td style="padding:12px;">

                                    {{ $log->user->name ?? '-' }}

                                </td>

                                <td style="padding:12px;">

                                    {{ $log->activity }}

                                </td>

                                <td style="padding:12px;">

                                    <code>
                                        {{ $log->ip_address }}
                                    </code>

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

                    $('#activityTable').DataTable({

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

                            infoEmpty: "Belum ada activity log",

                            emptyTable: "Belum ada activity log"

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
                📜 Belum Ada Activity Log
            </h3>

            <p>
                Aktivitas admin akan tercatat dan tampil di halaman ini.
            </p>

        </div>
    @endif

@endsection
