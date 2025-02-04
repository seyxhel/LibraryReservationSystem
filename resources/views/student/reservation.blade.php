<!DOCTYPE html>
<html>
<head>
    <title>UNIARCHIVE</title>
    <link rel="stylesheet" href="{{ asset('css/Student.ReservationPage.css') }}">
</head>

<body>

    <div class="wrapper">

        <div class="top_navbar">
            <div class="header">
                <img src="{{ asset('assets/UNIARCHIVE__4_-removebg-preview.png') }}" class="logo">
            </div>

            <div class="userman">
                <div class="user_name" id="userName">
                </div>

              <script>
               // Simulate fetching data from a source (e.g., backend API)
               const userData = {
            name: "{{ Auth::guard('student')->user()->FirstName }} {{ Auth::guard('student')->user()->LastName }}"
        };

        document.addEventListener("DOMContentLoaded", () => {
            const userNameDiv = document.getElementById("userName");
            userNameDiv.textContent = userData.name; // Only display the user's name
        });
              </script>

<div class="dropdown">
                        <button class="dropbtn">▼</button>
                        <div class="dropdown-content">
                            <a href="javascript:void(0)" class="scroll-link" onclick="toggleProfile()">View Profile</a>
                            <!-- Log Out Option -->
                            <a href="#" id="logout-link">Log Out</a>
                        </div>
                    </div>

                    <!-- Separate Logout Form -->
                    <form id="logout-form" action="{{ route('student.logout') }}" method="POST" style="display: none;">
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

        <div class="main_container">
            <div class="sidebar">
                <div class="sidebar__inner">
                  <ul>
                    <li>
                      <a href="{{ route('student.dashboard') }}" >
                        <span class="title">Dashboard</span>
                      </a>
                    </li>
                    <li>
                        <a href="{{ route('student.library') }}">
                            <span class="title">Library</span>
                        </a>
                    </li>
                    <li>
                      <a class="active">
                        <span class="title">Reservation</span>
                      </a>
                    </li>
                  </ul>

                </div>
            </div>

            <div class="container">

              <div class="title-bar">
                <span id="current-date"></span>
                <span id="current-time"></span>
              </div>

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
            </script>

            <div class="filter">
              <p>Filter:</p>
              <label for="dateFilter">Date</label>
              <select id="dateFilter" name="dateFilter">
                <option value="Date">Select Date:</option>
                <option value="value">""</option>
              </select>
            </div>
