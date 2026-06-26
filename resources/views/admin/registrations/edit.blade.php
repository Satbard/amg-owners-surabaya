@extends('layouts.admin')

@section('content')

    <h1 style="margin-bottom:20px;">
        Edit Pendaftaran
    </h1>

    <a href="/admin/registrations"
        style="
        display:inline-block;
        margin-bottom:20px;
        padding:10px 16px;
        background:#333;
        color:white;
        border-radius:8px;
    ">
        ← Kembali
    </a>

    @if ($errors->any())
        <div class="card" style="
        border:1px solid #c62828;
        margin-bottom:20px;
    ">

            <ul>

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif

    <form method="POST" action="/admin/registrations/{{ $registration->id }}">

        @csrf
        @method('PUT')

        <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
    ">

            <div class="card">

                <h2 style="
                color:#00e5ff;
                margin-bottom:20px;
            ">
                    Data Diri
                </h2>

                <div class="form-group">

                    <label>Nama Lengkap</label>

                    <input type="text" name="full_name" value="{{ old('full_name', $registration->full_name) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Nama Panggilan</label>

                    <input type="text" name="nickname" value="{{ old('nickname', $registration->nickname) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Tempat Lahir</label>

                    <input type="text" name="birth_place" value="{{ old('birth_place', $registration->birth_place) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Tanggal Lahir</label>

                    <input type="date" name="birth_date" value="{{ old('birth_date', $registration->birth_date) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Alamat</label>

                    <textarea name="address" class="form-input" style="min-height:100px;">{{ old('address', $registration->address) }}</textarea>

                </div>

                <div class="form-group">

                    <label>No HP</label>

                    <input type="text" name="phone" value="{{ old('phone', $registration->phone) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Email</label>

                    <input type="email" name="email" value="{{ old('email', $registration->email) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Instagram</label>

                    <input type="text" name="instagram" value="{{ old('instagram', $registration->instagram) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Pekerjaan</label>

                    <input type="text" name="occupation" value="{{ old('occupation', $registration->occupation) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Ukuran Kaos</label>

                    <select name="shirt_size" class="form-input">

                        @foreach (['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                            <option value="{{ $size }}"
                                {{ old('shirt_size', $registration->shirt_size) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

            <div class="card">

                <h2 style="
                color:#00e5ff;
                margin-bottom:20px;
            ">
                    Data Kendaraan
                </h2>

                <div class="form-group">

                    <label>Model Kendaraan</label>

                    <input type="text" name="vehicle_model"
                        value="{{ old('vehicle_model', $registration->vehicle_model) }}" class="form-input">

                </div>

                <div class="form-group">

                    <label>Tahun Kendaraan</label>

                    <input type="number" name="vehicle_year"
                        value="{{ old('vehicle_year', $registration->vehicle_year) }}" class="form-input">

                </div>

                <div class="form-group">

                    <label>No. Rangka / VIN</label>

                    <input type="text" name="vehicle_color"
                        value="{{ old('vehicle_color', $registration->vehicle_color) }}" class="form-input">

                </div>

                <div class="form-group">

                    <label>Nomor Polisi</label>

                    <input type="text" name="license_plate"
                        value="{{ old('license_plate', $registration->license_plate) }}" class="form-input">

                </div>

                <hr style="margin:25px 0;border-color:#222;">

                <h2 style="
                color:#00e5ff;
                margin-bottom:15px;
            ">
                    Status Keanggotaan
                </h2>

                <select name="membership_status" class="form-input">

                    <option value="Pending"
                        {{ old('membership_status', $registration->membership_status) == 'Pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="Approved"
                        {{ old('membership_status', $registration->membership_status) == 'Approved' ? 'selected' : '' }}>
                        Approved
                    </option>

                    <option value="Rejected"
                        {{ old('membership_status', $registration->membership_status) == 'Rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>

                </select>

                <br>

                <button type="submit"
                    style="
                width:100%;
                padding:14px;
                background:#00e5ff;
                color:black;
                border:none;
                border-radius:8px;
                font-weight:bold;
                cursor:pointer;
            ">
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>

    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #333;
            background: #1a1a1a;
            color: white;
        }

        @media(max-width:768px) {

            form>div {
                grid-template-columns: 1fr !important;
            }

        }
    </style>

@endsection
