<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin — AMG Owners Surabaya</title>

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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Brand Header */
        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand h1 {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #00e5ff;
            text-transform: uppercase;
        }

        .brand p {
            color: #888;
            font-size: 14px;
            margin-top: 6px;
        }

        /* Card */
        .login-card {
            background: #161616;
            border: 1px solid #222;
            border-radius: 14px;
            padding: 36px 32px 32px;
        }

        .login-card h2 {
            font-size: 20px;
            margin-bottom: 24px;
            text-align: center;
            color: #f0f0f0;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #ccc;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #00e5ff;
            box-shadow: 0 0 0 3px rgba(0, 229, 255, 0.12);
        }

        .form-group input.input-error {
            border-color: #ff4d4d;
        }

        .form-group input::placeholder {
            color: #666;
        }

        /* Error Message */
        .error-box {
            background: rgba(198, 40, 40, 0.15);
            border: 1px solid #c62828;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-box span {
            color: #ff6b6b;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Submit Button */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: #00e5ff;
            color: black;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
        }

        .login-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }

        .login-footer a {
            color: #666;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #00e5ff;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 28px 20px 24px;
            }

            .brand h1 {
                font-size: 22px;
            }
        }
    </style>

</head>

<body>

    <div class="login-wrapper">

        <!-- Brand -->
        <div class="brand">
            <h1>AMG</h1>
            <p>Owners Surabaya — Admin Panel</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">

            <h2>Masuk</h2>

            @if ($errors->any())
                <div class="error-box">
                    <span>⚠️ {{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="/admin/login">

                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username"
                        value="{{ old('username') }}" class="{{ $errors->has('username') ? 'input-error' : '' }}"
                        autofocus autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password"
                        class="{{ $errors->has('password') ? 'input-error' : '' }}" autocomplete="current-password">
                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>

            </form>

        </div>

        <div class="login-footer">
            <a href="/">← Kembali ke Beranda</a>
        </div>

    </div>

</body>

</html>
