<!DOCTYPE html>
<html>
<head>
    <title>UNIARCHIVE</title>
    <link rel="stylesheet" href="{{ asset('css/Student.DashboardPage.css') }}">
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

        </div>

        <div class="main_container">
            <div class="sidebar">
                <div class="sidebar__inner">
                  <ul>
                    <li>
                      <a class="active">
                        <span class="title">Dashboard</span>
                      </a>
                    </li>
                    <li>
                        <a href="{{ route('student.library') }}">
                            <span class="title">Library</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('student.reservation') }}">
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
                // Simulate fetching data from a source (e.g., backend API)
            const userData = {
            name: "{{ Auth::guard('student')->user()->FirstName }} {{ Auth::guard('student')->user()->LastName }}"
        };

        document.addEventListener("DOMContentLoaded", () => {
            const userNameDiv = document.getElementById("userName");
            userNameDiv.textContent = userData.name; // Only display the user's name
        });

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

              <div class="container1">
                <div class="box1">
                <h1 id="reserved-count"></h1>
                  <span>Total Number of Reserved Books</span></div>

                <div class="box2">
                  <h1 id="overtime-count"></h1>
                  <span>Total Number of Overtime Books</span></div>
              </div>

              <script>
                document.addEventListener("DOMContentLoaded", function () {
                  fetchConfirmedReservations();
                  fetchOvertimeReservations();
                });

                function fetchConfirmedReservations() {
                  fetch("{{ route('student.confirmed.reservations') }}")
                      .then(response => response.json())
                      .then(data => {
                          console.log("Confirmed Reservations:", data.confirmedReservations); // Debugging
                          document.getElementById('reserved-count').textContent = data.confirmedReservations;
                      })
                      .catch(error => {
                          console.error("Error fetching confirmed reservations:", error);
                          document.getElementById('reserved-count').textContent = "Error";
                      });
                }

                function fetchOvertimeReservations() {
                  fetch("{{ route('student.overtime.reservations') }}")
                      .then(response => response.json())
                      .then(data => {
                          console.log("Overtime Reservations:", data.overtimeReservations); // Debugging
                          document.getElementById('overtime-count').textContent = data.overtimeReservations;
                      })
                      .catch(error => {
                          console.error("Error fetching overtime reservations:", error);
                          document.getElementById('overtime-count').textContent = "Error";
                      });
                }
                </script>

              <div class="container2">
                <div class="box3">
                  <div class="header">
                    <h1 id="new-box1">Reservations</h1>
                  </div>

                  <!-- This container will make the table scrollable -->
                  <div id="reservations-table-container">
                    <table id="reservations-table">
                        <thead>
                            <tr>
                                <th>Research Title</th>
                                <th>Category</th>
                                <th>Reservation Date</th>
                                <th>Time Slot</th>
                                <th>Reserved Until</th>
                            </tr>
                        </thead>
                        <tbody id="reservations-body">
                            <tr><td colspan="5">Loading reservations...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                fetchReservations();
            });

            function fetchReservations() {
                fetch("{{ route('student.reservations.list') }}")
                    .then(response => response.json())
                    .then(data => {
                        console.log("Fetched reservations:", data); // Debugging

                        if (!Array.isArray(data) || data.length === 0) {
                            document.getElementById("reservations-body").innerHTML = "<tr><td colspan='5'>No reservations found</td></tr>";
                        } else {
                            populateReservationsTable(data);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching reservations:", error);
                        document.getElementById("reservations-body").innerHTML = "<tr><td colspan='5'>Error loading reservations</td></tr>";
                    });
            }

            function populateReservationsTable(data) {
                const tableBody = document.getElementById("reservations-body");
                tableBody.innerHTML = ""; // Clear table

                data.forEach(item => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${item.Title || 'N/A'}</td>
                        <td>${item.Categories || 'N/A'}</td>
                        <td>${item.ReservationDate || 'N/A'}</td>
                        <td>${item.TimeSlot || 'N/A'}</td>
                        <td>${item.ReservedUntil || 'N/A'}</td>
                    `;

                    tableBody.appendChild(row);
                });
            }
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
        <input type="password" class="input-field1" id="current-password" name="current_password" placeholder="Enter Current Password" required>
    </div>
    <div class="input-box">
        <label for="new-password">New Password:</label>
        <input type="password" class="input-field1" id="new-password" name="new_password" placeholder="Enter New Password" required>
    </div>
    <div class="input-box">
        <label for="confirm-new-password">Confirm New Password:</label>
        <input type="password" class="input-field1" id="confirm-new-password" name="new_password_confirmation" placeholder="Confirm New Password" required>
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


      </div>
    </body>
</html>
