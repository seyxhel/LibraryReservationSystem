<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1.0">
    <title>UNIARCHIVE - Reservation Handling</title>
    <link rel="stylesheet" 
          href="{{ asset('css/ADMIN-ReservationHandling3.css') }}">
    <link rel="stylesheet" []
          href="{{ asset('css/AA-Responsive-ReservationHandling.css') }}">
    <link rel="stylesheet" []
            href="{{ asset('css/CC-ReserHanTable.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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

        <div class="userman">
            <div class="user_name" id="userName">
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
                <div class="nav-upper-options">
                    <div class="nav-option option1" id="dashboard">
                        <img src="{{ asset('assets/04-dashboard.png') }}"
                            class="nav-img"
                            alt="dashboard">
                        <h3>Dashboard</h3>
                    </div>
    
                    <script>
                        // Add event listener to redirect when clicked
                        document.getElementById('dashboard').addEventListener('click', function() {
                            window.location.href = "{{ route('admin.dashboard') }}";
                        });
                    </script>

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

                    <div class="nav-option option3">
                        <img src= "{{ asset('assets/06-reservation.png') }}"
                            class="nav-img" 
                            alt="reserved">
                        <h3>
                            Reservation Handling
                        </h3>
                    </div>

                    <div class="nav-option option4" onclick="window.location.href='{{ route('reports.page') }}'">
                        <img src="{{ asset('assets/07-reports.png') }}" class="nav-img" alt="institution">
                        <h3>Reports</h3>
                    </div>
          
                    
                    <div class="option5 nav-option" id="book-inventory">
                        <img src="{{ asset('assets/05-user management.png') }}"
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

        <div class="main">
            <!--BODY CONTENT PAGE-->
            <div class="dashboard-title">
                RESERVATION HANDLING
            <div class="date-time">
                <span id="current-date"></span>
                <span id="current-time"></span>
            </div>
        </div>
            
            <div class="main-content">
                <div class="table-container">
                    <div class="filter-container">
                        <div class="filter-bar">
                            <label for="roles">Sort by:</label>
                            <select id="roles">
                                <option>Ascending</option>
                                <option>Descending</option>
                            </select>
                            <label for="roles">Date Reserved:</label>
                            <select id="status">
                                <option>Date Added</option>
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option>This Month</option>
                                <option>Last Month</option>
                            </select>
                        </div>
                        
                        <!-- RESERVATION TABLE CONTENTS-->
                                        
                            <table class="reservation-table" id="bookTable">
                                <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Borrowed Book</th>
                                    <th>Reservation Date</th>
                                    <th>Book Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                <tr>
                                    <td>John Doe</td>
                                    <td>Book 1</td>
                                    <td>01-01-2024</td>
                                    <td>0001</td>
                                    <td><button class="confirm-btn" onclick="confirm(this)">Confirm Reservation</button>
                                    <td><button class="confirm-btn" onclick="cancel(this)">Cancel Reservation</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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

        // Confirm Reservation Alert
        function confirm(button) {
        alert('Book Reservation Confirmed!');
        button.disabled = true;
    }
        // Cancel Reservation Alert
        function cancel(button) {
        alert('Book Reservation Cancelled!');
        button.disabled = true;
    }
    </script>
</body>
</html>
