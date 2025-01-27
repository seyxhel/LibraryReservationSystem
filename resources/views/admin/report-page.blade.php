<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1.0">
    <title>UNIARCHIVE - Reports</title>
    <link rel="stylesheet" 
          href="{{ asset('css/ADMIN-Reports4.css') }}">
    <link rel="stylesheet" []
          href="{{ asset('css/AA-Responsive-Reports4.css') }}">
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
<!--START OF NAV CONTAINER-->
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

                    <div class="nav-option option3" onclick="window.location.href='{{ route('reservation-handling.page') }}'">
                        <img src="{{ asset('assets/06-reservation.png') }}" class="nav-img" alt="report">
                        <h3> Reservation Handling</h3>
                    </div>

                    <div class="nav-option option4">
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
                  <img src= "{{ asset('02-search.png') }}"
                        class="icn srchicn" 
                        alt="search-button">
                  </div>
            </div>

            <h1 class="dashboard-title">REPORTS</h1>

            <div class="box-container">

                <div class="box box1">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Total Reserved Books</h2>
                    </div>
                </div>

                <div class="box box2">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Total Canceled Reservations</h2>
                    </div>
                </div>

                <div class="box box3">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Total of Overdue Reservations</h2>
                    </div>
                </div>

                <div class="box box4">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Total of Books Available</h2>
                    </div>
                </div>
            </div>
            
            <div class="main-content">
                <div class="filter-container">
                    <div class="filter-bar">
                        <label for="roles">Sort by:</label>
                        <select id="SELECT">
                            <option>Ascending</option>
                            <option>Descending</option>
                        </select>
                    </div>

                <div class="report-container">
                    <div class="report-header">
                        <h1 class="title-Header">List of Students</h1>
                        <button class="add">PRINT</button>
                    </div>
                    
                    <div class="report-body">
                        <div class="report-topic-heading">
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Users</th>
                                            <th>Reserved Books</th>
                                            <th>Reservation Date </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example rows -->
                                        <tr>
                                            <td>001</td>
                                            <td>John Doe</td>
                                            <td>Qualitative Research</td>
                                            <td>01-19-2025</td>
                                        </tr>
                                        <tr>
                                            <td>002</td>
                                            <td>Jane Smith</td>
                                            <td>Quantitative Research</td>
                                            <td>02-04-2025</td>
                                        </tr>
                                    </tbody>
                                </table>
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