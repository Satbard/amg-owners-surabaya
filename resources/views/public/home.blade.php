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

        @if($content->background)
            background-image:url('{{ asset('storage/'.$content->background) }}');
            background-size:cover;
            background-position:center;
        @endif
    "
>

    @if($content->logo)

        <img
            src="{{ asset('storage/'.$content->logo) }}"
            alt="Logo"
            style="
                max-width:300px;
                margin-bottom:30px;
            "
        >

    @endif

    <h1
        style="
            font-size:52px;
            margin-bottom:20px;
        "
    >
        {{ $content->title }}
    </h1>

    <p
        style="
            max-width:800px;
            font-size:20px;
            margin-bottom:40px;
        "
    >
        {{ $content->description }}
    </p>

    <a
        href="/register"
        class="btn-primary"
    >
        {{ $content->button_text }}
    </a>

</div>

@endsection