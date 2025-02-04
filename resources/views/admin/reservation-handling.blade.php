@php
    dd($reservations);
@endphp
<!-- reservation-handling.blade.php -->
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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .confirm-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancel-btn {
            background-color: #ff9800;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .confirm-btn:disabled,
        .cancel-btn:disabled,
        .delete-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>
<body>
    <!-- Debug information at the top -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <div class="nav-option option1" id="dashboard">
                        <img src="{{ asset('assets/04-dashboard.png') }}" class="nav-img" alt="dashboard">
                        <h3>Dashboard</h3>
                    </div>

                    <div class="option2 nav-option" id="user-management">
                        <img src="{{ asset('assets/05-user management.png') }}" class="nav-img" alt="User Management">
                        <h3>User Management</h3>
                    </div>

                    <div class="nav-option option3">
                        <img src="{{ asset('assets/06-reservation.png') }}" class="nav-img" alt="reserved">
                        <h3>Reservation Handling</h3>
                    </div>

                    <div class="nav-option option4" onclick="window.location.href='{{ route('reports.page') }}'">
                        <img src="{{ asset('assets/07-reports.png') }}" class="nav-img" alt="institution">
                        <h3>Reports</h3>
                    </div>

                    <div class="option5 nav-option" id="book-inventory">
                        <img src="{{ asset('assets/05-user management.png') }}" class="nav-img" alt="Book Inventory">
                        <h3>Book Inventory</h3>
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
                    <div class="filter-container">
                        <div class="filter-bar">
                            <label for="sortOrder">Sort by:</label>
                            <select id="sortOrder">
                                <option value="ASC">Ascending</option>
                                <option value="DESC">Descending</option>
                            </select>
                            <label for="dateFilter">Date Reserved:</label>
                            <select id="dateFilter">
                                <option value="all">All Time</option>
                                <option value="last7days">Last 7 Days</option>
                                <option value="last30days">Last 30 Days</option>
                                <option value="thisMonth">This Month</option>
                                <option value="lastMonth">Last Month</option>
                            </select>
                        </div>

                        <table class="reservation-table" id="bookTable">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Book Title</th>
                                    <th>Reservation Date</th>
                                    <th>Time Slot</th>
                                    <th>Book Code</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @if(isset($reservations) && count($reservations) > 0)
                                @foreach($reservations as $reservation)
                                <tr data-id="{{ $reservation->ReservationID ?? 'N/A' }}">
                                    <td>{{ $reservation->StudentName ?? 'N/A' }}</td>
                                    <td>{{ $reservation->BookTitle ?? 'N/A' }}</td>
                                    <td>{{ isset($reservation->ReservationDate) ? \Carbon\Carbon::parse($reservation->ReservationDate)->format('m-d-Y') : 'N/A' }}</td>
                                    <td>{{ $reservation->TimeSlot ?? 'N/A' }}</td>
                                    <td>{{ $reservation->BookID ?? 'N/A' }}</td>
                                    <td>{{ $reservation->Resrv_Status ?? 'N/A' }}</td>
                                    <td class="action-buttons">
                                        @if($reservation->Resrv_Status === 'Pending')
                                            <button class="confirm-btn" onclick="confirmReservation({{ $reservation->ReservationID }})">
                                                Confirm
                                            </button>
                                            <button class="cancel-btn" onclick="cancelReservation({{ $reservation->ReservationID }})">
                                                Cancel
                                            </button>
                                        @endif
                                        <button class="delete-btn" onclick="deleteReservation({{ $reservation->ReservationID }})">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No reservations found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
    <script>
        // Set CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle logout
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Navigation redirects
        document.getElementById('dashboard').addEventListener('click', function() {
            window.location.href = "{{ route('admin.dashboard') }}";
        });

        document.getElementById('user-management').addEventListener('click', function() {
            window.location.href = "{{ route('admin.user-management') }}";
        });

        document.getElementById('book-inventory').addEventListener('click', function() {
            window.location.href = "{{ route('admin.book-inventory') }}";
        });

        function confirmReservation(id) {
            if (confirm('Are you sure you want to confirm this reservation?')) {
                fetch(`{{ url('admin/reservations') }}/${id}/confirm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        refreshTable();
                    } else {
                        alert(data.message || 'Failed to confirm reservation');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while confirming the reservation');
                });
            }
        }

        function cancelReservation(id) {
            if (confirm('Are you sure you want to cancel this reservation?')) {
                fetch(`{{ url('admin/reservations') }}/${id}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        refreshTable();
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
            if (confirm('Are you sure you want to delete this reservation? This action cannot be undone.')) {
                fetch(`{{ url('admin/reservations') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        refreshTable();
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

        function refreshTable() {
            const sortOrder = document.getElementById('sortOrder').value;
            const dateFilter = document.getElementById('dateFilter').value;

            fetch(`{{ route('admin.reservations.filtered') }}?sort=${sortOrder}&dateFilter=${dateFilter}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('tableBody');
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center">No reservations found</td>
                            </tr>
                        `;
                        return;
                    }

                    data.forEach(reservation => {
                        const row = document.createElement('tr');
                        row.dataset.id = reservation.ReservationID;

                        const reservationDate = new Date(reservation.ReservationDate);
                        const formattedDate = reservationDate.toLocaleDateString('en-US', {
                            month: '2-digit',
                            day: '2-digit',
                            year: 'numeric'
                        });

                        row.innerHTML = `
                            <td>${reservation.StudentName}</td>
                            <td>${reservation.BookTitle}</td>
                            <td>${formattedDate}</td>
                            <td>${reservation.TimeSlot}</td>
                            <td>${reservation.BookID}</td>
                            <td>${reservation.Resrv_Status}</td>
                            <td class="action-buttons">
                                ${reservation.Resrv_Status === 'Pending' ? `
                                    <button class="confirm-btn" onclick="confirmReservation(${reservation.ReservationID})">
                                        Confirm
                                    </button>
                                    <button class="cancel-btn" onclick="cancelReservation(${reservation.ReservationID})">
                                        Cancel
                                    </button>
                                ` : ''}
                                <button class="delete-btn" onclick="deleteReservation(${reservation.ReservationID})">
                                    Delete
                                </button>
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to refresh the table');
                });
        }

        // Event listeners for filters
        document.getElementById('sortOrder').addEventListener('change', refreshTable);
        document.getElementById('dateFilter').addEventListener('change', refreshTable);

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('en-US', options);
            const time = now.toLocaleTimeString('en-US');

            document.getElementById('current-date').textContent = date;
            document.getElementById('current-time').textContent = time;
        }

        <!-- Continuing from where we left off in the blade template -->

setInterval(updateDateTime, 1000);
window.onload = () => {
    updateDateTime();
    // Add debug info on page load
    console.log('Page loaded');
    console.log('Sort order:', document.getElementById('sortOrder').value);
    console.log('Date filter:', document.getElementById('dateFilter').value);
};

// Add error handling for invalid dates
function isValidDate(date) {
    return date instanceof Date && !isNaN(date);
}

// Add loading states for buttons
function setLoadingState(button, isLoading) {
    button.disabled = isLoading;
    button.textContent = isLoading ? 'Loading...' : button.getAttribute('data-original-text');
}

// Initialize button text
document.querySelectorAll('button').forEach(button => {
    button.setAttribute('data-original-text', button.textContent);
});

// Add error handling for network requests
function handleFetchError(error) {
    console.error('Network error:', error);
    alert('A network error occurred. Please check your connection and try again.');
}

// Add session check
function checkSession() {
    fetch('{{ route("admin.check-session") }}')
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated) {
                alert('Your session has expired. Please log in again.');
                window.location.href = '{{ route("admin.login") }}';
            }
        })
        .catch(error => console.error('Session check failed:', error));
}

