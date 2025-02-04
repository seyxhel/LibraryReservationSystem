<!DOCTYPE html>
<html>
<head>
    <title>UNIARCHIVE</title>
    <link rel="stylesheet" href="{{ asset('css/Student.LibraryPage1.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                                @if($books->isNotEmpty())
                                    @foreach($books as $book)
                                        <li class="item">
                                            <h3>{{ $book['title'] }}</h3>
                                            <p>Author: {{ $book['author'] }}</p>
                                            <p>Date: {{ $book['date'] }}</p>
                                            <span class="status {{ strtolower($book['status']) }}">{{ $book['status'] }}</span>
                                            <button class="open-btn"
                                            onclick="viewDetails('{{ $book['id'] }}', '{{ addslashes($book['title']) }}', '{{ addslashes($book['author']) }}', '{{ $book['date'] }}', '{{ addslashes($book['category']) }}', '{{ $book['year'] }}', '{{ $book['status'] }}', '{{ addslashes($book['abstract']) }}')">
                                            Open
                                        </button>
                                        </li>
                                    @endforeach
                                @else
                                    <li>No books available at the moment.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="right-container">
                    <h2>Filters</h2>
                    <!-- Research Title Filter -->
                    <label for="research-title-filter">Research Title:</label>
                    <input type="text" id="research-title-filter" placeholder="Enter research title">

                    <br><br>

                    <!-- Category Filter -->
                    <select id="category-filter">
                        <option value=""disabled selected>Select Category</option>
                        @forelse($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @empty
                            <option value="" disabled>No categories available</option>
                        @endforelse
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
                    <button id="clear-btn">Clear Filters</button>
                  </div>
            </div>

            <div id="details-container" style="display:none;">
                <h2>Book Details</h2>
                <p id="details-title"></p>
                <p id="details-author"></p>
                <p id="details-date"></p>
                <p id="details-category"></p>
                <p id="details-year"></p>
                <p id="details-status"></p>
                <p id="details-abstract"></p>

                <button onclick="reserveBook()">Reserve</button>
                <button onclick="closeDetails()">Close</button>
            </div>


<!-- Reservation Form Container -->
<div id="reservation-form-container" style="display:none;">
    <h2>Add Reservation</h2>
    <form id="reservation-form">
        @csrf
        <input type="hidden" id="book-id" name="bookId" value="{{ $bookID ?? '' }}"> <!-- Hidden field for bookID -->

        <label for="reservation-title">Title of Book:</label><br>
        <input type="text" id="reservation-title" value="{{ $bookTitle ?? '' }}" readonly><br> <!-- Set book title dynamically -->

        <div class="input-box">
            <label for="reservation-name">Name:</label><br>
            <input type="text" id="reservation-name"
                value="{{ Auth::guard('student')->user()->FirstName }}
                       {{ Auth::guard('student')->user()->MiddleName }}
                       {{ Auth::guard('student')->user()->LastName }}"
                readonly><br>
        </div>

        <div class="input-box">
            <label for="reservation-program">Program:</label><br>
            <input type="text" id="reservation-program"
                value="{{ [
                    1 => 'Information Technology',
                    2 => 'Home Economics',
                    3 => 'Information Communication and Technology',
                    4 => 'Human Resource Management',
                    5 => 'Marketing Management',
                    6 => 'Entrepreneurship',
                    7 => 'Fiscal Administration',
                    8 => 'Office Management Technology'
                ][Auth::guard('student')->user()->Program_ID] ?? 'Unknown' }}"
                readonly><br>
        </div>

        <div class="input-box">
            <label for="reservation-contact">Contact Number:</label><br>
            <input type="text" id="reservation-contact"
                value="{{ Auth::guard('student')->user()->ContactNumber }}" readonly><br>
        </div>

        <div class="input-box">
            <label for="reservation-email">E-mail:</label><br>
            <input type="email" id="reservation-email"
                value="{{ Auth::guard('student')->user()->Email }}" readonly><br>
        </div>

        <div class="input-box">
            <label for="reservation-date">Reservation Date:</label><br>
            <input type="date" id="reservation-date" name="ReservationDate" required><br>
        </div>

        <!-- Time Slot Dropdown -->
        <div class="input-box" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
            <label for="time-slot">Select Time Slot:</label>
            <select id="time-slot" name="TimeSlot" style="width: 200px; margin-top: 10px;">
                <option value="">Select a Time Slot</option>
                @foreach ([    '7AM-9AM','9AM-11AM','11AM-1PM','1PM-3PM','3PM-5PM'] as $slot)
                    <option value="{{ $slot }}">{{ $slot }}</option>
                @endforeach
            </select>
        </div>

        <div id="error-message" style="color: red; margin-top: 10px;"></div>

        <button type="button" class="btn btn-secondary" onclick="closeReservationForm()">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="confirmReservation()">Confirm</button>
    </form>
</div>

