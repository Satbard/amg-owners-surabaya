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

        <a href="/admin/registrations-export-barcodes"
            style="
        padding:10px 18px;
        background:#7c4dff;
        color:white;
        border-radius:8px;
        font-weight:bold;
        text-decoration:none;
        ">
            📷 Export Barcode
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

    {{-- Filter Bar --}}
    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="/admin/registrations"
            style="
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            align-items:end;
        ">
            <div style="flex:1;min-width:200px;">
                <label style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">
                    Cari (Nama / No. HP / Plat / No. Member)
                </label>
                <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Ketik kata kunci..."
                    style="
                    width:100%;
                    padding:10px 14px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">
            </div>

            <div style="min-width:160px;">
                <label style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">
                    Filter Status
                </label>
                <select name="status" onchange="this.form.submit()"
                    style="
                    width:100%;
                    padding:10px 14px;
                    background:#1d1d1d;
                    border:1px solid #333;
                    border-radius:8px;
                    color:white;
                ">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ ($status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ ($status ?? '') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ ($status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <button type="submit"
                style="
                padding:10px 20px;
                background:#00e5ff;
                color:black;
                border:none;
                border-radius:8px;
                font-weight:bold;
                cursor:pointer;
            ">
                Cari
            </button>

            @if (request()->filled('keyword') || request()->filled('status'))
                <a href="/admin/registrations"
                    style="
                    padding:10px 20px;
                    background:#555;
                    color:white;
                    border-radius:8px;
                    text-decoration:none;
                    font-size:14px;
                ">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    @if ($registrations->count())
        {{-- Batch Action Bar --}}
        <div class="card" style="margin-bottom:20px;padding:15px 20px;">
            <form method="POST" action="/admin/registrations/batch-update" id="batchForm">
                @csrf
                <input type="hidden" name="action" id="batchAction">

                <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                    <span style="color:#aaa;font-size:14px;margin-right:5px;">
                        ☑ <span id="selectedCount">0</span> terpilih
                    </span>

                    <button type="button" class="batch-action-btn" data-action="approve"
                        style="padding:8px 16px;background:#2e7d32;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:bold;">
                        ✓ Setujui Terpilih
                    </button>

                    <button type="button" class="batch-action-btn" data-action="reject"
                        style="padding:8px 16px;background:#c62828;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:bold;">
                        ✕ Tolak Terpilih
                    </button>

                    <button type="button" class="batch-action-btn" data-action="pending"
                        style="padding:8px 16px;background:#f9a825;color:black;border:none;border-radius:6px;cursor:pointer;font-weight:bold;">
                        ⏳ Pending-kan Terpilih
                    </button>
                </div>
            </form>
        </div>

        <div class="card">

            <div style="overflow-x:auto;">

                <table id="registrationTable"
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>

                        <tr style="background:#1d1d1d;">

                            <th style="padding:12px;width:40px;">
                                <input type="checkbox" id="selectAll" style="width:18px;height:18px;cursor:pointer;">
                            </th>
                            <th style="padding:12px;">ID</th>
                            <th style="padding:12px;">No. Member</th>
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

                                <td style="padding:12px;text-align:center;">
                                    <input type="checkbox" name="ids[]" value="{{ $registration->id }}"
                                        class="row-checkbox" style="width:18px;height:18px;cursor:pointer;">
                                </td>

                                <td style="padding:12px;">
                                    {{ $registration->id }}
                                </td>

                                <td style="padding:12px;font-family:monospace;color:#00e5ff;font-weight:bold;">
                                    {{ $registration->member_number ?? '-' }}
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
                            [1, 'desc']
                        ],

                        columnDefs: [{
                            targets: 0,
                            orderable: false,
                        }],

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

                        },

                        drawCallback: function() {
                            updateSelectedCount();
                        }

                    });

                    // Select All checkbox
                    document.getElementById('selectAll').addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('.row-checkbox');
                        checkboxes.forEach(cb => cb.checked = this.checked);
                        updateSelectedCount();
                    });

                    // Individual checkbox → update count + uncheck selectAll if any unchecked
                    document.addEventListener('change', function(e) {
                        if (e.target.classList.contains('row-checkbox')) {
                            updateSelectedCount();
                            const allChecked = document.querySelectorAll('.row-checkbox:checked').length ===
                                document.querySelectorAll('.row-checkbox').length;
                            document.getElementById('selectAll').checked = allChecked;
                        }
                    });

                    // Batch action buttons
                    document.querySelectorAll('.batch-action-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const checked = document.querySelectorAll('.row-checkbox:checked');
                            if (checked.length === 0) {
                                alert('Pilih minimal satu data.');
                                return;
                            }
                            const actionLabels = {
                                'approve': 'menyetujui',
                                'reject': 'menolak',
                                'pending': 'mengubah status menjadi pending'
                            };
                            const action = this.dataset.action;
                            if (!confirm(
                                    `Yakin akan ${actionLabels[action]} ${checked.length} data terpilih?`
                                )) {
                                return;
                            }
                            document.getElementById('batchAction').value = action;
                            document.getElementById('batchForm').submit();
                        });
                    });

                    function updateSelectedCount() {
                        const count = document.querySelectorAll('.row-checkbox:checked').length;
                        document.getElementById('selectedCount').textContent = count;
                    }

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
