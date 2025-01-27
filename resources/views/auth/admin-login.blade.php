<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="{{ asset('css/Admin.login.css') }}">
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('assets/UNIARCHIVE LOGO.png') }}" alt="UNIARCHIVE Logo" class="logo">

        <!-- Laravel form for admin authentication -->
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf <!-- CSRF token for security -->

            <!-- Email Input -->
            <p>
                <label for="email">Email:</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    size="35"
                    maxlength="50"
                    required
                    autofocus
                    value="{{ old('email') }}"
                />
                <br>
            </p>

            <!-- Password Input -->
            <p>
                <label for="password">Password:</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    size="35"
                    maxlength="255"
                    required
                />
                <br>
            </p>

            <!-- Error Handling -->
            @if ($errors->any())
                <div class="error-messages">
                    <strong style="color: red;">{{ $errors->first() }}</strong>
                </div>
            @endif

            <!-- Login Button -->
            <p>
                <button type="submit" class="login-btn">Log In</button>
            </p>
        </form>
    </div>
</body>
</html>
