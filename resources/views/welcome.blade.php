<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniArchive</title>
    <!-- Link to the CSS -->
    <link rel="stylesheet" href="{{ asset('css/UNIARCHIVE-destination.css') }}">
</head>
<body>
    <div class="container">
        <div class="left-section"></div>
        <div class="right-section">
            <div class="logo">
                <img src="{{ asset('assets/UNIARCHIVE2.png') }}" alt="UniArchive Logo" style="height: 130px; margin-bottom: 10px;">
            </div>
            <div class="welcome-message">Welcome, Researchers!</div>
            <div class="instruction">
                <span class="arrow">&#x2193;</span>
                <span>Please choose your destination.</span>
            </div>
            <div class="buttons">
                <button class="button admin" onclick="window.location.href='{{ route('admin.login') }}'">FACULTY</button>
                <button class="button student" onclick="window.location.href='{{ route('student.welcome') }}'">STUDENT</button>
            </div>
        </div>
    </div>
</body>
</html>
