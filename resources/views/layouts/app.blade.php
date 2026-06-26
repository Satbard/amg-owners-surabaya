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
        * {

            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        body {

            font-family: Arial, Helvetica, sans-serif;

            background: #0a0a0a;

            color: #f9f9f9;

        }

        a {

            text-decoration: none;

        }

        /* ==========================
            HEADER
        ========================== */

        .navbar {

            position: sticky;

            top: 0;

            z-index: 999;

            height: 85px;

            background: #111;

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

        /* ==========================
            LOGO
        ========================== */

        .header-logo {

            max-height: 60px;

            max-width: 180px;

            width: auto;

            object-fit: contain;

        }

        /* ==========================
            MENU
        ========================== */

        .menu {

            display: flex;

            gap: 45px;

        }

        .menu a {

            color: white;

            font-size: 16px;

            font-weight: bold;

            transition: .3s;

        }

        .menu a:hover {

            color: #00e5ff;

        }

        /* ==========================
            CONTENT
        ========================== */

        .container {

            width: 100%;

            min-height: calc(100vh - 170px);

        }

        /* ==========================
            BUTTON
        ========================== */

        .btn-primary {

            display: inline-block;

            padding: 14px 28px;

            border-radius: 8px;

            background: #00e5ff;

            color: black;

            font-weight: bold;

            transition: .3s;

        }

        .btn-primary:hover {

            opacity: .9;

        }

        /* ==========================
            FOOTER
        ========================== */

        .footer {

            padding: 30px;

            background: #111;

            border-top: 1px solid #222;

            text-align: center;

            line-height: 28px;

            color: #ccc;

        }

        /* ==========================
            RESPONSIVE
        ========================== */

        @media(max-width:768px) {

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

                <a href="/">

                    Home

                </a>

                <a href="/register">

                    Pendaftaran

                </a>

            </div>

        </div>

        <!-- Logo Kanan -->

        <div class="navbar-right">

            @if ($content && $content->header_logo)
                <img src="{{ asset('storage/' . $content->header_logo) }}" class="header-logo" alt="Header Logo">
            @endif

        </div>

    </nav>

    <div class="container">

        @yield('content')

    </div>

    <footer class="footer">

        <strong>AMG Owners Surabaya</strong>

        <br>

        Jl. Demak No.166-168, Gundih, Kec. Bubutan,
        Surabaya, Jawa Timur 60172

        <br><br>

        © {{ date('Y') }} AMG Owners Surabaya.
        All Rights Reserved.

    </footer>

</body>

</html>
