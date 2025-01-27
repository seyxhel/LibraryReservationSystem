<!DOCTYPE html>
<html>
<head>
    <title>UNIARCHIVE | Login</title>
    <link rel="stylesheet" href="{{ asset('css/Student.SignInPage.css') }}">
</head>
<body>

    <div class="login-box">
        <div class="login-header">
            <img src="{{ asset('assets/UNIARCHIVE.TRANS.png') }}" class="logo">
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('student.login.post') }}">
            @csrf
            <div class="input-box">
                @error('email')
                <p class="error-message" style="font-size: 12px; margin-left:20px; font-family: Arial; color: white;">{{ $message }}</p>
                @enderror
                <input type="email" name="email" class="input-field" placeholder="E-mail:" autocomplete="off" required>
                <i class="bx bx-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password:" autocomplete="off" required>
                <i class="bx bx-lock"></i>
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Error Message for Login -->
            @if ($errors->has('login'))
            <p class="error-message">{{ $errors->first('login') }}</p>
            @endif
            <div class="input-submit">
                <button type="submit" class="submit-btn">Log In</button>
            </div>
        </form>

        <!-- Sign Up Link -->
        <div class="sign-up-link">
            <p>Don't have an account? <a href="{{ route('student.signup') }}">Sign Up</a></p>
        </div>
    </div>

</body>
</html>