<!-- Navigation Tabs -->
 <!-- Navigation and Table Structure -->
 <nav class="navigation-bar">
    <ul>
        <li><a href="#" id="all-books" class="active">All</a></li>
        <li><a href="#" id="reserved-books">Reserved Books</a></li>
        <li><a href="#" id="returned-books">Returned Books</a></li>
        <li><a href="#" id="overtime-books">Overtime Books</a></li>
    </ul>
  </nav>

  <!-- Add CSRF Token for Laravel -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="content-container">
    <!-- All Books Container -->
    <div id="all-books-container" class="content active">
        <main>
            <table>
                <thead>
                    <tr>
                        <th>Research Title</th>
                        <th>Reservation Date</th>
                        <th>Reserved Until</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </main>
    </div>

    <!-- Reserved Books Container -->
    <div id="reserved-books-container" class="content">
        <main>
            <table>
                <thead>
                    <tr>
                        <th>Research Title</th>
                        <th>Reservation Date</th>
                        <th>Time Slot</th>
                        <th>Reserved Until</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </main>
    </div>

    <!-- Returned Books Container -->
    <div id="returned-books-container" class="content">
        <main>
            <table>
                <thead>
                    <tr>
                        <th>Research Title</th>
                        <th>Reservation Date</th>
                        <th>Reserved Until</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </main>
    </div>

    <!-- Overtime Books Container (Removed "Time Overdue" column) -->
    <div id="overtime-books-container" class="content">
        <main>
            <table>
                <thead>
                    <tr>
                        <th>Research Title</th>
                        <th>Reservation Date</th>
                        <th>Reserved Until</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </main>
    </div>

    <!-- Container to show reservation details -->
    <div id="container-view" style="display: none;">
        <div class="view-content">
            <h2 id="view-title"></h2>
            <p id="view-category"></p>
            <p id="view-reservation-date"></p>
            <p id="view-status"></p>
            <p id="view-abstract"></p>
            <button id="close-view-button">Close</button>
        </div>
    </div>

    <script>
        // Handle close button for the container-view
        document.getElementById("close-view-button").addEventListener("click", () => {
            document.getElementById("container-view").style.display = "none";
        });

        // Function to populate and open the container-view with reservation details
        function showReservationDetails(data) {
            document.getElementById("view-title").textContent = data.title;
            document.getElementById("view-reservation-date").textContent = `Reservation Date: ${data.reservationDate}`;
            document.getElementById("view-status").textContent = `Status: ${data.status || 'N/A'}`;

            document.getElementById("container-view").style.display = "flex";
        }

        // JavaScript to handle tab switching and fetching data when relevant tab is selected
        const navLinks = document.querySelectorAll(".navigation-bar a");
        const contentContainers = document.querySelectorAll(".content");

        navLinks.forEach((link) => {
            link.addEventListener("click", async (e) => {
                e.preventDefault();

                // Remove 'active' class from all links and hide all containers
                navLinks.forEach((nav) => nav.classList.remove("active"));
                contentContainers.forEach((container) => container.classList.remove("active"));

                // Add 'active' class to the clicked link and its corresponding container
                link.classList.add("active");
                const targetContainer = document.getElementById(`${link.id}-container`);
                if (targetContainer) {
                    targetContainer.classList.add("active");

                    // Fetch reservations when the relevant tab is clicked
                    if (link.id === 'reserved-books') {
                        await fetchReservedBooks();
                    } else if (link.id === 'all-books') {
                        await fetchReservations();
                    }
                }
            });
        });

        // Function to populate tables with data
        function populateTable(containerId, data, columns) {
            const container = document.getElementById(containerId);
            if (container) {
                const tbody = container.querySelector("tbody");
                tbody.innerHTML = '';

                data.forEach((row) => {
                    const tr = document.createElement("tr");

                    // Add columns to the row
                    columns.forEach((column) => {
                        const td = document.createElement("td");
                        td.textContent = row[column];

                        if (column === "status") {
                            td.classList.add(row[column].toLowerCase());
                        }

                        tr.appendChild(td);
                    });

                    // Add View button
                    const viewTd = document.createElement("td");
                    const viewButton = document.createElement("button");
                    viewButton.textContent = "View";
                    viewButton.classList.add("view-button");

                    viewButton.addEventListener("click", () => showReservationDetails(row));

                    viewTd.appendChild(viewButton);
                    tr.appendChild(viewTd);
                    tbody.appendChild(tr);
                });
            }
        }

        // Function to fetch all reservations from the server
        async function fetchReservations() {
            try {
                const response = await fetch('/student/reservations/all', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                // Populate each table with the fetched data
                populateTable("all-books-container", data.all, [
                    "title", "reservationDate", "reservedUntil", "status"
                ]);
                populateTable("reserved-books-container", data.reserved, [
                    "title", "reservationDate", "timeSlot", "reservedUntil"
                ]);
                populateTable("returned-books-container", data.returned, [
                    "title", "reservationDate", "reservedUntil"
                ]);
                populateTable("overtime-books-container", data.overtime, [
                    "title", "reservationDate", "reservedUntil"
                ]);

            } catch (error) {
                console.error('Error fetching reservations:', error);
                alert('Failed to load reservations. Please try again later.');
            }
        }

        // Function to fetch reserved books from the server
        async function fetchReservedBooks() {
            try {
                const response = await fetch('/student/reservations/reserved', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const result = await response.json();

                if (result.success) {
                    populateTable("reserved-books-container", result.data, [
                        "title", "reservationDate", "timeSlot", "reservedUntil"
                    ]);
                } else {
                    throw new Error(result.message || 'Failed to fetch reserved books');
                }

            } catch (error) {
                console.error('Error fetching reserved books:', error);
                alert('Failed to load reserved books. Please try again later.');
            }
        }

        // Set default content visibility and fetch data when page loads
        document.addEventListener('DOMContentLoaded', async () => {
            document.getElementById("all-books-container").classList.add("active");
            await fetchReservations();
            await fetchReservedBooks();
        });

        // Optional: Auto-refresh every 5 minutes
        setInterval(fetchReservations, 300000);
  </script>
</div>

        <div class="profile-container" id="profile-section">
          <!-- Close button -->
          <button class="close-btn" onclick="toggleProfile()">×</button>

          <h2>View Profile</h2>

          <!-- Form with aligned text boxes -->
<form>
    <div class="four-forms">
        <div class="input-box">
            <label for="lastname">Lastname:</label>
            <input type="text" class="input-field" id="lastname" placeholder="Lastname" value="{{ $student->LastName }}" readonly>
            <i class="bx bx-user"></i>
        </div>
        <div class="input-box">
            <label for="firstname">Firstname:</label>
            <input type="text" class="input-field" id="firstname" placeholder="Firstname" value="{{ $student->FirstName }}" readonly>
            <i class="bx bx-user"></i>
        </div>
        <div class="input-box">
            <label for="middlename">Middlename:</label>
            <input type="text" class="input-field" id="middlename" placeholder="Middlename" value="{{ $student->MiddleName }}" readonly>
            <i class="bx bx-user"></i>
        </div>
        <div class="input-box">
            <label for="suffix">Suffix:</label>
            <input type="text" class="input-field" id="suffix" placeholder="Suffix" value="{{ $student->Suffix }}" readonly>
            <i class="bx bx-user"></i>
        </div>
    </div>

    <!-- Gender and Program Section -->
<div class="two-forms">
    <div class="input-box">
        <label for="gender">Gender:</label>
        <input type="text" class="input-field" id="gender" placeholder="Gender"
            value="{{ $student->Gender == 'male' ? 'Male' : ($student->Gender == 'female' ? 'Female' : ($student->Gender == 'other' ? 'Other' : 'Prefer not to say')) }}"
            readonly>
        <i class="bx bx-chevron-down"></i>
    </div>

    <div class="input-box">
        <label for="program">College Program:</label>
        <input type="text" class="input-field" id="program" placeholder="College Program"
            value="{{
                $student->Program_ID == 1 ? 'Information Technology' :
                ($student->Program_ID == 2 ? 'Home Economics' :
                ($student->Program_ID == 3 ? 'Information Communication and Technology' :
                ($student->Program_ID == 4 ? 'Human Resource Management' :
                ($student->Program_ID == 5 ? 'Marketing Management' :
                ($student->Program_ID == 6 ? 'Entrepreneurship' :
                ($student->Program_ID == 7 ? 'Fiscal Administration' : 'Office Management Technology'))))))
            }}" s
            readonly>
        <i class="bx bx-chevron-down"></i>
    </div>
