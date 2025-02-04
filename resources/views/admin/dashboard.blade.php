<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">
    <title>UNIARCHIVE - ADMIN Home</title>
    <!-- In your blade template (e.g., resources/views/layouts/app.blade.php) -->

    <!-- CSS files -->
    <link rel="stylesheet" href="{{ asset('css/ADMIN-Dashboard1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CC-Dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AA-Responsive-Dashboard.css') }}">

    <!-- Scripts -->
    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        .chart-container {
                width: 800px;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 12px;
                background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
        .chart-container:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

    <!-- for header part -->
    <header>
        <div class="logosec">
            <div class="logo">UNIARCHIVE</div>
            <img src= "{{ asset('assets/01-menu.png') }}"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>

        <div class="dropdown">
            <img src= "{{ asset('assets/03-user.png') }}"
                class="dpicn"
                alt="dp">
          <div class="dropdown-content">
              <a href="{{ route('admin.profile') }}">Edit Account</a>
              <a href="#" id="logout-link">Log Out</a>
                    </div>
                </div>

                <!-- Separate Logout Form -->
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <script>
                    // Handle the Logout functionality
                    document.getElementById('logout-link').addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent default link behavior
                        document.getElementById('logout-form').submit(); // Submit the hidden logout form
                    });
                </script>
        </div>
    </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-option option1">
                    <img src="{{ asset('assets/04-dashboard.png') }}"
                        class="nav-img"
                        alt="dashboard">
                    <h3> Dashboard</h3>
                </div>

                <div class="option2 nav-option" id="user-management">
                    <img src="{{ asset('assets/05-user management.png') }}"
                        class="nav-img"
                        alt="User Management">
                    <h3>User Management</h3>
                </div>

                <script>
                    document.getElementById('user-management').addEventListener('click', function() {
                        window.location.href = "{{ route('admin.user-management') }}";
                    });
                </script>

                <div class="nav-option option3" onclick="window.location.href='{{ route('reservation-handling.page') }}'">
                    <img src="{{ asset('assets/06-reservation.png') }}" class="nav-img" alt="report">
                    <h3> Reservation Handling</h3>
                </div>

                <div class="nav-option option4" onclick="window.location.href='{{ route('reports.page') }}'">
                    <img src="{{ asset('assets/07-reports.png') }}" class="nav-img" alt="institution">
                    <h3>Reports</h3>
                </div>

                <div class="option5 nav-option" id="book-inventory">
                    <img src="{{ asset('assets/13-inventory.png') }}"
                        class="nav-img"
                        alt="Book Inventory">
                    <h3>Book Inventory</h3>
                </div>

                <script>
                    document.getElementById('book-inventory').addEventListener('click', function() {
                        window.location.href = "{{ route('admin.book-inventory') }}";
                    });
                </script>
            </nav>
        </div>

        <!--BODY CONTENT PAGE-->
        <div class="main">
            <div class="dashboard-title">
                    Welcome, {{ Auth::guard('admin')->user()->FirstName }}.
                <div class="date-time">
                    <span id="current-date"></span>
                    <span id="current-time"></span>
                </div>
            </div>

            <div class="dashboard-layout"></div>
                <div class="stats-graph-container">
                    <div class="box-container">
                        <div class="box box1">
                            <div class="text">
                                <h2 class="topic-heading">{{ $totalReservedBooks }}</h2>
                                <h2 class="topic">Total Reserved Books</h2>
                            </div>
                            <img src= "{{ asset('assets/08-bookmark.png') }}"
                                alt="Views">
                        </div>
                        <div class="box box2">
                            <div class="text">
                                <h2 class="topic-heading">{{ $totalAvailableBooks }}</h2>
                                <h2 class="topic">Total of Books Available</h2>
                            </div>
                            <img src= "{{ asset('assets/11-available.png') }}" alt="published">
                        </div>
                    </div>

                    <!-- Graph Container -->
                    <div class="graph-container">
                        <div class="chart container">
                            <canvas id="bookReservationsChart"></canvas>
                        </div>
                    </div>
                </div>
                    <!-- Main Content (List of Research Books) -->
                <div class="main-content">
                    <div class="table-container">
                        <h1 class="title-Header">List of All Research Book Categories</h1>
                        <div class="scrollable-list">
                            <ul class="research-list">
                                <li>Descriptive Studies</li>
                                <li>Exploratory Studies</li>
                                <li>Explanatory (Causal) Studies</li>
                                <li>Evaluative Studies</li>
                                <li>Qualitative Research</li>
                                <li>Quantitative Research</li>
                                <li>Mixed-Methods Research</li>
                                <li>Theoretical Studies</li>
                                <li>Applied Studies</li>
                                <li>Empirical Studies</li>
                                <li>Cross-Sectional Studies</li>
                                <li>Longitudinal Studies</li>
                                <li>Primary Research</li>
                                <li>Secondary Research</li>
                                <li>Social Sciences Research</li>
                                <li>Natural Sciences Research</li>
                                <li>Business and Management Research</li>
                                <li>Technology Research</li>
                                <li>Experimental Research</li>
                                <li>Non-Experimental Research</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
    <script>
        function updateDateTime() {
            const now = new Date();

            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('en-US', options);

            // Format the time
            const time = now.toLocaleTimeString('en-US');

            document.getElementById('current-date').textContent = date;
            document.getElementById('current-time').textContent = time;
        }

        setInterval(updateDateTime, 1000);
        window.onload = updateDateTime;
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("{{ url('/admin/weekly-reservations') }}")
                .then(response => response.json())
                .then(data => {
                    const labels = [];
                    const reservationsData = [];

                    data.forEach(entry => {
                        labels.push(entry.date);
                        reservationsData.push(entry.count);
                    });

                    const ctx = document.getElementById('bookReservationsChart').getContext('2d');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Weekly Reservations (Mon-Sat)',
                                data: reservationsData,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Weekly Book Reservations (Monday to Saturday)'
                                },
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        stepSize: 10
                                    }
                                }
                            }
                        }
                    });
                });
        });
    </script>

</body>
</html>
