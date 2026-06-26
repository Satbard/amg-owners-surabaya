@extends('layouts.app')

@section('content')
    <div
        style="
        min-height:85vh;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        text-align:center;
        padding:40px;
        position:relative;
        overflow:hidden;

        @if ($content->background) background-image:
                linear-gradient(
                    rgba(0,0,0,.55),
                    rgba(0,0,0,.55)
                ),
                url('{{ asset('storage/' . $content->background) }}');

            background-size:cover;

            background-position:center;

            background-repeat:no-repeat;

            background-attachment:fixed;

        @else

            background:#111; @endif
    ">

        <div style="
            max-width:900px;
            z-index:2;
        ">

            <h1 class="homepage-title"
                style="
                margin-bottom:25px;
                color:white;
                text-shadow:
                    0 3px 15px rgba(0,0,0,.6);
            ">
                {{ $content->title }}
            </h1>

            <p
                style="
                font-size:16px;
                line-height:1.8;
                color:#f5f5f5;
                margin-bottom:45px;
                text-shadow:
                    0 2px 10px rgba(0,0,0,.6);
            ">
                {{ $content->description }}
            </p>

            <a href="/register" class="btn-primary"
                style="
                font-size:18px;
                padding:16px 36px;
            ">
                {{ $content->button_text }}
            </a>

        </div>

    </div>

    <style>
        @media (max-width:768px) {

            .homepage-title {

                font-size: 38px !important;

                line-height: 1.2;

            }

        }
    </style>
@endsection
