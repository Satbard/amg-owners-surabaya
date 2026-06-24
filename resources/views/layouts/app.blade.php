<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AMG Owners Surabaya</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0a0a0a;
            color: rgb(249, 249, 249);
        }

        a {
            text-decoration: none;
        }

        .navbar {

            position: sticky;
            top: 0;

            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 20px 40px;

            background: #111;

            border-bottom: 1px solid #222;

            z-index: 999;
        }

        .logo {

            font-size: 22px;
            font-weight: bold;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            color: #00e5ff;
        }

        .menu {

            display: flex;
            gap: 20px;
        }

        .menu a {

            color: white;
            transition: .3s;
        }

        .menu a:hover {

            color: #00e5ff;
        }

        .container {

            width: 100%;
        }

        .btn-primary {

            display: inline-block;

            padding: 14px 28px;

            border-radius: 8px;

            background: #00e5ff;

            color: black;

            font-weight: bold;
        }

        .btn-primary:hover {

            opacity: .9;
        }

        .footer {

            padding: 30px;

            text-align: center;

            border-top: 1px solid #222;

            background: #111;
        }

        @media(max-width:768px) {

            .navbar {

                flex-direction: column;

                gap: 15px;
            }

            .menu {

                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>

</head>

<body>

    <nav class="navbar">

        <div class="logo">

            AMG Owners Surabaya

        </div>

        <div class="menu">

            <a href="/">
                Home
            </a>

            <a href="/register">
                Pendaftaran
            </a>

        </div>

    </nav>

    <div class="container">

        @yield('content')

    </div>

    <footer class="footer">

        AMG Owners Surabaya © {{ date('Y') }}

        <br>

        Jl. Demak No.166-168, Gundih, Kec. Bubutan, Surabaya, Jawa Timur 60172

    </footer>

</body>

</html>
