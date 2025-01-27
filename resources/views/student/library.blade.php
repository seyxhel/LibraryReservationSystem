<!DOCTYPE html>
<html>
<head>
    <title>UNIARCHIVE</title>
    <link rel="stylesheet" href="{{ asset('css/Student.LibraryPage1.css') }}">
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
                      <a href="{{ route('student.dashboard') }}">
                        <span class="title">Dashboard</span>
                      </a>
                    </li>
                    <li>
                      <a class="active">
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

              <!-- New Content Container -->
              <div class="content-container">

                <div class="left-container">
                  <!-- For List -->
                  <div class="add-container">
                    <div id="item-list">
                      <ul id="list">
                        <!-- List items will be added dynamically here -->
                      </ul>
                    </div>
                  </div>
                </div>

            <!-- Right Container -->
            <div class="right-container">
              <h2>Filters</h2>
              
              <!-- Research Title Filter -->
              <label for="research-title-filter">Research Title:</label>
              <input type="text" id="research-title-filter" placeholder="Enter research title">
              
              <br><br>
              
              <!-- Category Filter -->
              <label for="category-filter">Category:</label>
              <select id="category-filter">
                <option value="">Select Category</option>
                <option value="Qualitative Research">Qualitative Research</option>
                <option value="Quantitative Research">Quantitative Research</option>
                <option value="Mixed-Methods Research">Mixed-Methods Research</option>
                <option value="Explanatory Research">Explanatory Research</option>
                <option value="Descriptive Research">Descriptive Research</option>
                <option value="Applied Research">Applied Research</option>
                <option value="Health and Medical Research">Health and Medical Research</option>
                <option value="Engineering and Technology Research">Engineering and Technology Research</option>
                <option value="Business and Management Research">Business and Management Research</option>
                <option value="Environmental Research">Environmental Research</option>
              </select>
              
              <br><br>
              
              <!-- Year Filter -->
              <label for="year-filter">Year:</label>
              <select id="year-filter">
                <option value="">Select Year</option>
                <!-- Populate the years dynamically with a 5-year difference -->
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <!-- Continue adding years in increments of 5 if needed -->
              </select>
              
              <br><br>
              
              <!-- Filter Button -->
              <button id="filter-btn">Apply Filters</button>
            </div>

                <!-- Details Container -->
                <div id="details-container" style="display:none;">
                  <h3 id="details-title"></h3>
                  <p id="details-researcher"></p>
                  <p id="details-date"></p>
                  <p id="details-category"></p>
                  <p id="details-year"></p>
                  <p id="details-status"></p>
                  <p id="details-abstract"></p> <!-- Abstract details -->
                  <button id="close-button" onclick="hideDetails()">Close</button>
                  <button id="reserve-button" onclick="reserveBook()">Reserve Book</button> <!-- Reserve Book button -->
              </div>

              <!-- Reservation Form Container -->
              <div id="reservation-form-container" style="display:none;">
                  <h2>Add Reservation</h2>
                  <form>
                      <label for="reservation-title">Title of Book:</label><br>
                      <input type="text" id="reservation-title" readonly><br>
                      
                      <div class="input-box">
        <label for="reservation-name">Name:</label><br>
        <input type="text" id="reservation-name" value="{{ Auth::guard('student')->user()->FirstName }} {{ Auth::guard('student')->user()->MiddleName }} {{ Auth::guard('student')->user()->LastName }}" readonly><br>
    </div>

    <div class="input-box">
        <label for="reservation-program">Program:</label><br>
        <input type="text" id="reservation-program" 
            value="{{ 
                Auth::guard('student')->user()->Program_ID == 1 ? 'Information Technology' : 
                (Auth::guard('student')->user()->Program_ID == 2 ? 'Home Economics' : 
                (Auth::guard('student')->user()->Program_ID == 3 ? 'Information Communication and Technology' : 
                (Auth::guard('student')->user()->Program_ID == 4 ? 'Human Resource Management' : 
                (Auth::guard('student')->user()->Program_ID == 5 ? 'Marketing Management' : 
                (Auth::guard('student')->user()->Program_ID == 6 ? 'Entrepreneurship' : 
                (Auth::guard('student')->user()->Program_ID == 7 ? 'Fiscal Administration' : 'Office Management Technology')))))) 
            }}" readonly><br>
    </div>

    <div class="input-box">
        <label for="reservation-contact">Contact Number:</label><br>
        <input type="text" id="reservation-contact" value="{{ Auth::guard('student')->user()->ContactNumber }}" readonly><br>
    </div>

    <div class="input-box">
        <label for="reservation-email">E-mail:</label><br>
        <input type="email" id="reservation-email" value="{{ Auth::guard('student')->user()->Email }}" readonly><br>
    </div>
      
    <div class="input-box" style="width: 100%; display: flex; flex-direction: column;">
    <label for="time-slot">Select Time Slot:</label>
    <div style="width: 100%; display: flex; justify-content: space-between; margin-top: 10px;">
        <div style="display: flex; align-items: center; gap: 5px;">
            <input type="radio" id="slot1" name="time_slot" value="7AM-9AM">
            <label for="slot1" style="margin-bottom: 15px;">7AM-9AM</label>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <input type="radio" id="slot2" name="time_slot" value="9AM-11AM">
            <label for="slot2" style="margin-bottom: 15px;">9AM-11AM</label>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <input type="radio" id="slot3" name="time_slot" value="11AM-1PM">
            <label for="slot3" style="margin-bottom: 15px;">11AM-1PM</label>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <input type="radio" id="slot4" name="time_slot" value="1PM-3PM">
            <label for="slot4" style="margin-bottom: 15px;">1PM-3PM</label>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <input type="radio" id="slot5" name="time_slot" value="3PM-5PM">
            <label for="slot5" style="margin-bottom: 15px;">3PM-5PM</label>
        </div>
    </div>
