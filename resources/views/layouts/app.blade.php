<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AMG Owners Surabaya</title>

    @php
        $content = \App\Models\HomepageContent::first();
    @endphp

    <style>
        /* ==========================================
           FONT
        ========================================== */

        @font-face {

            font-family: 'NunitoSans';

            src: url('/fonts/NunitoSans/NunitoSans_10pt-Medium.ttf') format('truetype');

            font-weight: 900;

            font-style: normal;

        }

        @font-face {

            font-family: 'NunitoExpanded';

            src: url('/fonts/NunitoSans/NunitoSans_10pt_Expanded-Black.ttf') format('truetype');

            font-weight: 900;

            font-style: normal;

        }

        /* ==========================================
           RESET
        ========================================== */

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }

        body {

            font-family: 'NunitoSans', sans-serif;

            background: #0a0a0a;

            color: #f9f9f9;

            overflow-x: hidden;

        }

        input,
        textarea,
        select,
        button,
        a,
        label,
        span,
        p,
        li,
        td,
        th,
        h2,
        h3,
        h4,
        h5,
        h6 {

            font-family: 'NunitoSans', sans-serif;

        }

        a {

            text-decoration: none;

        }

        /* ==========================================
           HOMEPAGE TITLE
        ========================================== */

        .homepage-title {

            font-family: 'NunitoExpanded', sans-serif;

            font-size: 60px;

            font-weight: 900;

            letter-spacing: 2px;

            text-transform: uppercase;

        }

        /* ==========================================
           HEADER
        ========================================== */

        .navbar {

            position: sticky;

            top: 0;

            z-index: 999;

            height: 85px;

            background: rgba(17, 17, 17, .95);

            backdrop-filter: blur(12px);

            border-bottom: 1px solid #222;

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 0 40px;

        }

        .navbar-left,
        .navbar-center,
        .navbar-right {

            display: flex;

            align-items: center;

        }

        .navbar-left {

            width: 220px;

            justify-content: flex-start;

        }

        .navbar-center {

            flex: 1;

            justify-content: center;

        }

        .navbar-right {

            width: 220px;

            justify-content: flex-end;

        }

        /* ==========================================
           LOGO
        ========================================== */

        .header-logo {

            max-height: 60px;

            max-width: 180px;

            width: auto;

            height: auto;

            object-fit: contain;

            transition: .3s;

        }

        .header-logo:hover {

            transform: scale(1.05);

        }

        /* ==========================================
           MENU
        ========================================== */

        .menu {

            display: flex;

            gap: 45px;

        }

        .menu a {

            color: white;

            font-size: 16px;

            font-weight: 900;

            transition: .3s;

            position: relative;

        }

        .menu a::after {

            content: "";

            position: absolute;

            left: 0;

            bottom: -6px;

            width: 0;

            height: 2px;

            background: #00e5ff;

            transition: .3s;

        }

        .menu a:hover {

            color: #00e5ff;

        }

        .menu a:hover::after {

            width: 100%;

        }

        /* ==========================================
           CONTENT
        ========================================== */

        .container {

            width: 100%;

            min-height: calc(100vh - 170px);

        }

        /* ==========================================
           BUTTON
        ========================================== */

        .btn-primary {

            display: inline-block;

            padding: 14px 28px;

            border-radius: 8px;

            background: #00e5ff;

            color: black;

            font-weight: 900;

            transition: .3s;

        }

        .btn-primary:hover {

            opacity: .9;

            transform: translateY(-2px);

        }

        /* ==========================================
           FOOTER
        ========================================== */

        .footer {

            padding: 20px 30px;

            background: #111;

            border-top: 1px solid #222;

            text-align: center;

            line-height: 28px;

            color: #ccc;

            font-size: 14px;

        }

        .footer-partner {

            margin-bottom: 15px;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

        }

        .partner-title {

            font-size: 15px;

            font-weight: 700;

            margin-bottom: 8px;

            color: #ffffff;

        }

        .footer-partner-logo {

            max-width: 240px;

            width: 100%;

            max-height: 90px;

            object-fit: contain;

        }

        .footer-copy {

            margin-bottom: 6px;

            line-height: 1.4;

        }

        .footer-address {

            color: #bdbdbd;

            line-height: 1.4;

        }

        /* ==========================================
           RESPONSIVE
        ========================================== */

        @media (max-width:768px) {

            .navbar {

                flex-direction: column;

                height: auto;

                padding: 20px;

                gap: 20px;

            }

            .navbar-left,
            .navbar-center,
            .navbar-right {

                width: 100%;

                justify-content: center;

            }

            .menu {

                gap: 25px;

                flex-wrap: wrap;

            }

            .header-logo {

                max-height: 55px;

            }

            .homepage-title {

                font-size: 36px;

                letter-spacing: 1px;

            }

        }
    </style>

</head>

<body>

    <nav class="navbar">

        <!-- Logo Kiri -->
        <div class="navbar-left">

            @if ($content && $content->logo)
                <img src="{{ asset('storage/' . $content->logo) }}" class="header-logo" alt="Logo AMG">
            @endif

        </div>

        <!-- Menu -->
        <div class="navbar-center">

            <div class="menu">

                <a href="/">Home</a>

                <a href="/register">Pendaftaran</a>

            </div>

        </div>

        <!-- Logo Kanan -->
        <div class="navbar-right">

        </div>

    </nav>

    <div class="container">

        @yield('content')

    </div>

    <footer class="footer">

        @if ($content && $content->header_logo)
            <div class="footer-partner">

                <div class="partner-title">

                    Supporting Partner:

                </div>

                <img src="{{ asset('storage/' . $content->header_logo) }}" class="footer-partner-logo"
                    alt="Supporting Partner">

            </div>
        @endif

        <div class="footer-copy">

            © {{ date('Y') }} AMG Owners Surabaya. All Rights Reserved.

        </div>

        <div class="footer-address">

            Jl. Demak No.166-168, Gundih,
            Kec. Bubutan,
            Surabaya,
            Jawa Timur 60172

        </div>

    </footer>

</body>

</html>