</div>


    <!-- Contact Section -->
    <div class="input-box">
        <label for="contact">Contact Number:</label>
        <input type="text" class="input-field" id="contact" placeholder="Contact Number" value="{{ $student->ContactNumber }}" readonly>
        <i class="bx bx-contact"></i>
    </div>

    <div class="input-box">
        <label for="email">E-mail Address:</label>
        <input type="email" class="input-field" id="email" placeholder="E-mail Address" value="{{ $student->Email }}" readonly>
        <i class="bx bx-envelope"></i>
    </div>

                <div class="button-group">
                  <button class="btn btn-change" type="button" onclick="toggleChangePassword()">Change Password</button>
                  <a href="{{ route('student.edit.profile') }}" class="btn btn-edit">Edit Profile</a>
                  <button class="btn btn-delete" type="button" onclick="toggleDeleteAccount()">Delete Account</button>
                </div>

              </form>

            </div>

            <div class="profile-container" id="change-password-section" style="display: none;">
            <button class="close-btn" onclick="toggleChangePassword()">×</button>

<h2>Change Password</h2>

<!-- Form for changing the password -->
<form method="POST" action="{{ route('student.updatePassword') }}">
    @csrf
    <div class="input-box">
        <label for="current-password">Current Password:</label>
        <input type="password" class="input-field" id="current-password" name="current_password" placeholder="Enter Current Password" required>
    </div>
    <div class="input-box">
        <label for="new-password">New Password:</label>
        <input type="password" class="input-field" id="new-password" name="new_password" placeholder="Enter New Password" required>
    </div>
    <div class="input-box">
        <label for="confirm-new-password">Confirm New Password:</label>
        <input type="password" class="input-field" id="confirm-new-password" name="new_password_confirmation" placeholder="Confirm New Password" required>
    </div>

    <div class="button-group">
        <button type="submit" class="btn btn-edit">Confirm</button>
        <button type="button" class="btn btn-cancel" onclick="toggleChangePassword()">Cancel</button>
    </div>
