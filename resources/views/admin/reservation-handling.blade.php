<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIARCHIVE - Reservation Handling</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/ADMIN-ReservationHandling3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AA-Responsive-ReservationHandling.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CC-ReserHanTable.css') }}">
    
    <style>
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .confirm-btn, .return-btn, .cancel-btn, .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .confirm-btn {
            background-color: #4CAF50;
        }
        .return-btn {
            background-color: #2196F3;
        }
        .cancel-btn {
            background-color: #ff9800;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            display: inline-block;
        }
        .status-confirmed {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .status-returned {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .status-canceled {
            background-color: #fff3e0;
            color: #f57c00;
        }
    </style>
</head>
<body>
    <!-- Header part -->
    <header>
        <div class="logosec">
            <div class="logo">UNIARCHIVE</div>
            <img src="{{ asset('assets/01-menu.png') }}" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>

        <div class="userman">
            <div class="user_name" id="userName"></div>
            <div class="dropdown">
                <img src="{{ asset('assets/03-user.png') }}" class="dpicn" alt="dp">
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
        </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <div class="nav-option option1">
                        <img src="{{ asset('assets/04-dashboard.png') }}" class="nav-img" alt="dashboard">
                        <h3><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></h3>
                    </div>

                    <div class="option2 nav-option">
                        <img src="{{ asset('assets/05-user management.png') }}" class="nav-img" alt="usermgmt">
                        <h3><a href="{{ route('admin.user-management') }}" class="nav-link">User Management</a></h3>
                    </div>

                    <div class="nav-option option3">
                        <img src="{{ asset('assets/06-reservation.png') }}" class="nav-img" alt="reserved">
                        <h3><a class="nav-link">Reservation Handling</a></h3>
                    </div>

                    <div class="nav-option option4" onclick="window.location.href='{{ route('reports.page') }}'">
                        <img src="{{ asset('assets/07-reports.png') }}" class="nav-img" alt="institution">
                        <h3>Reports</h3>
                    </div>

                    <div class="nav-option option5">
                        <img src="{{ asset('assets/13-inventory.png') }}" class="nav-img" alt="inventory">
                        <h3><a href="{{ route('admin.book-inventory') }}" class="nav-link">Book Inventory</a></h3>
                    </div>
                </div>
            </nav>
        </div>

        <div class="main">
            <div class="dashboard-title">
                RESERVATION HANDLING
                <div class="date-time">
                    <span id="current-date"></span>
                    <span id="current-time"></span>
                </div>
            </div>

            <div class="main-content">
                <div class="table-container">
                    <table class="reservation-table" id="bookTable">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Book Title</th>
                                <th>Time Slot</th>
                                <th>Book Code</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @if(!empty($reservations) && count($reservations) > 0)
                                @foreach($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation['StudentName'] }}</td>
                                        <td>{{ $reservation['BookTitle'] }}</td>
                                        <td>{{ $reservation['TimeSlot'] }}</td>
                                        <td>{{ $reservation['BookID'] }}</td>
                                        <td>
                                            <span class="status-badge status-{{ strtolower($reservation['Resrv_Status']) }}">
                                                {{ $reservation['Resrv_Status'] }}
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            @if($reservation['Resrv_Status'] === 'Confirmed')
                                                <button class="return-btn" onclick="returnBook({{ $reservation['ReservationID'] }})">Return</button>
                                                <button class="cancel-btn" onclick="cancelReservation({{ $reservation['ReservationID'] }})">Cancel</button>
                                            @elseif(in_array($reservation['Resrv_Status'], ['Canceled', 'Returned']))
                                                <button class="delete-btn" onclick="deleteReservation({{ $reservation['ReservationID'] }})">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="empty-state">No active reservations for today.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
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

        // Logout functionality
        document.getElementById('logout-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // AJAX setup for CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Action functions
        function returnBook(id) {
            if (confirm('Are you sure you want to mark this book as returned?')) {
                fetch(`/admin/reservations/${id}/return`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to process return');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the return');
                });
            }
        }

        function cancelReservation(id) {
            if (confirm('Are you sure you want to cancel this reservation?')) {
                fetch(`/admin/reservations/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to cancel reservation');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while cancelling the reservation');
                });
            }
        }

        function deleteReservation(id) {
            if (confirm('Are you sure you want to delete this reservation?')) {
                fetch(`/admin/reservations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to delete reservation');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the reservation');
                });
            }
        }
    </script>
</body>
</html>