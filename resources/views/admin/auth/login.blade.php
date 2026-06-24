<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

<h2>Login Admin</h2>

@if ($errors->any())
    <p>{{ $errors->first() }}</p>
@endif

<form method="POST" action="/admin/login">

    @csrf

    <div>
        <label>Username</label>
        <input type="text" name="username">
    </div>

    <br>

    <div>
        <label>Password</label>
        <input type="password" name="password">
    </div>

    <br>

    <button type="submit">
        Login
    </button>

</form>

</body>
</html>