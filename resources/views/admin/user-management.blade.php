<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1.0">
    <title>UNIARCHIVE - User Management</title>
    <link rel="stylesheet" 
          href="{{ asset('css/ADMIN-UserMgmt2.css') }}">
    <link rel="stylesheet" []
          href="{{ asset('css/AA-Responsive-UserMgmt.css') }}">
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

                    <div class="option2 nav-option">
                        <img src= "{{ asset('assets/05-user management.png') }}"
                            class="nav-img" 
                            alt="usermgmt">
                        <h3>
                            User Management
                        </h3>
                    </div>

                    <div class="nav-option option3" onclick="window.location.href='{{ route('reservation-handling.page') }}'">
                        <img src="{{ asset('assets/06-reservation.png') }}" class="nav-img" alt="report">
                        <h3> Reservation Handling</h3>
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
                  <img src= "{{ asset('02-search.png') }}"
                        class="icn srchicn" 
                        alt="search-button">
                  </div>
            </div>


            <!--BODY CONTENT PAGE-->

            <h1 class="dashboard-title">ADMINISTRATORS</h1>
            
            <div class="main-content">
                <div class="filter-container">
                    <div class="filter-bar">
                        <label for="roles">Filter by:</label>
                        </select>
                        <!--might delete this filter option-->
                        <select id="date-added"> 
                            <option>Date Added</option>
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>This Month</option>
                            <option>Last Month</option>
                        </select>
                    </div>

                <div class="report-container">
                    <div class="report-header">
                        <h1 class="title-Header">List of Users</h1>
                        <button class="add">Add</button>
                    </div>
                    
                    <div class="report-body">
                        <div class="report-topic-heading">
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email Address</th>
                                            <th>Date Added</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Example rows -->
                                        <tr>
                                            <td>John Doe</td>
                                            <td>johndoe@example.com</td>
                                            <td>01/01/2025</td>
                                        </tr>
                                        <tr>
                                            <td>Jane Smith</td>
                                            <td>janesmith@example.com</td>
                                            <td>02/01/2025</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="view">View</button>
                    <button class="edit">Edit</button>
                    <button class="delete">Delete</button>
                </div> 

            </div>    
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>