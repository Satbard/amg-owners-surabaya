{{-- Dynamic field builder — expects $fields (collection of GuestbookField) --}}

<div id="fieldBuilder" style="
    display:flex;
    flex-direction:column;
    gap:10px;
    margin-bottom:15px;
">

    @foreach ($fields as $field)
        <div class="field-row"
            style="
            display:flex;
            gap:8px;
            align-items:center;
            flex-wrap:wrap;
            padding:12px;
            background:#1d1d1d;
            border:1px solid #333;
            border-radius:8px;
        ">

            <input type="hidden" name="fields[id][{{ $field->id }}]" value="{{ $field->id }}">

            <input type="text" name="fields[label][{{ $field->id }}]" value="{{ $field->label }}"
                placeholder="Label kolom (mis. Nama Tamu)" required
                style="
                flex:1;
                min-width:180px;
                padding:10px;
                background:#0f0f0f;
                border:1px solid #333;
                border-radius:6px;
                color:white;
            ">

            <select name="fields[field_type][{{ $field->id }}]"
                style="
                padding:10px;
                background:#0f0f0f;
                border:1px solid #333;
                border-radius:6px;
                color:white;
            ">
                <option value="text" {{ $field->field_type == 'text' ? 'selected' : '' }}>Teks</option>
                <option value="textarea" {{ $field->field_type == 'textarea' ? 'selected' : '' }}>Teks Panjang</option>
                <option value="number" {{ $field->field_type == 'number' ? 'selected' : '' }}>Angka</option>
                <option value="date" {{ $field->field_type == 'date' ? 'selected' : '' }}>Tanggal</option>
            </select>

            <label
                style="
                display:flex;
                align-items:center;
                gap:6px;
                color:#aaa;
                font-size:14px;
                cursor:pointer;
            ">
                <input type="checkbox" name="fields[is_required][{{ $field->id }}]" value="1"
                    {{ $field->is_required ? 'checked' : '' }} style="width:16px;height:16px;cursor:pointer;">
                Wajib
            </label>

            <button type="button" onclick="moveFieldRow(this, -1)"
                style="
                padding:6px 10px;
                background:#333;
                color:white;
                border:none;
                border-radius:6px;
                cursor:pointer;
            ">↑</button>

            <button type="button" onclick="moveFieldRow(this, 1)"
                style="
                padding:6px 10px;
                background:#333;
                color:white;
                border:none;
                border-radius:6px;
                cursor:pointer;
            ">↓</button>

            <button type="button" onclick="this.closest('.field-row').remove()"
                style="
                padding:6px 10px;
                background:#c62828;
                color:white;
                border:none;
                border-radius:6px;
                cursor:pointer;
            ">Hapus</button>

        </div>
    @endforeach

</div>

<button type="button" id="addFieldBtn"
    style="
    padding:10px 16px;
    background:#333;
    color:white;
    border:1px dashed #555;
    border-radius:8px;
    cursor:pointer;
">
    + Tambah Kolom
</button>

@push('scripts')
    <script>
        let fieldKey = 100000;

        function makeFieldRowHTML(field) {
            const key = field.key ?? ('new_' + (fieldKey++));
            const id = field.id ?? '';
            const label = field.label ?? '';
            const fieldType = field.field_type ?? 'text';
            const isRequired = field.is_required ? 'checked' : '';

            const typeOptions = [
                ['text', 'Teks'],
                ['textarea', 'Teks Panjang'],
                ['number', 'Angka'],
                ['date', 'Tanggal'],
            ].map(function(item) {
                return '<option value="' + item[0] + '" ' +
                    (fieldType === item[0] ? 'selected' : '') + '>' + item[1] + '</option>';
            }).join('');

            return '' +
                '<div class="field-row" style="' +
                'display:flex;gap:8px;align-items:center;flex-wrap:wrap;' +
                'padding:12px;background:#1d1d1d;border:1px solid #333;border-radius:8px;' +
                '">' +
                '<input type="hidden" name="fields[id][' + key + ']" value="' + id + '">' +
                '<input type="text" name="fields[label][' + key + ']" value="' + label +
                '" placeholder="Label kolom (mis. Nama Tamu)" required style="' +
                'flex:1;min-width:180px;padding:10px;background:#0f0f0f;' +
                'border:1px solid #333;border-radius:6px;color:white;">' +
                '<select name="fields[field_type][' + key + ']" style="' +
                'padding:10px;background:#0f0f0f;border:1px solid #333;' +
                'border-radius:6px;color:white;">' + typeOptions + '</select>' +
                '<label style="display:flex;align-items:center;gap:6px;color:#aaa;font-size:14px;cursor:pointer;">' +
                '<input type="checkbox" name="fields[is_required][' + key + ']" value="1" ' + isRequired +
                ' style="width:16px;height:16px;cursor:pointer;"> Wajib</label>' +
                '<button type="button" onclick="moveFieldRow(this, -1)" style="' +
                'padding:6px 10px;background:#333;color:white;border:none;border-radius:6px;cursor:pointer;">↑</button>' +
                '<button type="button" onclick="moveFieldRow(this, 1)" style="' +
                'padding:6px 10px;background:#333;color:white;border:none;border-radius:6px;cursor:pointer;">↓</button>' +
                '<button type="button" onclick="this.closest(\'.field-row\').remove()" style="' +
                'padding:6px 10px;background:#c62828;color:white;border:none;border-radius:6px;cursor:pointer;">Hapus</button>' +
                '</div>';
        }

        function addFieldRow(field) {
            document.getElementById('fieldBuilder').insertAdjacentHTML('beforeend', makeFieldRowHTML(field || {}));
        }

        function moveFieldRow(btn, dir) {
            const row = btn.closest('.field-row');
            if (dir === -1 && row.previousElementSibling) {
                row.parentNode.insertBefore(row, row.previousElementSibling);
            } else if (dir === 1 && row.nextElementSibling) {
                row.parentNode.insertBefore(row.nextElementSibling, row);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('addFieldBtn');
            if (btn) {
                btn.addEventListener('click', function() {
                    addFieldRow();
                });
            }
        });
    </script>
@endpush
