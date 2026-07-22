@extends('layouts.app')

@section('content')
    <div
        style="
        min-height:100vh;

        background-image:url('{{ $content && $content->media_background ? asset('storage/' . $content->media_background) : '' }}');

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
                Pendaftaran Media
            </h1>

            <p
                style="
                text-align:center;
                margin-bottom:40px;
                color:#bbb;
            ">
                Untuk Fotografer, Videografer, Jurnalis, dan Content Creator
            </p>

            <form method="POST" action="/register-media">

                @csrf

                {{-- ============================================= --}}
                {{-- PERSONAL INFORMATION --}}
                {{-- ============================================= --}}

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Personal Information
                </h2>

                <div style="display:grid;gap:15px;">

                    <input type="text" name="full_name" placeholder="Full Name" value="{{ old('full_name') }}"
                        class="form-input {{ $errors->has('full_name') ? 'input-error' : '' }}">

                    @error('full_name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    <input type="text" name="media_name" placeholder="Media Name" value="{{ old('media_name') }}"
                        class="form-input {{ $errors->has('media_name') ? 'input-error' : '' }}">

                    @error('media_name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    {{-- Position (Multi-select checkboxes) --}}
                    <div style="margin-bottom:5px;">
                        <label style="display:block;margin-bottom:8px;color:#ccc;font-size:14px;">
                            Position
                        </label>

                        <div style="display:flex;flex-wrap:wrap;gap:12px;">
                            @php
                                $positions = [
                                    'Photographer',
                                    'Videographer',
                                    'Journalist',
                                    'Content Creator',
                                    'Others',
                                ];
                                $oldPositions = old('position', []);
                            @endphp

                            @foreach ($positions as $pos)
                                <label
                                    style="
                                    display:flex;
                                    align-items:center;
                                    gap:6px;
                                    cursor:pointer;
                                    padding:8px 14px;
                                    background: {{ in_array($pos, (array) $oldPositions) ? '#00e5ff' : '#1a1a1a' }};
                                    color: {{ in_array($pos, (array) $oldPositions) ? 'black' : 'white' }};
                                    border-radius:8px;
                                    border:1px solid #333;
                                    font-size:14px;
                                    transition:.2s;
                                ">
                                    <input type="checkbox" name="position[]" value="{{ $pos }}"
                                        {{ in_array($pos, (array) $oldPositions) ? 'checked' : '' }} style="display:none;"
                                        onchange="this.parentElement.style.background=this.checked?'#00e5ff':'#1a1a1a';this.parentElement.style.color=this.checked?'black':'white';">
                                    {{ $pos }}
                                </label>
                            @endforeach
                        </div>

                        @error('position')
                            <div class="error-text" style="margin-top:8px;">{{ $message }}</div>
                        @enderror
                        @error('position.*')
                            <div class="error-text" style="margin-top:8px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="tel" name="phone" placeholder="Mobile Number (WhatsApp)" value="{{ old('phone') }}"
                        class="form-input {{ $errors->has('phone') ? 'input-error' : '' }}"
                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">

                    @error('phone')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}"
                        class="form-input {{ $errors->has('email') ? 'input-error' : '' }}">

                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                <br><br>

                {{-- ============================================= --}}
                {{-- MEDIA INFORMATION --}}
                {{-- ============================================= --}}

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Media Information
                </h2>

                <div style="display:grid;gap:15px;">

                    <input type="text" name="social_media"
                        placeholder="Social Media Account (Instagram / TikTok / YouTube)" value="{{ old('social_media') }}"
                        class="form-input {{ $errors->has('social_media') ? 'input-error' : '' }}">

                    @error('social_media')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    <input type="text" name="followers" placeholder="Number of Followers / Subscribers (Optional)"
                        value="{{ old('followers') }}" class="form-input">

                    <select name="media_type" class="form-input {{ $errors->has('media_type') ? 'input-error' : '' }}">

                        <option value="">
                            Select Media Type
                        </option>

                        @foreach (['Print', 'Online', 'TV', 'Radio', 'Digital Creator', 'Community Media', 'Others'] as $type)
                            <option value="{{ $type }}" {{ old('media_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach

                    </select>

                    @error('media_type')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                <br><br>

                {{-- ============================================= --}}
                {{-- COMPETITION REGISTRATION --}}
                {{-- ============================================= --}}

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Competition Registration
                </h2>

                <div style="display:grid;gap:15px;">

                    <select name="competition_category"
                        class="form-input {{ $errors->has('competition_category') ? 'input-error' : '' }}">

                        <option value="">
                            Select Competition Category
                        </option>

                        @foreach (['Photography', 'Videography / Reels'] as $cat)
                            <option value="{{ $cat }}"
                                {{ old('competition_category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach

                    </select>

                    @error('competition_category')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                    <select name="equipment_used"
                        class="form-input {{ $errors->has('equipment_used') ? 'input-error' : '' }}">

                        <option value="">
                            Select Equipment Used
                        </option>

                        @foreach (['Camera', 'Smartphone', 'Drone'] as $eq)
                            <option value="{{ $eq }}" {{ old('equipment_used') == $eq ? 'selected' : '' }}>
                                {{ $eq }}
                            </option>
                        @endforeach

                    </select>

                    @error('equipment_used')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                <br><br>

                {{-- ============================================= --}}
                {{-- TERMS & AGREEMENT --}}
                {{-- ============================================= --}}

                <h2 style="
                    color:#00e5ff;
                    margin-bottom:20px;
                ">
                    Terms & Agreement
                </h2>

                <div style="display:grid;gap:12px;">

                    @php
                        $terms = [
                            'I confirm that all submitted information is accurate.',
                            'I agree to follow all event rules and organizer instructions.',
                            'I grant the organizer permission to repost or feature my submitted content with proper credit.',
                        ];
                    @endphp

                    @foreach ($terms as $i => $term)
                        <label
                            style="
                            display:flex;
                            align-items:flex-start;
                            gap:10px;
                            cursor:pointer;
                            padding:12px 16px;
                            background:#1a1a1a;
                            border-radius:8px;
                            border:1px solid #333;
                            font-size:14px;
                            color:#ddd;
                        ">
                            <input type="checkbox" name="terms_agreed" value="1"
                                {{ old('terms_agreed') ? 'checked' : '' }}
                                style="margin-top:2px;width:18px;height:18px;cursor:pointer;accent-color:#00e5ff;">
                            <span>{{ $term }}</span>
                        </label>
                    @endforeach

                    @error('terms_agreed')
                        <div class="error-text">{{ $message }}</div>
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
                    Kirim Pendaftaran Media
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

        .input-error {

            border: 2px solid #ff4d4d !important;
        }

        .error-text {

            color: #ff6b6b;

            font-size: 13px;

            margin-top: -8px;

            margin-bottom: 5px;
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
    </style>
@endsection