</form>

</div>

<div class="profile-container" id="delete-account-section" style="display: none;">
<button class="close-btn" onclick="toggleDeleteAccount()">×</button>

<h2 style="text-align: center;">Delete Account</h2>

    <p style="text-align: center;">
        Are you sure you want to delete your account? This action cannot be undone.
    </p>

    <form method="POST" action="{{ route('student.deleteAccount') }}" style="text-align: center;">
        @csrf
        <div class="button-group" style="display: flex; justify-content: center; gap: 1rem; margin-top: 20px;">
            <button type="submit" class="btn btn-delete">Delete Account</button>
            <button type="button" class="btn btn-cancel" onclick="toggleDeleteAccount()">Cancel</button>
        </div>
    </form>
</div>

<script>
    // Toggle Change Password Section
    function toggleChangePassword() {
        const section = document.getElementById('change-password-section');
        const profileSection = document.getElementById('profile-section');
        if (section.style.display === 'none') {
            section.style.display = 'block';
            profileSection.style.display = 'none';
        } else {
            section.style.display = 'none';
            profileSection.style.display = 'block';
        }
    }

    // Toggle Delete Account Section
    function toggleDeleteAccount() {
        const section = document.getElementById('delete-account-section');
        const profileSection = document.getElementById('profile-section');
        if (section.style.display === 'none') {
            section.style.display = 'block';
            profileSection.style.display = 'none';
        } else {
            section.style.display = 'none';
            profileSection.style.display = 'block';
        }
    }

    // Save Password Logic
    function savePassword() {
        const currentPassword = document.getElementById('current-password').value.trim();
        const newPassword = document.getElementById('new-password').value.trim();
        const confirmNewPassword = document.getElementById('confirm-new-password').value.trim();

        if (newPassword !== confirmNewPassword) {
            alert('New passwords do not match.');
            return;
        }

        // Add password validation logic here
        alert('Password updated successfully!');
        toggleChangePassword();
    }

    // Delete Account Logic
    function deleteAccount() {
        if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
            document.querySelector('#delete-account-form').submit();
            }
    }
</script>
        <script>
          // JavaScript to toggle the visibility of the profile section
          function toggleProfile() {
              var profileSection = document.getElementById("profile-section");
              if (profileSection.style.display === "none" || profileSection.style.display === "") {
                  profileSection.style.display = "block"; // Show the profile section
              } else {
                  profileSection.style.display = "none";  // Hide the profile section
              }
          }
        </script>

      </div>

</body>
</html>
