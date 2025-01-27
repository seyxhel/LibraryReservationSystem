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

    

        <div class="searchbar">
            <input type="text" 
                   placeholder="Search">
            <div class="searchbtn">
              <img src="{{ asset('assets/02-search.png') }}"
                    class="icn srchicn" 
                    alt="search-icon">
              </div>
        </div>


            <div class="dp">
              <img src= "{{ asset('assets/03-user.png') }}"
                    class="dpicn" 
                    alt="dp">
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

                    <div class="nav-logout">
                        <div class="nav-option logout">
                            <img src="{{ asset('assets/12-logout.png') }}" class="nav-img" alt="logout">
                            <h3>Logout</h3>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <script>
                        // Add an event listener for logout
                        document.querySelector('.nav-logout').addEventListener('click', function () {
                            event.preventDefault();
                            // Submit the logout form
                            document.getElementById('logout-form').submit();
                        });
                    </script>      

            </nav>
        </div>

        <div class="main">

            <div class="searchbar2">
                <input type="text" 
                       name="" 
                       id="" 
                       placeholder="Search">
                <div class="searchbtn">
                  <img src= "02-search.png"
                        class="icn srchicn" 
                        alt="search-button">
                  </div>
            </div>


            <!--BODY CONTENT PAGE-->

            <h1 class="dashboard-title">RESERVATION HANDLING</h1>
            
            <div class="main-content">
                <div class="filter-container">
                    <div class="filter-bar">
                        <label for="roles">Sort by:</label>
                        <select id="roles">
                            <option>Ascending</option>
                            <option>Deascending</option>
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

                <div class="report-container">
                    <div class="report-header">
                        <h1 class="title-Header">List of Reservations</h1>
                    </div>
                    
                    <div class="report-body">
                        <div class="report-topic-heading">
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name of Student</th>
                                            <th>Borrowed Book</th>
                                            <th>Date of Reservation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example rows -->
                                        <tr>
                                            <td>001</td>
                                            <td>John Doe</td>
                                            <td>Qualitative Reserch Book</td>
                                            <td>01/01/2025</td>
                                        </tr>
                                        <tr>
                                            <td>002</td>
                                            <td>Jane Smith</td>
                                            <td>Quantitative Research Book</td>
                                            <td>02/01/2025</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="action-buttons">
                                <button class="confirm">Confirm</button>
                                <button class="cancel">Cancel</button>
                            </div> 
                        </div>
                    </div>
                </div>

            </div>    
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>