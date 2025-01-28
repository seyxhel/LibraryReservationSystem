<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1.0">
    <title>UNIARCHIVE - Reservation Handling</title>
    <link rel="stylesheet" 
          href="{{ asset('css/ADMIN-ReservationHandling3.css') }}">
    <link rel="stylesheet" []
          href="{{ asset('css/AA-Responsive-ReservationHandling.css') }}">
    <link rel="stylesheet" []
          href="{{ asset('css/CC-BookInvTable.css') }}">
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
                  <a href="profile.html">Edit Account</a>
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

    <!-- for navigation part -->
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
                

                    <div class="nav-option option5">
                        <img src= "{{ asset('assets/13-inventory.png') }}"
                                class="nav-img" 
                                alt="logout">
                        <h3>
                            <a class="nav-link">Book Inventory</a>
                        </h3>
                    </div>        
                </div>
            </nav>
        </div>

        <div class="main">
            <!--BODY CONTENT PAGE-->    
            <div class="dashboard-title">
                BOOK INVENTORY
            <div class="date-time">
                <span id="current-date"></span>
                <span id="current-time"></span>
            </div>
        </div>
            
            <div class="main-content">
                <div class="table-container">                    
                    <button class="add-book-btn" id="addBookBtn">Add Book</button>
                    <table class="inventory-table" id="bookTable">
                        <thead>
                        <tr>
                            <th>Book Number</th>
                            <th>Title</th>
                            <th>Researchers</th>
                            <th>Location</th>
                            <th>Book Code</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                        <tr>
                            <td>0001</td>
                            <td>Sample 1</td>
                            <td>Jane Smith</td>
                            <td>Book shelf 3</td>
                            <td>2020</td>
                            <td>ACTIVE</td>
                            <td>Descriptive Research</td>
                            <td>
                            <button class="view-btn" onclick="viewBook(this)">View</button>
                            <button class="edit-btn" onclick="editBook(this)">Edit</button>
                            <button class="delete-btn" onclick="deleteBook(this)">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
    </div>

    <!-- Add Book Modal -->
    <div id="addBookModal" class="modal-background flex justify-center items-center h-screen" style="display: none;">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-semibold">Add Book</h2>
                <button class="text-gray-500 hover:text-gray-700 text-3xl p-2" onclick="closeModal(addBookModal)">&times;</button>
            </div>
            <form id="addBookForm">
                <div class="mb-4">
                    <label for="bookLabel" class="block text-sm font-medium">Book Label</label>
                    <input type="text" id="bookLabel" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="bookNumber" class="block text-sm font-medium">Book Number</label>
                    <input type="text" id="bookNumber" class="text-field mt-1 block w-full rounded-md shadow-sm" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="mb-4">
                    <label for="bookCode" class="block text-sm font-medium">Book Code</label>
                    <input type="text" id="bookCode" class="text-field mt-1 block w-full rounded-md shadow-sm" maxlength="6" pattern="[A-Za-z0-9]+" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase()" required>
                </div>
                <div class="mb-4">
                    <label for="researchTitle" class="block text-sm font-medium">Research Title</label>
                    <input type="text" id="researchTitle" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="researcher" class="block text-sm font-medium">Researcher</label>
                    <input type="text" id="researcher" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="abstract" class="block text-sm font-medium">Abstract</label>
                    <textarea id="abstract" class="text-field mt-1 block w-full rounded-md shadow-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="heldBy" class="block text-sm font-medium">Held By</label>
                    <input type="text" id="heldBy" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium">Location</label>
                    <select id="location" class="text-field mt-1 block w-full rounded-md shadow-sm">
                        <option disabled selected>Select Bookshelf</option>
                        <option>Bookshelf 1</option>
                        <option>Bookshelf 2</option>
                        <option>Bookshelf 3</option>
                        <option>Bookshelf 4</option>
                        <option>Bookshelf 5</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium">Category</label>
                    <select id="category" class="text-field mt-1 block w-full rounded-md shadow-sm">
                        <option disabled selected>Select Category</option>
                        <option>Qualitative Research</option>
                        <option>Quantitative Research</option>
                        <option>Mixed-Methods Research</option>
                        <option>Explanatory Research</option>
                        <option>Descriptive Research</option>
                        <option>Applied Research</option>
                        <option>Basic Research</option>
                        <option>Ethnographic Research</option>
                        <option>Engineering and Technology Research</option>
                        <option>Correlational Research</option>
                        <option>Environmental Research</option>
                        <option>Case Studies</option>
                        <option>Historical Research</option>
                        <option>Phenomonological Research</option>
                        <option>Grounded Theory</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md" onclick="closeModal(addBookModal)">Cancel</button>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md">Save Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Book Modal-->
    <div id="editBookModal" class="modal-background flex justify-center items-center h-screen" style="display: none;">
        <div class="modal-content-edit bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h2 class="text-xl font-semibold">Edit Book</h2>
            <button class="text-gray-500 hover:text-gray-700 text-3xl p-2" onclick="closeModal('editBookModal')">&times;</button>
        </div>
            <form id="editBookForm">
                <div class="mb-4">
                    <label for="bookLabel" class="block text-sm font-medium">Book Label</label>
                    <input type="text" id="editBookLabel" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="bookNumber" class="block text-sm font-medium">Book Number</label>
                    <input type="number" id="editBookNumber" class="text-field mt-1 block w-full rounded-md shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                </div>
                <div class="mb-4">
                    <label for="bookCode" class="block text-sm font-medium">Book Code</label>
                    <input type="text" id="editBookCode" class="text-field mt-1 block w-full rounded-md shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                </div>
                <div class="mb-4">
                    <label for="researchTitle" class="block text-sm font-medium">Research Title</label>
                    <input type="text" id="editBookTitle" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="researcher" class="block text-sm font-medium">Researcher</label>
                    <input type="text" id="editBookResearcher" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="abstract" class="block text-sm font-medium">Abstract</label>
                    <textarea id="editBookAbstract" class="text-field mt-1 block w-full rounded-md shadow-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="heldBy" class="block text-sm font-medium">Held By</label>
                    <input type="text" id="editBookHeldBy" class="text-field mt-1 block w-full rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium">Location</label>
                    <select id="editLocation" class="text-field mt-1 block w-full rounded-md shadow-sm">
                        <option disabled selected>Select Bookshelf</option>
                        <option>Bookshelf 1</option>
                        <option>Bookshelf 2</option>
                        <option>Bookshelf 3</option>
                        <option>Bookshelf 4</option>
                        <option>Bookshelf 5</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium">Category</label>
                    <select id="editBookCategory" class="text-field mt-1 block w-full rounded-md shadow-sm">
                        <option disabled selected>Select Category</option>
                        <option>Qualitative Research</option>
                        <option>Quantitative Research</option>
                        <option>Mixed-Methods Research</option>
                        <option>Explanatory Research</option>
                        <option>Descriptive Research</option>
                        <option>Applied Research</option>
                        <option>Basic Research</option>
                        <option>Ethnographic Research</option>
                        <option>Engineering and Technology Research</option>
                        <option>Correlational Research</option>
                        <option>Environmental Research</option>
                        <option>Case Studies</option>
                        <option>Historical Research</option>
                        <option>Phenomonological Research</option>
                        <option>Grounded Theory</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium">Status</label>
                    <select id="editBookStatus" class="text-field mt-1 block w-full rounded-md shadow-sm">
                        <option disabled selected>Select Status</option>
                        <option>ACTIVE</option>
                        <option>INACTIVE</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4 border-t pt-4 mt-6">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md" onclick="closeModal('editBookModal')">Cancel</button>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md">Save Book</button>
                </div>
            </form>
        </div>
    </div>

    <!--Nav Bar Script-->
    <script src="{{ asset('js/index.js') }}"></script>
    <script>


    // Book Inventory Management Script
    document.addEventListener('DOMContentLoaded', () => {
        // Selecting modal elements
        const addBookModal = document.getElementById('addBookModal');
        const editBookModal = document.getElementById('editBookModal');
        const addBookBtn = document.getElementById('addBookBtn');
        loadTableData();

        // Function to load table data
        function loadTableData() {
            const tableData = JSON.parse(localStorage.getItem('bookTableData')) || [];
            const tableBody = document.getElementById('tableBody');
            
            tableData.forEach(book => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${book.bookNumber}</td>
                    <td>${book.bookLabel}</td>
                    <td>${book.researcher}</td>
                    <td>${book.location}</td>
                    <td>${book.bookCode}</td>
                    <td>${book.status}</td>
                    <td>${book.category}</td>
                    <td>
                        <button class="view-btn" onclick="viewBook(this)">View</button>
                        <button class="edit-btn" onclick="editBook(this)">Edit</button>
                        <button class="delete-btn" onclick="deleteBook(this)">Delete</button>
                    </td>
                `;
                tableBody.appendChild(newRow);
            });
        }

        // Open modal function
        function openModal(modal) {
            if (modal) modal.style.display = 'flex';
        }

        // Close Modal Function
        function closeModal(modal) {
            modal.style.display = "none"; // Hide the modal
        }

        // Add Book Modal Functionality
        addBookBtn.addEventListener('click', () => openModal(addBookModal));

        // Close buttons for both modals
        const closeButtons = document.querySelectorAll('.modal-content button[onclick^="closeModal"]');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => closeModal(addBookModal));
        });

        // Event listeners for buttons
        document.querySelectorAll('[onclick^="closeModal"]').forEach(button => {
            button.addEventListener('click', () => closeModal(editBookModal));
        });

        // Add Book Form Submission
        const addBookForm = document.getElementById('addBookForm');
        addBookForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect book details
            const bookDetails = {
                bookNumber: document.getElementById('bookNumber').value,
                bookCode: document.getElementById('bookCode').value,
                bookLabel: document.getElementById('bookLabel').value,
                researchTitle: document.getElementById('researchTitle').value,
                researcher: document.getElementById('researcher').value,
                location: document.getElementById('location').value,
                category: document.getElementById('category').value,
                abstract: document.getElementById('abstract').value,
                heldBy: document.getElementById('heldBy').value,
                status: 'ACTIVE'
            };

            // Save to localStorage
            const existingData = JSON.parse(localStorage.getItem('bookTableData')) || [];
            existingData.push(bookDetails);
            localStorage.setItem('bookTableData', JSON.stringify(existingData));

            // Create table row
            const tableBody = document.getElementById('tableBody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${bookDetails.bookNumber}</td>
                <td>${bookDetails.bookLabel}</td>
                <td>${bookDetails.researcher}</td>
                <td>${bookDetails.location}</td>
                <td>${bookDetails.bookCode}</td>
                <td>${bookDetails.status}</td>
                <td>${bookDetails.category}</td>
                <td>
                    <button class="view-btn" onclick="viewBook(this)">View</button>
                    <button class="edit-btn" onclick="editBook(this)">Edit</button>
                    <button class="delete-btn" onclick="deleteBook(this)">Delete</button>
                </td>
            `;

            // Add row to table
            tableBody.appendChild(newRow);

            // Close modal
            closeModal(addBookModal);

            // Reset form
            addBookForm.reset();
        });

        // Edit Book Functionality
        function editBook(button) {
            const row = button.closest('tr'); // Find the row containing the book
            const cells = row.getElementsByTagName('td');

            // Get the modal
            const editBookModal = document.getElementById('editBookModal');

            // Get stored data
            const bookNumber = cells[0].textContent;
            const existingData = JSON.parse(localStorage.getItem('bookTableData')) || [];
            const bookData = existingData.find(book => book.bookNumber === bookNumber);

            // Populate all form fields with existing data
            document.getElementById('editBookNumber').value = cells[0].textContent;    // Book Number
            document.getElementById('editBookCode').value = cells[4].textContent;      // Book Code
            document.getElementById('bookLabel').value = cells[1].textContent;     // Book Label
            document.getElementById('editBookResearcher').value = cells[2].textContent; // Researcher
            document.getElementById('editLocation').value = cells[3].textContent;      // Location
            document.getElementById('editBookStatus').value = cells[5].textContent;    // Status
            document.getElementById('editBookCategory').value = cells[6].textContent;  // Category
            document.getElementById('editBookAbstract').value = cells[7]?.textContent || ''; // Abstract
            document.getElementById('editBookHeldBy').value = cells[8]?.textContent || '';   // HeldBy

            // Close Modal Function
            function closeModal(modal) {
                modal.style.display = "none"; // Hide the modal
            }

            // Open the modal
            openModal(editBookModal);

            // Store reference to the row being edited
            editBookModal.dataset.editingRow = row.rowIndex - 1; // Account for header row
        }

        // Edit Book Form Submission
        const editBookForm = document.getElementById('editBookForm');
        editBookForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Get the modal and the row being edited
            const editBookModal = document.getElementById('editBookModal');
            const rowIndex = parseInt(editBookModal.dataset.editingRow);
            const tableBody = document.getElementById('tableBody');
            const row = tableBody.rows[rowIndex];

            // Update row cells with new values
            row.cells[1].textContent = document.getElementById('editBookLabel').value;
            row.cells[2].textContent = document.getElementById('editResearchTitle').value;
            row.cells[3].textContent = document.getElementById('editBookResearcher').value;
            row.cells[4].textContent = document.getElementById('editLocation').value;
            row.cells[5].textContent = document.getElementById('editBookCode').value;
            row.cells[6].textContent = document.getElementById('editBookStatus').value;
            row.cells[7].textContent = document.getElementById('editBookCategory').value;
            row.cells[8].textContent = document.getElementById('editBookAbstract').value;
            row.cells[9].textContent = document.getElementById('editBookHeldBy').value;

            // Close modal
            closeModal(editBookModal);

            // Reset form
            editBookForm.reset();
        });

        // Expose functions globally if needed
        window.editBook = editBook;
        window.deleteBook = deleteBook;

        // Optional: DateTime update function
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('en-US', options);
            const time = now.toLocaleTimeString('en-US');
            
            document.getElementById('current-date').textContent = date;
            document.getElementById('current-time').textContent = time;
        }

        // Update date and time initially and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });

    // Placeholder for view function (to be implemented)
    function viewBook(button) {
        alert('View functionality to be implemented');
    }

    // Delete Function
    function deleteBook(button) {
    if (confirm("Are you sure you want to delete this book?")) {
        const row = button.closest('tr');
        const bookNumber = row.cells[0].textContent;
        
        // Remove from localStorage
        let tableData = JSON.parse(localStorage.getItem('bookTableData')) || [];
        tableData = tableData.filter(book => book.bookNumber !== bookNumber);
        localStorage.setItem('bookTableData', JSON.stringify(tableData));
        
        // Remove row from table
        row.remove();
        alert("Book has been deleted successfully!");
    }
}
    </script>

</body>
</html>