// Check session every 5 minutes
setInterval(checkSession, 5 * 60 * 1000);

// Add responsive table handling
function handleResponsiveTable() {
    const table = document.querySelector('.reservation-table');
    const windowWidth = window.innerWidth;

    if (windowWidth < 768) {
        table.classList.add('responsive');
    } else {
        table.classList.remove('responsive');
    }
}

// Listen for window resize
window.addEventListener('resize', handleResponsiveTable);

// Initial call
handleResponsiveTable();

// Add toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }, 100);
}

// Replace default alerts with toast notifications
function showNotification(message, type) {
    showToast(message, type);
}

// Handle table row highlighting
function highlightRow(row) {
    row.classList.add('highlighted');
    setTimeout(() => {
        row.classList.remove('highlighted');
    }, 2000);
}

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close any open modals or dropdowns
        const dropdowns = document.querySelectorAll('.dropdown-content');
        dropdowns.forEach(dropdown => dropdown.style.display = 'none');
    }
});

// Add form validation
function validateForm(formData) {
    const errors = [];

    if (!formData.get('studentName')) {
        errors.push('Student name is required');
    }

    if (!formData.get('bookTitle')) {
        errors.push('Book title is required');
    }

    return errors;
}

// Add export functionality
function exportToCSV() {
    const table = document.querySelector('.reservation-table');
    const rows = table.querySelectorAll('tr');
    let csv = [];

    for (const row of rows) {
        const rowData = [];
        const cols = row.querySelectorAll('td, th');

        for (const col of cols) {
            // Remove any commas from the data to avoid CSV issues
            rowData.push('"' + (col.textContent || '').replace(/"/g, '""') + '"');
        }

        csv.push(rowData.join(','));
    }

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (navigator.msSaveBlob) {
        // IE 10+
        navigator.msSaveBlob(blob, 'reservations.csv');
    } else {
        link.href = URL.createObjectURL(blob);
        link.setAttribute('download', 'reservations.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Add print functionality
function printTable() {
    const printContent = document.querySelector('.table-container').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = `
        <div class="print-header">
            <h1>UNIARCHIVE - Reservation Report</h1>
            <p>Generated on: ${new Date().toLocaleString()}</p>
        </div>
        ${printContent}
    `;

    window.print();
    document.body.innerHTML = originalContent;

    // Reinitialize event listeners after restoring content
    initializeEventListeners();
}

// Initialize all event listeners
function initializeEventListeners() {
    // ... (previous event listeners)

    // Add export button listener
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', exportToCSV);
    }

    // Add print button listener
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
        printBtn.addEventListener('click', printTable);
    }
}

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
                alert(data.message);
                refreshTable();
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

function markOvertime(id) {
    if (confirm('Are you sure you want to mark this reservation as overtime?')) {
        fetch(`/admin/reservations/${id}/overtime`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                refreshTable();
            } else {
                alert(data.message || 'Failed to mark as overtime');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking as overtime');
        });
    }
}

// Call initialization on page load
initializeEventListeners();
</script>

<style>
/* Toast notification styles */
.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 10px 20px;
    border-radius: 4px;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.toast.show {
    opacity: 1;
}

.toast-info {
    background-color: #2196F3;
}

.toast-success {
    background-color: #4CAF50;
}

.toast-error {
    background-color: #f44336;
}

/* Print styles */
@media print {
    .no-print {
        display: none;
    }

    .print-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .table-container {
        margin: 20px;
    }
}

/* Responsive table styles */
@media screen and (max-width: 768px) {
    .reservation-table.responsive {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}

/* Row highlight animation */
.highlighted {
    background-color: #fff3cd;
    transition: background-color 0.5s ease-out;
}
</style>
</body>
</html>
