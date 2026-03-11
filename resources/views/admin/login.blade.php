<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Cormorant+Garamond:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
</head>

<body>
    <div class="login-wrap">
        <div class="login-brand">
            <h1>Admin Panel</h1>
            <p>Restricted Access</p>
        </div>

        <div class="login-card">
            @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@store.com" required
                        autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="submit-btn">Sign In to Admin</button>
            </form>
        </div>

        <div class="login-footer">
            <a href="{{ route('home') }}">← Back to Store</a>
        </div>

        <p class="powered">Powered by <span>brndng.</span></p>
    </div>
</body>

</html>