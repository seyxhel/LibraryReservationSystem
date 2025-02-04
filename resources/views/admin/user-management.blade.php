<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">
    <title>UNIARCHIVE - User Management</title>
    <link rel="stylesheet" href="{{ asset('css/ADMIN-UserMgmt2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AA-Responsive-UserMgmt.css') }}">
    <link rel="stylesheet" href="{{ asset('css/CC-UserMgmt.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
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

            <!----BODY CONTENT PAGE----->
            <div class="main">
                <div class="dashboard-title">
                    USER MANAGEMENT
                    <div class="date-time">
                        <span id="current-date"></span>
                        <span id="current-time"></span>
                    </div>
                </div>

                <div class="wrapper">
                    <div class="tab-box">
                    <input checked="checked" id="tab-box1" name="tab-box" type="radio">
                    <label for="tab-box1" class="label-one">ADMIN</label>
                    <div class="content">
                        <div class="main-content">
                            <div class="table-container">
                                <div class="report-header">
                                    <h1 class="title-Header">Administrators</h1>
                                    <button class="add-admin-btn" id="addAdminBtn">ADD USER ADMIN</button>
                                </div>

                                <table class="inventory-table" id="bookTable">
                                    <thead>
                                        <tr>
                                            <th>School ID</th>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <tr>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <input id="tab-box2" name="tab-box" type="radio">
                    <label for="tab-box2" class="label-two">STUDENTS</label>
                    <div class="content">
                        <div class="main-content">
                            <div class="table-container">
                                <div class="report-header">
                                    <h1 class="title-Header">Student Users</h1>
                                </div>

                                <table class="inventory-table" id="studentTable">
                                    <thead>
                                        <tr>
                                            <th>School ID</th>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentTableBody">
                                        <!-- Data will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!---------- Add ADMIN Modal ------------->
        <div id="addAdminModal" class="modal-background fixed inset-0 flex justify-center items-center" style="display: none;">
            <div class="modal-content relative bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-xl font-semibold">ADD ADMIN</h2>
                    <button class="text-gray-500 hover:text-gray-700 text-3xl p-2" onclick="closeModal('addAdminModal')">&times;</button>
                </div>
                <form id="addAdminForm">
                    <div class="mb-4">
                        <label for="schoolID" class="block text-sm font-medium">School ID</label>
                        <input type="text" id="schoolID" name="School_ID" required class="text-field mt-1 block w-full rounded-md shadow-sm"
                            maxlength="20" pattern="20[0-9]{2}-[0-9]{5}-CM-2" title="Format: 20XX-XXXXX-CM-2">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input type="email" id="email" name="Email" required class="text-field mt-1 block w-full rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="firstname" class="block text-sm font-medium">First Name</label>
                        <input type="text" id="firstname" name="FirstName" required class="text-field mt-1 block w-full rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="middlename" class="block text-sm font-medium">Middle Name</label>
                        <input type="text" id="middlename" name="MiddleName" class="text-field mt-1 block w-full rounded-md shadow-sm">
                    </div>
                    <div class="mb-4 flex gap-4">
                        <div class="flex-1">
                            <label for="surname" class="block text-sm font-medium">Surname</label>
                            <input type="text" id="surname" name="LastName" required class="text-field mt-1 block w-full rounded-md shadow-sm">
                        </div>
                        <div class="w-24">
                            <label for="suffix" class="block text-sm font-medium">Suffix</label>
                            <select id="suffix" name="Suffix" class="text-field mt-1 block w-full rounded-md shadow-sm">
                                <option value="">None</option>
                                <option>Jr.</option>
                                <option>Sr.</option>
                                <option>II</option>
                                <option>III</option>
                                <option>IV</option>
                                <option>V</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="gender" class="block text-sm font-medium">Gender</label>
                        <select id="gender" name="Gender" required class="text-field mt-1 block w-full rounded-md shadow-sm">
                            <option disabled selected>Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                            <option>Prefer not to say</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="contactnum" class="block text-sm font-medium">Contact Number</label>
                        <input type="text" id="contactnum" name="ContactNumber" required class="text-field mt-1 block w-full rounded-md shadow-sm"
                            maxlength="11" pattern="09[0-9]{9}" title="Must be an 11-digit number starting with 09">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium">Password</label>
                        <input type="password" id="password" name="Password" required class="text-field mt-1 block w-full rounded-md shadow-sm"
                            minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                            title="At least 8 characters with uppercase, lowercase, number, and special character.">
                    </div>
                    <div class="mb-4">
                        <label for="confirmPassword" class="block text-sm font-medium">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="Password_confirmation" required class="text-field mt-1 block w-full rounded-md shadow-sm"
                            minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md" onclick="closeModal('addAdminModal')">Cancel</button>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md">CONFIRM</button>
                    </div>
                </form>
            </div>
        </div>

        <!---------- View ADMIN Modal ------------->
        <div id="viewAdminModal" class="modal-background flex justify-center items-center h-screen" style="display: none;">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-xl font-semibold">ADMIN INFORMATION</h2>
                    <button class="text-gray-500 hover:text-gray-700 text-3xl p-2" onclick="closeModal('viewAdminModal')">&times;</button>
                </div>
                <div class="space-y-4">
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">School ID</p>
                        <p class="mt-1 text-base" id="viewSchoolID"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="mt-1 text-base" id="viewEmail"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">First Name</p>
                        <p class="mt-1 text-base" id="viewFirstName"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Middle Name</p>
                        <p class="mt-1 text-base" id="viewMiddleName"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Surname</p>
                        <p class="mt-1 text-base" id="viewSurname"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Suffix</p>
                        <p class="mt-1 text-base" id="viewSuffix"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Gender</p>
                        <p class="mt-1 text-base" id="viewGender"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Contact Number</p>
                        <p class="mt-1 text-base" id="viewContactNum"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1 text-base" id="viewStatus"></p>
                    </div>
                </div>
                <div class="flex justify-center mt-6 pt-4 border-t">
                    <button type="button" class="bg-indigo-600 hover:bg-green-500 text-white px-4 py-2 rounded-md" onclick="closeModal('viewAdminModal')">OKAY</button>
                </div>
            </div>
        </div>

        <!---------- View STUDENT Modal ------------->
        <div id="viewStudentModal" class="modal-background flex justify-center items-center h-screen" style="display: none;">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-xl font-semibold">STUDENT INFORMATION</h2>
                    <button class="text-gray-500 hover:text-gray-700 text-3xl p-2" onclick="closeModal('viewStudentModal')">&times;</button>
                </div>
                <div class="space-y-4">
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">School ID</p>
                        <p class="mt-1 text-base" id="viewStudentID"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="mt-1 text-base" id="viewStudentEmail"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">First Name</p>
                        <p class="mt-1 text-base" id="viewStudentFirstName"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Middle Name</p>
                        <p class="mt-1 text-base" id="viewStudentMiddleName"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Surname</p>
                        <p class="mt-1 text-base" id="viewStudentSurname"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Suffix</p>
                        <p class="mt-1 text-base" id="viewStudentSuffix"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Gender</p>
                        <p class="mt-1 text-base" id="viewStudentGender"></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Contact Number</p>
                        <p class="mt-1 text-base" id="viewStudentContactNum"></p>
                    </div>
                </div>
                <div class="flex justify-center gap-4 mt-6 pt-4 border-t">
                    <button type="button" class="bg-gray-500 hover:bg-red-400 text-white px-4 py-2 rounded-md" onclick="openChangePasswordModal('')">Change Password</button>
                    <button type="button" class="bg-indigo-600 hover:bg-green-500 text-white px-4 py-2 rounded-md" onclick="closeModal('viewStudentModal')">OKAY</button>
                </div>
            </div>
        </div>

        <!---------- Student Change Password Modal ------------>
        <div id="changePasswordModal" class="modal-background flex justify-center items-center h-screen" style="display: none;">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg max-w-xl w-full">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-xl font-semibold">Change Student Password</h2>
                    <button class="text-gray-500 hover:text-gray-700 text-3xl p-2" onclick="closeModal('changePasswordModal')">&times;</button>
                </div>
                <form id="changePasswordForm">
                    <input type="hidden" id="changePasswordStudentEmail"> <!-- Hidden field to store student email -->
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="newPassword" class="block text-sm font-medium text-gray-500">Enter New Password</label>
                            <input type="password"
                                id="newPassword"
                                class="text-field mt-1 block w-full rounded-md shadow-sm"
                                required
                                minlength="8"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                                title="Password must be at least 8 characters long and include at least one number, one lowercase letter, one uppercase letter, and one special character."
                            >
                        </div>
                        <div class="mb-4">
                            <label for="confirmNewPassword" class="block text-sm font-medium text-gray-500">Confirm New Password</label>
                            <input type="password"
                                id="confirmNewPassword"
                                class="text-field mt-1 block w-full rounded-md shadow-sm"
                                required
                                minlength="8"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                                title="Password must match the requirements above"
                            >
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 pt-4 border-t">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
