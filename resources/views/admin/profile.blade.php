<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Account Details</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- Header part remains the same as your original -->
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

        <div class="main">
            <div class="dashboard-title">
                Edit Account Details
                <div class="date-time">
                    <span id="current-date"></span>
                    <span id="current-time"></span>
                </div>
            </div>

            <!-- Fixed position message container -->
            <div id="message-container" class="fixed top-4 right-4 z-50"></div>

            <!-- Profile Update Form -->
            <div class="max-w-4xl mx-auto">
                <form id="profile-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Personal Information</h3>
                    <div class="space-y-4 mb-6">
                        <!-- School ID (Read-only) -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="schoolID">
                                School ID
                            </label>
                            <input type="text" id="schoolID" value="{{ $admin->School_ID }}"
                                class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                                disabled>
                        </div>

                        <!-- First Name -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="firstname">
                                First Name
                            </label>
                            <input type="text" id="firstname" name="firstname" value="{{ $admin->FirstName }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-xs mt-1" id="firstname-error"></span>
                        </div>

                        <!-- Middle Name -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="middlename">
                                Middle Name (Optional)
                            </label>
                            <input type="text" id="middlename" name="middlename" value="{{ $admin->MiddleName }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <!-- Last Name and Suffix -->
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="lastname">
                                    Last Name
                                </label>
                                <input type="text" id="lastname" name="lastname" value="{{ $admin->LastName }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <span class="text-red-500 text-xs mt-1" id="lastname-error"></span>
                            </div>
                            <div class="w-32">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="suffix">
                                    Suffix
                                </label>
                                <input type="text" value="{{ $admin->Suffix }}"
                                    class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                                    disabled>
                            </div>
                        </div>

                        <!-- Gender (Read-only) -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="gender">
                                Gender
                            </label>
                            <input type="text" value="{{ ucfirst($admin->Gender) }}"
                                class="bg-gray-100 appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight"
                                disabled>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Contact Information</h3>
                    <div class="space-y-4 mb-6">
                        <!-- Contact Number -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="contactnum">
                                Contact Number
                            </label>
                            <input type="text" id="contactnum" name="contactnum" value="{{ $admin->ContactNumber }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                maxlength="11" pattern="^09[0-9]{9}$">
                            <span class="text-red-500 text-xs mt-1" id="contactnum-error"></span>
                            <p class="text-gray-600 text-xs mt-1">Format: 09XXXXXXXXX</p>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ $admin->Email }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-xs mt-1" id="email-error"></span>
                        </div>
                    </div>

                    <!-- Password Change -->
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Change Password</h3>
                    <div class="space-y-4 mb-6">
                        <!-- Current Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">
                                Current Password
                            </label>
                            <input type="password" id="current_password" name="current_password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-xs mt-1" id="current-password-error"></span>
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
                                New Password
                            </label>
                            <input type="password" id="new_password" name="new_password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-xs mt-1" id="new-password-error"></span>
                            <p class="text-gray-600 text-xs mt-1">
                                Password must contain at least 8 characters, including uppercase, lowercase, number and special character
                            </p>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password_confirmation">
                                Confirm New Password
                            </label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="text-red-500 text-xs mt-1" id="password-confirmation-error"></span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
    <script>
        // DateTime update function remains the same
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('en-US', options);
            const time = now.toLocaleTimeString('en-US');

            document.getElementById('current-date').textContent = date;
            document.getElementById('current-time').textContent = time;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Form submission handling
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');

            // Get form data
            const formData = {
                firstname: document.getElementById('firstname').value,
                lastname: document.getElementById('lastname').value,
                middlename: document.getElementById('middlename').value,
                email: document.getElementById('email').value,
                contactnum: document.getElementById('contactnum').value,
                current_password: document.getElementById('current_password').value,
                new_password: document.getElementById('new_password').value,
                new_password_confirmation: document.getElementById('new_password_confirmation').value
            };

            // Remove empty password fields if not changing password
            if (!formData.current_password && !formData.new_password && !formData.new_password_confirmation) {
                delete formData.current_password;
                delete formData.new_password;
                delete formData.new_password_confirmation;
            }

            // Validate password fields if attempting to change password
            if (formData.new_password || formData.current_password) {
                if (!formData.current_password) {
                    document.getElementById('current-password-error').textContent = 'Current password is required to change password';
                    return;
                }
                if (!formData.new_password) {
                    document.getElementById('new-password-error').textContent = 'New password is required';
                    return;
                }
                if (!formData.new_password_confirmation) {
                    document.getElementById('password-confirmation-error').textContent = 'Please confirm your new password';
                    return;
                }
                if (formData.new_password !== formData.new_password_confirmation) {
                    document.getElementById('password-confirmation-error').textContent = 'Passwords do not match';
                    return;
                }

                // Password strength validation
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!passwordRegex.test(formData.new_password)) {
                    document.getElementById('new-password-error').textContent =
                        'Password must be at least 8 characters long and include uppercase, lowercase, number and special character';
                    return;
                }
            }

            // Send AJAX request
            fetch('{{ route('admin.profile.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    // Show success message
                    showMessage(response.message || 'Profile updated successfully!', 'success');

                    // Clear password fields
                    document.getElementById('current_password').value = '';
                    document.getElementById('new_password').value = '';
                    document.getElementById('new_password_confirmation').value = '';

                    // Update displayed values if needed
                    if (response.user) {
                        document.getElementById('firstname').value = response.user.FirstName;
                        document.getElementById('lastname').value = response.user.LastName;
                        document.getElementById('middlename').value = response.user.MiddleName || '';
                        document.getElementById('email').value = response.user.Email;
                        document.getElementById('contactnum').value = response.user.ContactNumber;
                    }
                } else {
                    if (response.errors) {
                        Object.entries(response.errors).forEach(([field, messages]) => {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = messages[0];
                            }
                        });
                    }
                    showMessage(response.message || 'Please correct the errors in the form.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred while updating the profile.', 'error');
            });
        });
    </script>
</body>
</html>
