<!DOCTYPE html>
<html lang="id">

<head>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AMG Admin Panel</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0f0f0f;
            color: white;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {

            width: 260px;

            background: #111;

            border-right: 1px solid #222;

            padding: 20px;
        }

        .sidebar h2 {

            color: #00e5ff;

            margin-bottom: 30px;
        }

        .sidebar-label {
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0 8px 0;
            padding-left: 12px;
        }

        .sidebar a {

            display: block;

            color: white;

            text-decoration: none;

            padding: 12px;

            margin-bottom: 4px;

            border-radius: 8px;
        }

        .sidebar a:hover {

            background: #1d1d1d;
        }

        .sidebar a.active {
            background: #1d1d1d;
            color: #00e5ff;
        }

        .content {

            flex: 1;

            padding: 30px;
        }

        .card {

            background: #161616;

            border: 1px solid #222;

            border-radius: 14px;

            padding: 20px;
        }

        .logout-btn {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            background: #ff4d4d;

            color: white;
        }

        @media(max-width:768px) {

            .wrapper {

                flex-direction: column;
            }

            .sidebar {

                width: 100%;
            }
        }
    </style>

</head>

@stack('scripts')

<body>

    <div class="wrapper">

        <aside class="sidebar">

            <h2>
                AMG Admin
            </h2>

            <div class="sidebar-label">Utama</div>

            <a href="/admin">
                Dashboard
            </a>

            <div class="sidebar-label">Manajemen</div>

            <a href="/admin/registrations">
                Pendaftaran
            </a>

            <a href="/admin/media-registrations">
                Pendaftaran Media
            </a>

            <a href="/admin/media-events">
                Acara Media
            </a>

            <a href="/admin/events">
                Acara
            </a>

            <a href="/admin/scan">
                Scan Member
            </a>

            <a href="/admin/scan-media">
                Scan Media
            </a>

            <div class="sidebar-label">Lainnya</div>

            <a href="/admin/registrations-trash">
                Trash Bin
            </a>

            <a href="/admin/media-registrations-trash">
                Trash Bin Media
            </a>

            <a href="/admin/activity-logs">
                Activity Logs
            </a>

            <a href="/admin/content">
                Homepage CMS
            </a>

            <br>

            <form method="POST" action="/admin/logout">

                @csrf

                <button class="logout-btn" type="submit">
                    Logout
                </button>

            </form>

        </aside>

        <main class="content">

            @yield('content')

        </main>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    @stack('scripts')

</body>

</html>
