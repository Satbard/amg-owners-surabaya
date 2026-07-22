@extends('layouts.admin')

@section('content')

    <h1 style="margin-bottom:20px;">
        Edit Pendaftaran Media
    </h1>

    <a href="/admin/media-registrations"
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

    <form method="POST" action="/admin/media-registrations/{{ $mediaRegistration->id }}">

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
                    Personal Information
                </h2>

                <div class="form-group">

                    <label>Full Name</label>

                    <input type="text" name="full_name" value="{{ old('full_name', $mediaRegistration->full_name) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Media Name</label>

                    <input type="text" name="media_name" value="{{ old('media_name', $mediaRegistration->media_name) }}"
                        class="form-input">

                </div>

                {{-- Position (Multi-select checkboxes) --}}
                <div class="form-group">

                    <label>Position</label>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">

                        @php
                            $positions = ['Photographer', 'Videographer', 'Journalist', 'Content Creator', 'Others'];
                            $selectedPositions = old(
                                'position',
                                is_array($mediaRegistration->position) ? $mediaRegistration->position : [],
                            );
                        @endphp

                        @foreach ($positions as $pos)
                            <label
                                style="
                                display:flex;
                                align-items:center;
                                gap:6px;
                                cursor:pointer;
                                padding:8px 14px;
                                background: {{ in_array($pos, $selectedPositions) ? '#00e5ff' : '#1d1d1d' }};
                                color: {{ in_array($pos, $selectedPositions) ? 'black' : 'white' }};
                                border-radius:6px;
                                border:1px solid #333;
                                font-size:14px;
                            ">
                                <input type="checkbox" name="position[]" value="{{ $pos }}"
                                    {{ in_array($pos, $selectedPositions) ? 'checked' : '' }} style="display:none;"
                                    onchange="this.parentElement.style.background=this.checked?'#00e5ff':'#1d1d1d';this.parentElement.style.color=this.checked?'black':'white';">
                                {{ $pos }}
                            </label>
                        @endforeach

                    </div>

                </div>

                <div class="form-group">

                    <label>Mobile Number (WhatsApp)</label>

                    <input type="tel" name="phone" value="{{ old('phone', $mediaRegistration->phone) }}"
                        class="form-input" oninput="this.value = this.value.replace(/[^0-9+]/g, '')">

                </div>

                <div class="form-group">

                    <label>Email Address</label>

                    <input type="email" name="email" value="{{ old('email', $mediaRegistration->email) }}"
                        class="form-input">

                </div>

            </div>

            <div class="card">

                <h2 style="
                color:#00e5ff;
                margin-bottom:20px;
            ">
                    Media Information
                </h2>

                <div class="form-group">

                    <label>Social Media Account</label>

                    <input type="text" name="social_media"
                        value="{{ old('social_media', $mediaRegistration->social_media) }}" class="form-input">

                </div>

                <div class="form-group">

                    <label>Number of Followers / Subscribers (Optional)</label>

                    <input type="text" name="followers" value="{{ old('followers', $mediaRegistration->followers) }}"
                        class="form-input">

                </div>

                <div class="form-group">

                    <label>Media Type</label>

                    <select name="media_type" class="form-input">

                        @foreach (['Print', 'Online', 'TV', 'Radio', 'Digital Creator', 'Community Media', 'Others'] as $type)
                            <option value="{{ $type }}"
                                {{ old('media_type', $mediaRegistration->media_type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <hr style="margin:25px 0;border-color:#222;">

                <h2 style="
                color:#00e5ff;
                margin-bottom:15px;
            ">
                    Competition Registration
                </h2>

                <div class="form-group">

                    <label>Competition Category</label>

                    <select name="competition_category" class="form-input">

                        @foreach (['Photography', 'Videography / Reels'] as $cat)
                            <option value="{{ $cat }}"
                                {{ old('competition_category', $mediaRegistration->competition_category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="form-group">

                    <label>Equipment Used</label>

                    <select name="equipment_used" class="form-input">

                        @php
                            $equipmentOptions = [
                                'Camera' => 'Camera',
                                'Smartphone' => 'Smartphone',
                                'Drone' => 'Drone (Subject to Organizer Approval)',
                            ];
                        @endphp
                        @foreach ($equipmentOptions as $eqVal => $eqLabel)
                            <option value="{{ $eqVal }}"
                                {{ old('equipment_used', $mediaRegistration->equipment_used) == $eqVal ? 'selected' : '' }}>
                                {{ $eqLabel }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <hr style="margin:25px 0;border-color:#222;">

                <h2 style="
                color:#00e5ff;
                margin-bottom:15px;
            ">
                    Status
                </h2>

                <select name="status" class="form-input">

                    <option value="Pending" {{ old('status', $mediaRegistration->status) == 'Pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="Approved"
                        {{ old('status', $mediaRegistration->status) == 'Approved' ? 'selected' : '' }}>
                        Approved
                    </option>

                    <option value="Rejected"
                        {{ old('status', $mediaRegistration->status) == 'Rejected' ? 'selected' : '' }}>
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
