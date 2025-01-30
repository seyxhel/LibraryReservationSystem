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
                    Welcome, ADMIN.
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
                                <h2 class="topic-heading">891</h2>
                                <h2 class="topic">Total Reserved Books</h2>
                            </div>
                            <img src= "{{ asset('assets/08-bookmark.png') }}"
                                alt="Views">
                        </div>
                        <div class="box box2">
                            <div class="text">
                                <h2 class="topic-heading">1350</h2>
                                <h2 class="topic">Total of Books Available</h2>
                            </div>
                            <img src= "{{ asset('assets/11-available.png') }}" alt="published">
                        </div>
                    </div>

                    <!-- Graph Container -->
                    <div class="graph-container">
                        <div class="chart container">
                            <canvas id="bookReservationsChart"></canvas> <!-- Placeholder for the graph -->
                        </div>
                    </div>
                </div>

                    <!-- Main Content (List of Research Books) -->
                <div class="main-content">
                    <div class="table-container">             
                        <h1 class="title-Header">List of All Research Books</h1>
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
        const ctx = document.getElementById('bookReservationsChart').getContext('2d');
        
        // Create vibrant primary gradient
        const primaryGradient = ctx.createLinearGradient(0, 0, 0, 400);
        primaryGradient.addColorStop(0, 'rgba(255, 107, 107, 0.8)');    // Coral red
        primaryGradient.addColorStop(0.5, 'rgba(255, 159, 67, 0.8)');   // Orange
        primaryGradient.addColorStop(1, 'rgba(255, 205, 86, 0.8)');     // Yellow

        // Create hover gradient
        const hoverGradient = ctx.createLinearGradient(0, 0, 0, 400);
        hoverGradient.addColorStop(0, 'rgba(255, 107, 107, 1)');       // Brighter coral
        hoverGradient.addColorStop(0.5, 'rgba(255, 159, 67, 1)');      // Brighter orange
        hoverGradient.addColorStop(1, 'rgba(255, 205, 86, 1)');        // Brighter yellow

        // Secondary gradient for lower values
        const secondaryGradient = ctx.createLinearGradient(0, 0, 0, 400);
        secondaryGradient.addColorStop(0, 'rgba(46, 213, 115, 0.8)');   // Green
        secondaryGradient.addColorStop(0.5, 'rgba(86, 203, 249, 0.8)'); // Blue
        secondaryGradient.addColorStop(1, 'rgba(68, 189, 255, 0.8)');   // Light blue

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Book Reservations',
                    data: [450, 580, 690, 850, 780, 600, 520, 750, 820, 900, 850, 780],
                    backgroundColor: function(context) {
                        const index = context.dataIndex;
                        const value = context.dataset.data[index];
                        return value > 700 ? primaryGradient : secondaryGradient;
                    },
                    hoverBackgroundColor: hoverGradient,
                    borderWidth: 2,
                    borderColor: function(context) {
                        return context.active ? 'rgba(255, 255, 255, 0.8)' : 'rgba(0, 0, 0, 0.1)';
                    },
                    borderRadius: 8,
                    barPercentage: 0.75,
                    transition: 'all 0.3s ease'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Book Reservations (2024)',
                        font: {
                            size: 22,
                            weight: 'bold',
                            family: 'Arial'
                        },
                        padding: 20,
                        color: '#2c3e50'
                    },
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleFont: {
                            size: 16
                        },
                        bodyFont: {
                            size: 14
                        },
                        padding: 15,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `📚 ${context.parsed.y} books reserved`;
                            }
                        },
                        animation: {
                            duration: 150
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1000,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            stepSize: 200,
                            callback: function(value) {
                                return value + ' 📚';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#2c3e50'
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart',
                    delay: function(context) {
                        return context.dataIndex * 100;
                    }
                },
                onHover: (event, elements) => {
                    event.native.target.style.cursor = elements[0] ? 'pointer' : 'default';
                }
            }
        });
    </script>
</body>
</html>
