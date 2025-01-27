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

               <!---DITOBETLOG-->
                <!-- Navigation Tabs -->

              <!-- Navigation Tabs -->
              <nav class="navigation-bar">
                <ul>
                    <li><a href="#" id="all-books" class="active">All</a></li>
                    <li><a href="#" id="reserved-books">Reserved Books</a></li>
                    <li><a href="#" id="returned-books">Returned Books</a></li>
                    <li><a href="#" id="overtime-books">Overtime Books</a></li>
                </ul>
              </nav>

              <div class="content-container">
                <!-- All Books Container -->
                <div id="all-books-container" class="content active">
                  <main>
                    <table>
                      <thead>
                        <tr>
                          <th>Research Title</th>
                          <th>Researchers</th> <!-- Changed from Author -->
                          <th>Reservation Date</th>
                          <th>Reserved Until</th>
                          <th>Status</th>
                          <th>Action</th> <!-- New column for View button -->
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
                          <th>Researchers</th>
                          <th>Reservation Date</th>
                          <th>Time Slot</th>
                          <th>Reserved Until</th>
                          <th>Action</th> <!-- New column for View button -->
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
                          <th>Researchers</th>
                          <th>Reservation Date</th>
                          <th>Reserved Until</th>
                          <th>Action</th> <!-- New column for View button -->
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </main>
                </div>
              
                <!-- Overtime Books Container -->
                <div id="overtime-books-container" class="content">
                  <main>
                    <table>
                      <thead>
                        <tr>
                          <th>Research Title</th>
                          <th>Researchers</th>
                          <th>Reservation Date</th>
                          <th>Reserved Until</th>
                          <th>Time Overdue</th>
                          <th>Action</th> <!-- New column for View button -->
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
                      <p id="view-researcher"></p>
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

                // Function to populate and open the container-view
                function showReservationDetails(data) {
                  document.getElementById("view-title").textContent = data.title || data.bookTitle || "Reservation Details";
                  document.getElementById("view-researcher").textContent = `Researcher(s): ${data.researcher || "N/A"}`;
                  document.getElementById("view-reservation-date").textContent = `Reservation Date: ${data.reservationDate || "N/A"}`;
                  document.getElementById("view-status").textContent = `Status: ${data.status || "N/A"}`;
                  document.getElementById("view-abstract").textContent = `Abstract: ${data.abstract || "N/A"}`;

                  document.getElementById("container-view").style.display = "flex";
                }
                </script>

                <script>
                  // JavaScript to handle tab switching
                  const navLinks = document.querySelectorAll(".navigation-bar a");
                  const contentContainers = document.querySelectorAll(".content");
              
                  navLinks.forEach((link) => {
                    link.addEventListener("click", (e) => {
                      e.preventDefault();
              
                      // Remove 'active' class from all links and hide all containers
                      navLinks.forEach((nav) => nav.classList.remove("active"));
                      contentContainers.forEach((container) => container.classList.remove("active"));
              
                      // Add 'active' class to the clicked link and its corresponding container
                      link.classList.add("active");
                      const targetContainer = document.getElementById(`${link.id}-container`);
                      if (targetContainer) {
                        targetContainer.classList.add("active");
                      }
                    });
                  });
              
                  // Set default content visibility
                  document.getElementById("all-books-container").classList.add("active");
              
                // Sample data for each table with added "category" field
                const allBooksData = [
                  { title: "The Great Gatsby", category: "Literature", researcher: "F. Scott Fitzgerald", reservationDate: "01-10-2025", status: "Returned" },
                  { title: "1984", category: "Dystopian", researcher: "George Orwell", reservationDate: "01-12-2025", status: "Reserved" },
                  { title: "To Kill a Mockingbird", category: "Literature", researcher: "Harper Lee", reservationDate: "02-16-2025", status: "Overtime" },
                ];

                const reservedBooksData = [
                  { bookTitle: "1984", category: "Dystopian", researcher: "Alice", reservationDate: "01-10-2025" },
                  { bookTitle: "Pride and Prejudice", category: "Literature", researcher: "Bob", reservationDate: "01-22-2025" },
                ];

                const returnedBooksData = [
                  { bookTitle: "The Great Gatsby", category: "Literature", researcher: "Charlie", reservationDate: "06-21-2025" },
                  { bookTitle: "The Catcher in the Rye", category: "Literature", researcher: "Diana", reservationDate: "12-24-2025" },
                ];

                const overtimeBooksData = [
                  { bookTitle: "Moby Dick", category: "Adventure", researcher: "Eve", reservationDate: "06-21-2025", returnDate: "06-21-2025", daysOverdue: 5 },
                  { bookTitle: "War and Peace", category: "Historical", researcher: "Frank", reservationDate: "06-21-2025", returnDate: "06-21-2025", daysOverdue: 3 },
                ];

                // Function to populate a table with a "View" button
                function populateTable(containerId, data, columns) {
                  const container = document.getElementById(containerId);
                  if (container) {
                    const tbody = container.querySelector("tbody");
                    data.forEach((row) => {
                      const tr = document.createElement("tr");

                      // Add columns to the row
                      columns.forEach((column) => {
                        const td = document.createElement("td");
                        td.textContent = row[column];

                        // Add a class for "status" or other columns as necessary
                        if (column === "status" || column === "reservationDate" || column === "returnDate") {
                          td.classList.add(row[column].toLowerCase());
                        }

                        tr.appendChild(td);
                      });

                      // Add View button
                      const viewTd = document.createElement("td");
                      const viewButton = document.createElement("button");
                      viewButton.textContent = "View";
                      viewButton.classList.add("view-button");

                      viewTd.appendChild(viewButton);
                      tr.appendChild(viewTd);
                      tbody.appendChild(tr);
                    });
                  }
                }

                // Populate the tables with data and add View button
                populateTable("all-books-container", allBooksData, ["title", "category", "researcher", "reservationDate", "status"]);
                populateTable("reserved-books-container", reservedBooksData, ["bookTitle", "category", "researcher", "reservationDate"]);
                populateTable("returned-books-container", returnedBooksData, ["bookTitle", "category", "researcher", "reservationDate"]);
                populateTable("overtime-books-container", overtimeBooksData, ["bookTitle", "category", "researcher", "reservationDate", "returnDate", "daysOverdue"]);

                  function populateTable(containerId, data, columns) {
                    const container = document.getElementById(containerId);
                    if (container) {
                      const tbody = container.querySelector("tbody");
                      data.forEach((row) => {
                        const tr = document.createElement("tr");
                  
                        // Add columns to the row
                        columns.forEach((column) => {
                          const td = document.createElement("td");
                          td.textContent = row[column];
                          
                          if (column === "status") {
                            // Add a class to the status column based on the status value
                            td.classList.add(row[column].toLowerCase());
                          }
                  
                          tr.appendChild(td);
                        });

                  
                        // Add the "View" button
                        const viewTd = document.createElement("td");
                        const viewButton = document.createElement("button");
                        viewButton.textContent = "View";
                        viewButton.classList.add("view-button");

                        // Attach click event to "View" button to show reservation details
                        viewButton.addEventListener("click", () => showReservationDetails(row));

                        viewTd.appendChild(viewButton);
                        tr.appendChild(viewTd);
                        tbody.appendChild(tr);
                        
                      });
                    }
                  }
                </script>
                
            </div>

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