<!-- SCRIPT FOR VIEW DETAILS-->
  <script>
        function viewDetails(bookId, title, author, date, category, year, status, abstractText) {
             document.getElementById('book-id').value = bookId; // Set book ID for reservation
             document.getElementById('details-title').textContent = title;
             document.getElementById('details-author').textContent = "Author: " + author;
             document.getElementById('details-date').textContent = "Date: " + date;
             document.getElementById('details-category').textContent = "Category: " + category;
             document.getElementById('details-year').textContent = "Year: " + year;
             document.getElementById('details-status').textContent = "Status: " + status;
             document.getElementById('details-abstract').textContent = abstractText;
            // Store the bookId for reservation use
                currentBookId = bookId;
            // Show the details container
                document.getElementById('details-container').style.display = 'block';
        }

        function hideDetails() {
            document.getElementById('details-container').style.display = 'none';
            }

                // Function to render the list dynamically //VIEW LIST OF BOOKS
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

                //FILTER FUNCTIONS
                document.getElementById("filter-btn").addEventListener("click", function() {
                let title = document.getElementById("research-title-filter").value;
                let category = document.getElementById("category-filter").value;
                let year = document.getElementById("year-filter").value;

                // Add console log to see what's being sent
                console.log('Sending request with:', { title, category, year });

                fetch(`/library/search?title=${title}&category=${category}&year=${year}`)
                .then(response => {
                    // Add this to see the raw response
                    console.log('Raw response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);  // Add this to see the data

                    if (data.error) {
                        console.error('Server error:', data);
                        throw new Error(data.message || 'Server error occurred');
                    }

                    let resultsContainer = document.getElementById("list");
                    resultsContainer.innerHTML = "";

                    if (data.length === 0) {
                        resultsContainer.innerHTML = "<li>No books found.</li>";
                        return;
                    }

                    data.forEach(book => {
                        let bookItem = document.createElement("li");
                        bookItem.classList.add("item");

                        // Log each book's data
                        console.log('Processing book:', book);

                        bookItem.innerHTML = `
                            <h3>${book.title}</h3>
                            <p>Author: ${book.author}</p>
                            <p>Year: ${book.year}</p>
                            <p>Category: ${book.category}</p>
                            <p>Status: <span class="status ${book.status.toLowerCase().replace(/ /g, "-")}">${book.status}</span></p>
                            <button class="open-btn" onclick="viewDetails('${book.title}', '${book.author}', '${book.date}', '${book.category}', '${book.year}', '${book.status}', '${book.abstract.replace(/'/g, "\\'")}')">Open</button>
                        `;

                        resultsContainer.appendChild(bookItem);
                    });
                })

        .catch(error => {
        // More detailed error logging
        console.error("Detailed error:", {
            message: error.message,
            error: error
            });
            alert('Error: ' + error.message);
            });
        });
            document.getElementById("clear-btn").addEventListener("click", function() {
            document.getElementById("research-title-filter").value = "";
            document.getElementById("category-filter").selectedIndex = 0;
            document.getElementById("year-filter").selectedIndex = 0;

            // Fetch all books from the original page load (not search API)
            fetch(`/library`)
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                let originalList = doc.getElementById("list").innerHTML;
                document.getElementById("list").innerHTML = originalList;
            })
            .catch(error => console.error("Error resetting books:", error));
        });


//RESERVATIONS
let currentBookId = null;

// Function to show reservation form
function reserveBook() {
    if (!currentBookId) {
        document.getElementById('error-message').textContent = "Error: Book ID is missing.";
        return;
    }

    // Set book ID and title in form
    document.getElementById('book-id').value = currentBookId;
    const title = document.getElementById('details-title').textContent;
    document.getElementById('reservation-title').value = title;

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('reservation-date').min = today;

    // Reset time slot and error message
    document.getElementById('time-slot').value = "";
    document.getElementById('error-message').textContent = "";

    // Hide details and show reservation form
    document.getElementById('reservation-form-container').style.display = 'block';
    document.getElementById('details-container').style.display = 'none';
}

// Function to close reservation form
function closeReservationForm() {
    // Reset form fields
    document.getElementById('reservation-date').value = "";
    document.getElementById('time-slot').value = "";
    document.getElementById('error-message').textContent = "";

    // Hide reservation form
    document.getElementById('reservation-form-container').style.display = 'none';

    // Show details container again
    document.getElementById('details-container').style.display = 'block';
}

// Function to close details view
function closeDetails() {
    // Hide both containers
    document.getElementById('details-container').style.display = 'none';
    document.getElementById('reservation-form-container').style.display = 'none';

    // Reset any error messages
    document.getElementById('error-message').textContent = "";
}

