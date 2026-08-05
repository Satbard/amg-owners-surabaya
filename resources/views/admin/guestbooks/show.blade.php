@extends('layouts.admin')

@section('content')

    <div
        style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
        flex-wrap:wrap;
        gap:10px;
    ">

        <div>
            <h1>📖 {{ $guestbook->name }}</h1>
            <p style="color:#aaa;margin-top:5px;">
                Acara: <strong>{{ $guestbookEvent->title }}</strong>
                &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($guestbookEvent->event_date)->format('d M Y H:i') }}
            </p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">

            <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}/export"
                style="
                padding:10px 20px;
                background:#2e7d32;
                color:white;
                border-radius:8px;
                font-weight:bold;
                text-decoration:none;
            ">
                ⬇ Export Excel
            </a>

            <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}/edit"
                style="
                padding:10px 16px;
                background:#555;
                color:white;
                border-radius:8px;
                text-decoration:none;
            ">
                Edit
            </a>

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

    {{-- Pilih Guestbook --}}
    <div class="card" style="margin-bottom:20px;">

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        ">
            Pilih Guestbook
        </label>

        <select onchange="if(this.value){window.location.href=this.value;}"
            style="
            width:100%;
            padding:12px;
            background:#1d1d1d;
            border:1px solid #333;
            border-radius:8px;
            color:white;
        ">

            @foreach ($guestbooks as $gb)
                <option value="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $gb->id }}"
                    {{ $gb->id == $guestbook->id ? 'selected' : '' }}>
                    {{ $gb->name }}
                </option>
            @endforeach

        </select>

    </div>

    {{-- Pengisian Guestbook --}}
    <div class="card" style="margin-bottom:20px;">

        <h3 style="margin-bottom:15px;">
            ✍️ Pengisian Guestbook
        </h3>

        @if ($guestbook->fields->count())
            <form method="POST"
                action="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}/entries">

                @csrf

                @foreach ($guestbook->fields as $field)
                    <div style="margin-bottom:15px;">

                        <label
                            style="
                            display:block;
                            margin-bottom:8px;
                            font-weight:bold;
                        ">
                            {{ $field->label }}
                            @if ($field->is_required)
                                <span style="color:#ff5252;">*</span>
                            @else
                                <span style="color:#888;font-weight:normal;font-size:13px;">
                                    (opsional)
                                </span>
                            @endif
                        </label>

                        @if ($field->field_type == 'textarea')
                            <textarea name="values[{{ $field->id }}]" rows="3" {{ $field->is_required ? 'required' : '' }}
                                style="
                                width:100%;
                                padding:12px;
                                background:#1d1d1d;
                                border:1px solid #333;
                                border-radius:8px;
                                color:white;
                            ">{{ old('values.' . $field->id) }}</textarea>
                        @elseif ($field->field_type == 'number')
                            <input type="number" name="values[{{ $field->id }}]"
                                value="{{ old('values.' . $field->id) }}" {{ $field->is_required ? 'required' : '' }}
                                style="
                                width:100%;
                                padding:12px;
                                background:#1d1d1d;
                                border:1px solid #333;
                                border-radius:8px;
                                color:white;
                            ">
                        @elseif ($field->field_type == 'date')
                            <input type="date" name="values[{{ $field->id }}]"
                                value="{{ old('values.' . $field->id) }}" {{ $field->is_required ? 'required' : '' }}
                                style="
                                width:100%;
                                padding:12px;
                                background:#1d1d1d;
                                border:1px solid #333;
                                border-radius:8px;
                                color:white;
                            ">
                        @else
                            <input type="text" name="values[{{ $field->id }}]"
                                value="{{ old('values.' . $field->id) }}" {{ $field->is_required ? 'required' : '' }}
                                style="
                                width:100%;
                                padding:12px;
                                background:#1d1d1d;
                                border:1px solid #333;
                                border-radius:8px;
                                color:white;
                            ">
                        @endif

                    </div>
                @endforeach

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
                    Simpan Entri
                </button>

            </form>
        @else
            <p style="text-align:center;padding:30px;color:#aaa;">
                Guestbook ini belum memiliki kolom isian.
                <a href="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}/edit"
                    style="color:#00e5ff;">
                    Edit Guestbook
                </a>
                untuk menambahkan kolom.
            </p>
        @endif

    </div>

    {{-- Daftar Entri --}}
    <div class="card">

        <h2 style="margin-bottom:20px;">
            Daftar Entri
            <span style="color:#aaa;font-weight:normal;font-size:16px;">
                ({{ $guestbook->entries->count() }})
            </span>
        </h2>

        @if ($guestbook->entries->count())
            <div style="overflow-x:auto;">

                <table id="guestbookEntryTable"
                    style="
                    width:100%;
                    border-collapse:collapse;
                ">

                    <thead>
                        <tr style="background:#1d1d1d;">

                            @foreach ($guestbook->fields as $field)
                                <th style="padding:12px;">{{ $field->label }}</th>
                            @endforeach

                            <th style="padding:12px;">Waktu Input</th>
                            <th style="padding:12px;">Aksi</th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($guestbook->entries as $entry)
                            <tr style="border-top:1px solid #222;">

                                @foreach ($guestbook->fields as $field)
                                    <td style="padding:12px;">
                                        {{ $entry->data[$field->id] ?? '-' }}
                                    </td>
                                @endforeach

                                <td style="padding:12px;font-size:14px;color:#aaa;">
                                    {{ \Carbon\Carbon::parse($entry->created_at)->format('d M Y H:i') }}
                                </td>

                                <td style="padding:12px;">

                                    <form method="POST"
                                        action="/admin/guestbooks/{{ $guestbookEvent->id }}/guestbooks/{{ $guestbook->id }}/entries/{{ $entry->id }}"
                                        style="display:inline;" onsubmit="return confirm('Hapus entri ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            style="
                                            padding:6px 12px;
                                            background:#c62828;
                                            color:white;
                                            border:none;
                                            border-radius:6px;
                                            cursor:pointer;
                                        ">
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
        @else
            <p style="text-align:center;padding:40px;color:#aaa;">
                Belum ada entri. Isi form di atas untuk menambahkan entri pertama.
            </p>
        @endif

    </div>

    @if ($guestbook->entries->count())
        @push('scripts')
            <script>
                $(document).ready(function() {

                    $('#guestbookEntryTable').DataTable({

                        pageLength: 25,

                        order: [],

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

                            emptyTable: "Belum ada entri"

                        }

                    });

                });
            </script>
        @endpush
    @endif

@endsection
