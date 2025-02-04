<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIARCHIVE - Reports</title>
    <link rel="stylesheet" href="{{ asset('css/ADMIN-Reports4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AA-Responsive-Reports4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CC-ReportsTable.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <!-- for header part -->
    <header>
        <div class="logosec">
            <div class="logo">UNIARCHIVE</div>
            <img src="{{ asset('assets/01-menu.png') }}"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>

        <div class="userman">
            <div class="user_name" id="userName"></div>
            <div class="dropdown">
                <img src="{{ asset('assets/03-user.png') }}"
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

                    <div class="option2 nav-option" id="user-management">
                        <img src="{{ asset('assets/05-user management.png') }}"
                            class="nav-img"
                            alt="User Management">
                        <h3>User Management</h3>
                    </div>

                    <div class="nav-option option3" onclick="window.location.href='{{ route('reservation-handling.page') }}'">
                        <img src="{{ asset('assets/06-reservation.png') }}" class="nav-img" alt="report">
                        <h3>Reservation Handling</h3>
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
                </div>
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
                                <option value="Ascending">Ascending</option>
                                <option value="Descending">Descending</option>
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
                                    <th onclick="sortTable(1)">Name of Student</th>
                                    <th onclick="sortTable(2)">Book Location</th>
                                    <th onclick="sortTable(3)">Book Code</th>
                                    <th onclick="sortTable(4)">Time Slot</th>
                                    <th onclick="sortTable(5)">Reservation Date</th>
                                    <th onclick="sortTable(6)">Reserved Until</th>
                                    <th onclick="sortTable(7)">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Table body will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
    <script>
        // Navigation Event Listeners
        document.getElementById('dashboard').addEventListener('click', function() {
            window.location.href = "{{ route('admin.dashboard') }}";
        });

        document.getElementById('user-management').addEventListener('click', function() {
            window.location.href = "{{ route('admin.user-management') }}";
        });

        document.getElementById('book-inventory').addEventListener('click', function() {
            window.location.href = "{{ route('admin.book-inventory') }}";
        });

        // Logout functionality
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Date and Time Update
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('en-US', options);
            const time = now.toLocaleTimeString('en-US');

            document.getElementById('current-date').textContent = date;
            document.getElementById('current-time').textContent = time;
        }

        setInterval(updateDateTime, 1000);
        window.onload = updateDateTime;

        // Print functionality
        document.getElementById('printBtn').addEventListener('click', function() {
            window.print();
        });

        // Table sorting
        let sortDirection = {};
        function sortTable(columnIndex) {
            const table = document.getElementById('bookTable');
            const tbody = table.getElementsByTagName('tbody')[0];
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            sortDirection[columnIndex] = !sortDirection[columnIndex];

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

            rows.forEach(row => tbody.appendChild(row));
        }

        // Fetch and display reservation data
        document.addEventListener('DOMContentLoaded', function() {
            fetchReservationData();

            // Add event listener for the sort select
            document.getElementById('SELECT').addEventListener('change', function() {
                const tbody = document.getElementById('tableBody');
                const rows = Array.from(tbody.getElementsByTagName('tr'));

                rows.sort((a, b) => {
                    const aVal = a.cells[0].textContent;
                    const bVal = b.cells[0].textContent;
                    return this.value === 'Ascending' ?
                        aVal.localeCompare(bVal) :
                        bVal.localeCompare(aVal);
                });

                rows.forEach(row => tbody.appendChild(row));
            });
        });

        function fetchReservationData() {
            fetch('/admin/reservation-report')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tableBody');
                    tbody.innerHTML = '';

                    data.forEach(reservation => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${reservation.BookNumber}</td>
                            <td>${reservation.StudentName}</td>
                            <td>${reservation.BookLocation}</td>
                            <td>${reservation.BookCode}</td>
                            <td>${reservation.TimeSlot}</td>
                            <td>${formatDate(reservation.ReservationDate)}</td>
                            <td>${formatDate(reservation.ReservedUntil)}</td>
                            <td>${reservation.Resrv_Status}</td>
                        `;

                        // Add status-based styling
                        const statusCell = row.lastElementChild;
                        switch(reservation.Resrv_Status.toLowerCase()) {
                            case 'confirmed':
                                statusCell.style.color = 'green';
                                break;
                            case 'overtime':
                                statusCell.style.color = 'red';
                                break;
                            case 'returned':
                                statusCell.style.color = 'blue';
                                break;
                            case 'canceled':
                                statusCell.style.color = 'gray';
                                break;
                        }

                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const tbody = document.getElementById('tableBody');
                    tbody.innerHTML = '<tr><td colspan="8" class="error-message">Error loading reservation data</td></tr>';
                });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: '2-digit',
                day: '2-digit',
                year: 'numeric'
            });
        }
    </script>
</body>
</html>
