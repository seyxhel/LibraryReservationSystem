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
    <link rel="stylesheet" []
            href="{{ asset('css/CC-ReportsTable.css') }}">
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
                  <a href="profile.html">Edit Account</a>
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
<!--START OF NAV CONTAINER-->
    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <div class="nav-option option1">
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
                REPORTS
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
                            <select id="SELECT">
                                <option>Ascending</option>
                                <option>Descending</option>
                            </select>
                        </div>

                        <div class="report-header">
                            <h1 class="title-Header">Reservation Data Records</h1>
                            <button id="printBtn" class="print-btn">PRINT</button>
                        </div>
             
                        <div class="print-header"></div>
                        <table class="inventory-table" id="bookTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">Book Number</th>
                                    <th onclick="sortTable(1)">Research Title</th>
                                    <th onclick="sortTable(2)">Location</th>
                                    <th onclick="sortTable(3)">Book Code</th>
                                    <th onclick="sortTable(4)">Category</th>
                                    <th onclick="sortTable(5)">Reservation Start</th>
                                    <th onclick="sortTable(6)">End of Reservation</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td>0001</td>
                                    <td>Book Sample 1</td>
                                    <td>Bookshelf 1</td>
                                    <td>GA0122</td>
                                    <td>Descriptive Research</td>
                                    <td>01-01-2023</td>
                                    <td>01-30-2023</td>
                                </tr>
                                <tr>
                                    <td>0456</td>
                                    <td>Book Sample 2</td>
                                    <td>Bookshelf 4</td>
                                    <td>MA1102</td>
                                    <td>Case Study Research</td>
                                    <td>11-24-2023</td>
                                    <td>12-10-2023</td>
                                </tr>
                            </tbody>
                        </table>
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

        //Print Dialog 
        document.addEventListener('DOMContentLoaded', function() {
        const printBtn = document.getElementById('printBtn');
        
        printBtn.addEventListener('click', function() {
            window.print();
        });
    });

    //Sort Table
    let sortDirection = {};
    function sortTable(columnIndex) {
        const table = document.getElementById('bookTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        
        // Toggle sort direction
        sortDirection[columnIndex] = !sortDirection[columnIndex];
        
        // Sort rows
        rows.sort((a, b) => {
            let aValue = a.cells[columnIndex].textContent;
            let bValue = b.cells[columnIndex].textContent;
            
            // Handle date columns
            if (columnIndex === 5 || columnIndex === 6) {
                aValue = new Date(aValue);
                bValue = new Date(bValue);
            }
            // Handle numeric columns
            else if (columnIndex === 0) {
                aValue = parseInt(aValue);
                bValue = parseInt(bValue);
            }
            
            if (sortDirection[columnIndex]) {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });
        
        // Update table
        rows.forEach(row => tbody.appendChild(row));
    }
    </script>
</body>
</html>