// Function to handle reservation confirmation
// First, update your frontend JavaScript:

    function confirmReservation() {
        // Get form values
        const selectedSlot = document.getElementById('time-slot').value;
        const selectedDate = document.getElementById('reservation-date').value;
        const errorMessageElement = document.getElementById('error-message');

        // Clear previous error message
        errorMessageElement.textContent = "";

        // Basic validation
        if (!selectedSlot || !selectedDate) {
            errorMessageElement.textContent = "Please select both a date and time slot.";
            return;
        }

        // Create form data
        const formData = new FormData();
        formData.append('bookId', currentBookId);
        formData.append('reservationDate', selectedDate);
        formData.append('timeSlot', selectedSlot);

        // Show loading state
        errorMessageElement.textContent = "Processing reservation...";

        fetch('/reservation', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            // Always try to parse JSON response
            return response.json().then(data => {
                // Add the status to the parsed data
                return { ...data, status: response.status };
            });
        })
        .then(data => {
            console.log('Server response:', data);

            if (data.success) {
                // Success case
                alert("Reservation successful!");
                closeReservationForm();
                location.reload();
            } else {
                // Error case
                let errorMessage = data.message;
                if (data.errors) {
                    // Handle validation errors
                    errorMessage = Object.values(data.errors).flat().join('\n');
                }
                errorMessageElement.textContent = errorMessage || "Reservation failed. Please try again.";
            }
        })
        .catch(error => {
            console.error('Reservation error:', error);
            errorMessageElement.textContent = "An error occurred while processing your reservation. Please try again.";
        });
    }

    // Function to check availability before reservation
    function checkAvailability(bookId, date, timeSlot) {
        return fetch(`/check-availability?bookId=${bookId}&reservationDate=${date}&timeSlot=${timeSlot}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to check availability');
            }
            return data.data.available;
        });
    }

    // Function to close reservation form
    function closeReservationForm() {
        const form = document.getElementById('reservation-form');
        if (form) form.reset();

        const errorElement = document.getElementById('error-message');
        if (errorElement) errorElement.textContent = '';

        const formContainer = document.getElementById('reservation-form-container');
        if (formContainer) formContainer.style.display = 'none';
    }
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
        <input type="email" class="input-field" id="email" placeholder="E-mail Address" value="{{ $student->Email }}" readonly autocomplete="off">
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

    <script>
        // Add this script to your blade file or a separate JS file
        document.addEventListener('DOMContentLoaded', function () {
        let currentBookId = null;

    window.viewDetails = function (title, author, date, category, year, status, abstract, bookId) {
        console.log("Book ID received:", bookId); // Debugging
        currentBookId = bookId;

        document.getElementById('details-title').textContent = title;
        document.getElementById('details-researcher').textContent = `Author: ${author}`;
        document.getElementById('details-date').textContent = `Date: ${date}`;
        document.getElementById('details-category').textContent = `Category: ${category}`;
        document.getElementById('details-year').textContent = `Year: ${year}`;
        document.getElementById('details-status').textContent = `Status: ${status}`;
        document.getElementById('details-abstract').textContent = `Abstract: ${abstract}`;

        document.getElementById('details-container').style.display = 'block';
    };

    window.closeDetails = function () {
            console.log("Closing details container"); // Debugging
            document.getElementById('details-container').style.display = 'none';
        };


    window.confirmReservation = function () {
        const reservationDate = document.getElementById('reservation-date')?.value;
        const timeSlot = document.getElementById('time-slot').value;

        if (!reservationDate || !timeSlot) {
            alert("Please select both a date and time slot.");
            return;
        }

        if (!currentBookId) {
            alert("Error: Book ID is missing. Please try again.");
            return;
        }

        console.log("Checking availability with:", currentBookId, reservationDate, timeSlot);

        // First check availability
        fetch(`/check-availability?bookId=${currentBookId}&reservationDate=${reservationDate}&timeSlot=${timeSlot}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    alert("This time slot is no longer available. Please select another.");
                    return; // Prevents unnecessary function calls
                }
                submitReservation(reservationDate, timeSlot);
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error checking availability. Please try again.");
            });
    };

    function submitReservation(reservationDate, timeSlot) {
        const formData = new FormData();
        formData.append("bookId", parseInt(currentBookId));
        // Ensure the key matches Laravel's request validation
        formData.append("reservationDate", reservationDate);
        formData.append("timeSlot", timeSlot);

        console.log("Submitting reservation:", {
            bookID: currentBookId,
            reservationDate,
            timeSlot,
        });

        fetch("/reservation", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                console.log("Response received:", data);

                if (data.success) {
                    alert("Book reserved successfully!");
                    closeReservationForm();
                    location.reload();
                } else {
                    alert(data.message || "Failed to reserve book. Please try again.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while processing your reservation.");
            });
    }

    window.closeReservationForm = function () {
        document.getElementById('reservation-form-container').style.display = 'none';
    };
});

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handlers for close buttons if they exist
        const closeButtons = document.querySelectorAll('.close-btn, .btn-cancel');
        closeButtons.forEach(button => {
            button.addEventListener('click', closeReservationForm);
        });
    });
</script>
    </body>
</html>
