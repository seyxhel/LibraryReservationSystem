<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="{{ asset('css/ADMIN-Login.css') }}">
</head>
<body>
    <div class="page-wrapper">
        <div class="login-container">
            <div class="logo-section">
                <img src="{{ asset('assets/16-admin-login.png') }}" alt="UNIARCHIVE Logo" class="logo">
            </div>

            <div class="form-wrapper">
                <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
                    @csrf <!-- CSRF token for security -->

                    <div class="form-group">
                        <label for="email" class="form-label">Email:</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-control"
                                size="35" 
                                maxlength="50" 
                                required 
                                autofocus 
                                value="{{ old('email') }}"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password:</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-control"
                                size="35" 
                                maxlength="255" 
                                required
                            >
                        </div>
                    </div>

                    <!-- Error Handling -->
                    @if ($errors->any())
                        <div class="error-container">
                            <strong class="error-message">{{ $errors->first() }}</strong>
                        </div>
                    @endif

                    <div class="button-container">
                        <button type="submit" class="login-btn">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
