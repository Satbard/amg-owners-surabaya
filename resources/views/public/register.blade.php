@extends('layouts.app')

@section('content')
    <div
        style="
        min-height:100vh;

        background-image:url('{{ $content && $content->registration_background ? asset('storage/' . $content->registration_background) : '' }}');

        background-size:cover;
        background-position:center;
        background-repeat:no-repeat;
        background-attachment:fixed;

        padding:40px 20px;
    ">

        <div
            style="
                max-width:900px;
                margin:auto;

                background:rgba(17,17,17,0.92);

                backdrop-filter:blur(8px);
                -webkit-backdrop-filter:blur(8px);

                border:1px solid rgba(255,255,255,0.08);

                border-radius:16px;

                padding:40px;
        ">

            <h1
                style="
                text-align:center;
                margin-bottom:10px;
                color:#00e5ff;
            ">
                Form Pendaftaran
            </h1>

            <p
                style="
                text-align:center;
                margin-bottom:40px;
                color:#bbb;
            ">
                Lengkapi data diri dan kendaraan Anda
            </p>

            <form method="POST" action="/register">

                @csrf

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Data Diri
                </h2>

                <div style="display:grid;gap:15px;">

                    <input type="text" name="full_name" placeholder="Nama Lengkap" value="{{ old('full_name') }}"
                        class="form-input {{ $errors->has('full_name') ? 'input-error' : '' }}">

                    @error('full_name')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="text" name="nickname" placeholder="Nama Panggilan" value="{{ old('nickname') }}"
                        class="form-input {{ $errors->has('nickname') ? 'input-error' : '' }}">

                    @error('nickname')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="text" name="birth_place" placeholder="Tempat Lahir" value="{{ old('birth_place') }}"
                        class="form-input {{ $errors->has('birth_place') ? 'input-error' : '' }}">

                    @error('birth_place')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="text" name="birth_date" value="{{ old('birth_date') }}"
                        class="form-input date-input {{ $errors->has('birth_date') ? 'input-error' : '' }}"
                        placeholder="Tanggal Lahir" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">

                    @error('birth_date')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <textarea name="address" placeholder="Alamat Domisili"
                        class="form-input  {{ $errors->has('address') ? 'input-error' : '' }}" style="min-height:120px; resize: none;">{{ old('address') }}</textarea>

                    @error('address')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="number" name="phone" placeholder="No HP / WhatsApp" value="{{ old('phone') }}"
                        class="form-input {{ $errors->has('phone') ? 'input-error' : '' }}">

                    @error('phone')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="form-input {{ $errors->has('email') ? 'input-error' : '' }}">

                    @error('email')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="text" name="instagram" placeholder="Instagram (Not Required)"
                        value="{{ old('instagram') }}" class="form-input">

                    <input type="text" name="occupation" placeholder="Pekerjaan / Profesi"
                        value="{{ old('occupation') }}"
                        class="form-input {{ $errors->has('occupation') ? 'input-error' : '' }}">

                    @error('occupation')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <select name="shirt_size" class="form-input {{ $errors->has('shirt_size') ? 'input-error' : '' }}">

                        @error('shirt_size')
                            <div class="error-text">

                                {{ $message }}

                            </div>
                        @enderror

                        <option value="">
                            Pilih Ukuran Kemeja / Kaos
                        </option>

                        <option>XXS</option>
                        <option>XS</option>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                        <option>XXL</option>
                        <option>XXXL</option>

                    </select>

                </div>

                <br><br>

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Data Kendaraan
                </h2>

                <div style="display:grid;gap:15px;">

                    <input type="text" name="vehicle_model" placeholder="Model Kendaraan"
                        value="{{ old('vehicle_model') }}"
                        class="form-input {{ $errors->has('vehicle_model') ? 'input-error' : '' }}">

                    @error('vehicle_model')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                    <input type="number" name="vehicle_year" placeholder="Tahun Pembuatan"
                        value="{{ old('vehicle_year') }}"
                        class="form-input {{ $errors->has('vehicle_year') ? 'input-error' : '' }}">

                    @error('vehicle_year')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="text" name="vehicle_color" placeholder="Nomor Rangka / VIN"
                        value="{{ old('vehicle_color') }}"
                        class="form-input {{ $errors->has('vehicle_color') ? 'input-error' : '' }}">

                    @error('vehicle_color')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                    <input type="text" name="license_plate" placeholder="Nomor Polisi"
                        value="{{ old('license_plate') }}"
                        class="form-input {{ $errors->has('license_plate') ? 'input-error' : '' }}">

                    @error('license_plate')
                        <div class="error-text">

                            {{ $message }}

                        </div>
                    @enderror

                </div>

                <br><br>

                <button type="submit" class="btn-primary"
                    style="
                    width:100%;
                    border:none;
                    cursor:pointer;
                    font-size:16px;
                ">
                    Kirim Pendaftaran
                </button>

            </form>

        </div>

    </div>

    <style>
        .form-input {

            width: 100%;

            padding: 14px;

            border-radius: 10px;

            border: 1px solid #333;

            background: #1a1a1a;

            color: white;

            font-size: 15px;
        }

        .form-input:focus {

            outline: none;

            border-color: #00e5ff;
        }

        /* khusus input date biar konsisten di dark mode */
        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
            opacity: 0.8;
        }

        .date-input::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        /* Chrome, Safari, Edge, Opera */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .input-error {

            border: 2px solid #ff4d4d !important;

        }

        .error-text {

            color: #ff6b6b;

            font-size: 13px;

            margin-top: -8px;

            margin-bottom: 5px;

        }
    </style>
@endsection
