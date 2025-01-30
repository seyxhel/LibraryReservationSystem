// DateTime update function
function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = now.toLocaleDateString('en-US', options);
    const time = now.toLocaleTimeString('en-US');
    
    document.getElementById('current-date').textContent = date;
    document.getElementById('current-time').textContent = time;
}

// Initialize datetime updates
setInterval(updateDateTime, 1000);
window.onload = updateDateTime;

// Modal handling functions
function openModal(modalId) {
    const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = typeof modalId === 'string' ? document.getElementById(modalId) : modalId;
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Function to initialize the admin table with data
async function initializeAdminTable() {
    try {
        const response = await fetch('/admin/get-admins', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        console.log("Response status:", response.status);
        console.log("Response headers:", response.headers);

        if (!response.ok) {
            const errorText = await response.text(); // Log raw response text
            throw new Error(`Failed to fetch admin data: ${errorText}`);
        }

        const admins = await response.json();
        console.log("Fetched admins:", admins);

        const tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = '';

        admins.forEach(admin => {
            const row = document.createElement('tr');
            row.dataset.email = admin.Email;
            row.dataset.firstName = admin.FirstName;
            row.dataset.middleName = admin.MiddleName || '';
            row.dataset.surname = admin.LastName;
            row.dataset.suffix = admin.Suffix || '';
            row.dataset.gender = admin.Gender;
            row.dataset.contactNum = admin.ContactNumber;
            
            row.innerHTML = `
                <td>${admin.School_ID}</td>
                <td>${admin.Email}</td>
                <td>${admin.FirstName} ${admin.LastName}</td>
                <td>${createStatusDropdown(admin.Status)}</td>
                <td>
                    <button class="view-btn" onclick="viewAdmin(this)">View</button>
                </td>
            `;

            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error('Error loading admin data:', error);
        alert('Failed to load admin data. Please refresh the page.');
    }
}


// Status dropdown creation function
function createStatusDropdown(status) {
    return `
        <select class="status-select ${status.toLowerCase() === 'active' ? 'active' : 'inactive'} px-3 py-1 rounded-md cursor-pointer appearance-none focus:outline-none text-white font-medium" 
                onchange="updateAdminStatus(this)">
            <option value="ACTIVE" ${status.toUpperCase() === 'ACTIVE' ? 'selected' : ''}>ACTIVE</option>
            <option value="INACTIVE" ${status.toUpperCase() === 'INACTIVE' ? 'selected' : ''}>INACTIVE</option>
        </select>
    `;
}

// Admin status update function
async function updateAdminStatus(select) {
    const newStatus = select.value;
    const row = select.closest('tr');
    const schoolId = row.cells[0].textContent;

    try {
        const response = await fetch(`/admin/update-status/${schoolId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        if (!response.ok) {
            throw new Error('Failed to update status');
        }

        select.classList.remove('active', 'inactive');
        select.classList.add(newStatus.toLowerCase() === 'active' ? 'active' : 'inactive');

        const viewModal = document.getElementById('viewAdminModal');
        if (viewModal && viewModal.style.display === 'flex') {
            const statusElement = document.getElementById('viewStatus');
            if (statusElement) {
                statusElement.textContent = newStatus;
                statusElement.className = `mt-1 text-base ${newStatus === 'ACTIVE' ? 'text-green-500' : 'text-red-500'}`;
            }
        }
    } catch (error) {
        console.error('Error updating status:', error);
        alert('Failed to update status. Please try again.');
        // Revert the select value if the update failed
        select.value = select.value === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
    }
}

// View admin function
window.viewAdmin = function(button) {
    try {
        const row = button.closest('tr');
        if (!row) {
            console.error('Could not find parent row');
            return;
        }

        const fields = {
            'viewSchoolID': row.cells[0].textContent,
            'viewEmail': row.dataset.email,
            'viewFirstName': row.dataset.firstName,
            'viewMiddleName': row.dataset.middleName || 'N/A',
            'viewSurname': row.dataset.surname,
            'viewSuffix': row.dataset.suffix || 'N/A',
            'viewGender': row.dataset.gender,
            'viewContactNum': row.dataset.contactNum,
            'viewStatus': row.querySelector('.status-select')?.value || 'N/A'
        };

        Object.entries(fields).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
                // Add appropriate styling for status if it's the status field
                if (id === 'viewStatus') {
                    element.className = `mt-1 text-base ${value === 'ACTIVE' ? 'text-green-500' : 'text-red-500'}`;
                }
            }
        });

        openModal('viewAdminModal');
    } catch (error) {
        console.error('Error in viewAdmin:', error);
        alert('Error viewing admin details. Please try again.');
    }
};

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize the admin table
    initializeAdminTable();

    // Add Admin button event listener
    const addAdminBtn = document.getElementById('addAdminBtn');
    if (addAdminBtn) {
        addAdminBtn.addEventListener('click', () => openModal('addAdminModal'));
    }

    // Add Admin form submission handler
    const addAdminForm = document.getElementById('addAdminForm');
    if (addAdminForm) {
        addAdminForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/admin/add-admin', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        Email: formData.get('Email'),
                        School_ID: formData.get('School_ID'),
                        FirstName: formData.get('FirstName'),
                        MiddleName: formData.get('MiddleName'),
                        LastName: formData.get('LastName'),
                        Suffix: formData.get('Suffix'),
                        Gender: formData.get('Gender'),
                        ContactNumber: formData.get('ContactNumber'),
                        Password: formData.get('Password'),
                        Password_confirmation: formData.get('Password_confirmation')
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.errors ? Object.values(data.errors).flat().join('\n') : data.message);
                }

                if (data.success) {
                    alert('Admin added successfully!');
                    closeModal('addAdminModal');
                    addAdminForm.reset();
                    // Refresh the table to show the new admin
                    initializeAdminTable();
                }
            } catch (error) {
                alert(error.message);
                console.error('Error:', error);
            }
        });
    }

    // Change password form handler
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmNewPassword').value;
            
            if (newPassword !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
            if (!passwordRegex.test(newPassword)) {
                alert('Password must meet all requirements!');
                return;
            }
            
            closeModal('changePasswordModal');
            this.reset();
        });
    }

    // Modal close handlers
    document.querySelectorAll('.modal-background').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
    });

    // Close button handlers
    document.querySelectorAll('[onclick*="closeModal"]').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = button.getAttribute('onclick').match(/'([^']+)'/)?.[1];
            if (modalId) closeModal(modalId);
        });
    });

    // Escape key handler
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const visibleModal = document.querySelector('.modal-background[style*="flex"]');
            if (visibleModal) closeModal(visibleModal);
        }
    });

    // Function to load student data when the Students tab is selected
    document.getElementById('tab-box2').addEventListener('change', function() {
        if (this.checked) {
            loadStudentData();
        }
    });

    // Function to fetch and display student data
    function loadStudentData() {
        fetch('/get-students-list')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('studentTableBody');
                tableBody.innerHTML = ''; // Clear existing rows

                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.School_ID}</td>
                        <td>${student.Email}</td>
                        <td>${student.Name}</td>
                        <td><button class="view-btn" onclick="viewStudentModal('${student.School_ID}')">View Student</button></td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Load student data when page loads if student tab is active
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('tab-box2').checked) {
            loadStudentData();
        }
    });

    // Function to load admin data
function loadAdminData() {
    fetch('/get-admins-list')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // Clear existing rows

            data.forEach(admin => {
                const row = document.createElement('tr');
                const statusClass = admin.Status === 'active' ? 'status-active' : 'status-inactive';
                
                row.innerHTML = `
                    <td>${admin.School_ID}</td>
                    <td>${admin.Email}</td>
                    <td>${admin.Name}</td>
                    <td><span class="${statusClass}">${admin.Status}</span></td>
                    <td>
                        <button class="view-btn" onclick="viewAdminModal('${admin.School_ID}')">View Admin</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Load admin data when page loads if admin tab is active
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('tab-box1').checked) {
        loadAdminData();
    }
});

// Load admin data when switching to admin tab
document.getElementById('tab-box1').addEventListener('change', function() {
    if (this.checked) {
        loadAdminData();
    }
});

// Handle Add Admin button click
document.getElementById('addAdminBtn').addEventListener('click', function() {
    const modal = document.getElementById('addAdminModal');
    modal.style.display = 'flex';
});

// Handle admin view button click
function viewAdminModal(schoolId) {
    // Fetch specific admin details
    fetch(`/get-admin-details/${schoolId}`)
        .then(response => response.json())
        .then(admin => {
            // Populate modal fields
            document.getElementById('viewSchoolID').textContent = admin.School_ID;
            document.getElementById('viewEmail').textContent = admin.Email;
            document.getElementById('viewFirstName').textContent = admin.FirstName;
            document.getElementById('viewMiddleName').textContent = admin.MiddleName || 'N/A';
            document.getElementById('viewSurname').textContent = admin.LastName;
            document.getElementById('viewSuffix').textContent = admin.Suffix || 'N/A';
            document.getElementById('viewGender').textContent = admin.Gender;
            document.getElementById('viewContactNum').textContent = admin.ContactNumber;
            document.getElementById('viewStatus').textContent = admin.Status;

            // Show the modal
            document.getElementById('viewAdminModal').style.display = 'flex';
        })
        .catch(error => console.error('Error:', error));
}

// Function to close modals
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
});

// Additional utility functions
window.viewStudentModal = () => openModal('viewStudentModal');
window.changePasswordModal = () => openModal('changePasswordModal');