</div>


                      <button type="button" onclick="closeReservationForm()">Cancel</button>
                      <button type="button" onclick="confirmReservation()">Confirm</button>
                  </form>
              </div>
            </div>

              <script>
                const data = [
                    {
                        title: "Research on Quantum Mechanics",
                        author: "Dr. John Doe",
                        date: "2023-02-15",
                        status: "AVAILABLE",
                        category: "Science",
                        year: "2023",
                        abstract: "This research explores the fundamental principles of quantum mechanics and their applications in modern physics."
                    },
                    {
                        title: "Study on Artificial Intelligence",
                        author: "Dr. Jane Smith",
                        date: "2024-01-12",
                        status: "AVAILABLE",
                        category: "Technology",
                        year: "2024",
                        abstract: "A detailed study on the applications and future of artificial intelligence."
                    },
                    {
                        title: "Exploration of Virtual Assistants",
                        author: "Dr. Alice Brown",
                        date: "2023-11-30",
                        status: "RESERVED",
                        category: "Technology",
                        year: "2023",
                        abstract: "Exploring the development and impact of virtual assistants in modern-day businesses."
                    }
                ];
                
                // Function to render the list dynamically
                function renderList(items) {
                    const list = document.getElementById("list");
                    list.innerHTML = "";
  
                    items.forEach((item) => {
                        const listItem = document.createElement("li");
                        listItem.classList.add("item");
  
                        listItem.innerHTML = `
                            <h3>${item.title}</h3>
                            <p>${item.author}</p>
                            <p>${item.date}</p>
                            <span class="status ${item.status.toLowerCase()}">${item.status}</span>
                            <button class="open-btn">Open</button>
                        `;
  
                        // Add functionality to the "Open" button
                        listItem.querySelector(".open-btn").addEventListener("click", () => {
                            document.getElementById('details-title').textContent = item.title;
                            document.getElementById('details-researcher').textContent = `Researcher: ${item.author}`;
                            document.getElementById('details-date').textContent = `Date: ${item.date}`;
                            document.getElementById('details-category').textContent = `Category: ${item.category}`;
                            document.getElementById('details-year').textContent = `Year: ${item.year}`;
                            document.getElementById('details-status').textContent = `Status: ${item.status}`;
                            document.getElementById('details-abstract').textContent = `Abstract: ${item.abstract}`;
                            document.getElementById('details-container').style.display = 'block';
                        });
  
                        list.appendChild(listItem);
                    });
                }
  
                function hideDetails() {
                    document.getElementById('details-container').style.display = 'none';
                }
  
                function reserveBook() {
                    const title = document.getElementById('details-title').textContent;
                    document.getElementById('reservation-title').value = title; // Set title in the form
                    document.getElementById('reservation-form-container').style.display = 'block'; // Show reservation form
                }
  
                function closeReservationForm() {
                    document.getElementById('reservation-form-container').style.display = 'none'; // Hide reservation form
                }
  
                function confirmReservation() {
                    const title = document.getElementById('reservation-title').value;
                    const name = document.getElementById('reservation-name').value;
                    const section = document.getElementById('reservation-section').value;
                    const program = document.getElementById('reservation-program').value;
                    const contact = document.getElementById('reservation-contact').value;
                    const email = document.getElementById('reservation-email').value;
                    const datetimeReserve = document.getElementById('reservation-datetime-reserve').value;
                    const datetimeReturn = document.getElementById('reservation-datetime-return').value;
                
                    if (name && section && program && contact && email && datetimeReserve && datetimeReturn) {
                        alert(`Reservation confirmed for "${title}".\nName: ${name}\nProgram: ${program}`);
                        closeReservationForm();
                    } else {
                        alert("Please fill in all fields.");
                    }
                }
  
                // Render the list on page load
                renderList(data);
  
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
              </script>

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

        
        
      </div>

</body>
</html>