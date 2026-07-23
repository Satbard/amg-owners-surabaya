@extends('layouts.admin')

@section('content')

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h1>Acara Media</h1>
        <a href="/admin/media-events/create"
            style="padding:10px 20px;background:#00e5ff;color:black;border-radius:8px;font-weight:bold;text-decoration:none;">
            + Buat Acara
        </a>
    </div>

    @if (session('success'))
        <div class="card" style="margin-bottom:20px;border-left:4px solid #4caf50;">
            {{ session('success') }}
        </div>
    @endif

    @if ($events->count())
        <div class="card">
            <div style="overflow-x:auto;">
                <table id="mediaEventTable" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#1d1d1d;">
                            <th style="padding:12px;">Acara</th>
                            <th style="padding:12px;">Tanggal</th>
                            <th style="padding:12px;">Lokasi</th>
                            <th style="padding:12px;">Status</th>
                            <th style="padding:12px;">Media Hadir</th>
                            <th style="padding:12px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr style="border-top:1px solid #222;">
                                <td style="padding:12px;">
                                    <strong>{{ $event->title }}</strong>
                                </td>
                                <td style="padding:12px;">
                                    {{ $event->event_date->format('d M Y H:i') }}
                                </td>
                                <td style="padding:12px;">
                                    {{ $event->location ?: '-' }}
                                </td>
                                <td style="padding:12px;">
                                    @if ($event->status == 'upcoming')
                                        <span
                                            style="background:#1976d2;padding:4px 10px;border-radius:12px;font-size:13px;">Upcoming</span>
                                    @elseif($event->status == 'ongoing')
                                        <span
                                            style="background:#f9a825;color:black;padding:4px 10px;border-radius:12px;font-size:13px;">Ongoing</span>
                                    @elseif($event->status == 'completed')
                                        <span
                                            style="background:#2e7d32;padding:4px 10px;border-radius:12px;font-size:13px;">Completed</span>
                                    @else
                                        <span
                                            style="background:#c62828;padding:4px 10px;border-radius:12px;font-size:13px;">Cancelled</span>
                                    @endif
                                </td>
                                <td style="padding:12px;">
                                    {{ $event->attendances->where('status', 'hadir')->count() }}
                                    /
                                    {{ $event->attendances->count() }}
                                </td>
                                <td style="padding:12px;">
                                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                        <a href="/admin/media-events/{{ $event->id }}"
                                            style="padding:6px 10px;background:#1976d2;color:white;border-radius:6px;text-decoration:none;">
                                            Detail
                                        </a>
                                        <a href="/admin/media-events/{{ $event->id }}/edit"
                                            style="padding:6px 10px;background:#555;color:white;border-radius:6px;text-decoration:none;">
                                            Edit
                                        </a>
                                        <form method="POST" action="/admin/media-events/{{ $event->id }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus acara ini?')"
                                                style="padding:6px 10px;background:#c62828;color:white;border:none;border-radius:6px;cursor:pointer;">
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
                    $('#mediaEventTable').DataTable({
                        pageLength: 10,
                        order: [
                            [1, 'desc']
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
                            emptyTable: "Belum ada acara media"
                        }
                    });
                });
            </script>
        @endpush
    @else
        <div class="card" style="text-align:center;padding:60px;">
            <h3>📅 Belum Ada Acara Media</h3>
            <p style="color:#aaa;margin-top:10px;">Buat acara media terlebih dahulu untuk memulai scan barcode.</p>
            <br>
            <a href="/admin/media-events/create"
                style="display:inline-block;padding:12px 24px;background:#00e5ff;color:black;border-radius:8px;font-weight:bold;text-decoration:none;">
                Buat Acara Baru
            </a>
        </div>
    @endif

@endsection
