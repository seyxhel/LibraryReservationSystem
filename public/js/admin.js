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
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
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

// Function to load admin data
async function loadAdminTable() {
    const tableBody = document.getElementById('tableBody');
    if (!tableBody) {
        console.error('Table body element not found');
        return;
    }
    
    try {
        console.log('Fetching admin data...');
        const response = await fetch('/admin/get-admins', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response not ok:', errorText);
            throw new Error(`Failed to fetch admin data: ${errorText}`);
        }

        const admins = await response.json();
        console.log('Received admin data:', admins);
        
        tableBody.innerHTML = '';
        admins.forEach(admin => {
            const row = document.createElement('tr');
            row.dataset.email = admin.Email || '';
            row.dataset.firstName = admin.FirstName || '';
            row.dataset.middleName = admin.MiddleName || '';
            row.dataset.surname = admin.LastName || '';
            row.dataset.suffix = admin.Suffix || '';
            row.dataset.gender = admin.Gender || '';
            row.dataset.contactNum = admin.ContactNumber || '';
            
            row.innerHTML = `
                <td>${admin.School_ID || ''}</td>
                <td>${admin.Email || ''}</td>
                <td>${(admin.FirstName || '') + ' ' + (admin.LastName || '')}</td>
                <td>${createStatusDropdown(admin.Status || 'INACTIVE')}</td>
                <td>
                    <button class="view-btn" onclick="viewAdmin(this)">View</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error('Error in loadAdminTable:', error);
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-red-500 py-4">
                    Error loading admin data. Please check console and refresh the page.
                </td>
            </tr>
        `;
    }
}

// Function to load student data
async function loadStudentData() {
    try {
        const response = await fetch('/get-students-list');
        if (!response.ok) throw new Error('Failed to fetch students');

        const students = await response.json();
        const tableBody = document.getElementById('studentTableBody');
        tableBody.innerHTML = '';

        students.forEach(student => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${student.School_ID}</td>
                <td>${student.Email}</td>
                <td>${student.Name}</td>
                <td>
                    <button class="view-btn" onclick="viewStudent('${student.School_ID}')">View Student</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error('Error loading student data:', error);
    }
}

// Admin status update function
async function updateAdminStatus(select) {
    try {
        const newStatus = select.value;
        const row = select.closest('tr');
        const schoolId = row.cells[0].textContent;

        // Show loading state
        const originalColor = select.style.backgroundColor;
        select.style.backgroundColor = '#ccc';
        select.disabled = true;

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

        const data = await response.json();

        // Update dropdown styling
        select.classList.remove('active', 'inactive');
        select.classList.add(newStatus.toLowerCase() === 'active' ? 'active' : 'inactive');

        // Update view modal status if it's open
        updateViewModalStatus(newStatus);

        // Show success message
        showNotification('Status updated successfully', 'success');

    } catch (error) {
        console.error('Error updating status:', error);
        select.value = select.value === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        showNotification('Failed to update status. Please try again.', 'error');
    } finally {
        // Reset loading state
        select.disabled = false;
        select.style.backgroundColor = '';
    }
}

// View admin function
window.viewAdmin = function(button) {
    try {
        const row = button.closest('tr');
        if (!row) {
            throw new Error('Could not find parent row');
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

// View student function
window.viewStudent = async function(schoolID) {
    try {
        const response = await fetch(`/get-student/${schoolID}`);
        if (!response.ok) throw new Error('Failed to fetch student details');

        const student = await response.json();

        document.getElementById('viewStudentID').textContent = student.School_ID || '';
        document.getElementById('viewStudentEmail').textContent = student.Email || '';
        document.getElementById('viewStudentFirstName').textContent = student.FirstName || '';
        document.getElementById('viewStudentMiddleName').textContent = student.MiddleName || 'N/A';
        document.getElementById('viewStudentSurname').textContent = student.LastName || '';
        document.getElementById('viewStudentSuffix').textContent = student.Suffix || 'N/A';
        document.getElementById('viewStudentGender').textContent = student.Gender || '';
        document.getElementById('viewStudentContactNum').textContent = student.ContactNumber || '';

        openModal('viewStudentModal');
    } catch (error) {
        console.error('Error fetching student details:', error);
    }
};

// Function to update status in view modal
function updateViewModalStatus(newStatus) {
    const statusElement = document.getElementById('viewStatus');
    if (statusElement) {
        statusElement.textContent = newStatus;
        statusElement.className = `mt-1 text-base ${newStatus === 'ACTIVE' ? 'text-green-500' : 'text-red-500'}`;
    }
}

// Function to show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white text-sm font-medium z-50 transition-opacity duration-500`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Change password functionality
window.changePasswordModal = function() {
    openModal('changePasswordModal');
};

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing admin and student management system...');
    
    // Initialize tables
    loadAdminTable();
    
    if (document.getElementById('tab-box2')?.checked) {
        loadStudentData();
    }

    // Tab change handler
    document.getElementById('tab-box2')?.addEventListener('change', function() {
        if (this.checked) {
            loadStudentData();
        }
    });

    // Add Admin button handler
    const addAdminBtn = document.getElementById('addAdminBtn');
    if (addAdminBtn) {
        addAdminBtn.addEventListener('click', () => {
            openModal('addAdminModal');
        });
    }

    // Add Admin Form Handler
    const addAdminForm = document.getElementById('addAdminForm');
    if (addAdminForm) {
        addAdminForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const adminData = {};
            formData.forEach((value, key) => {
                adminData[key] = value;
            });

            if (!adminData.Suffix) adminData.Suffix = '';

            try {
                const response = await fetch('/admin/add-admin', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(adminData)
                });

                const data = await response.json();
                
                if (!response.ok) {
                    if (response.status === 422) {
                        const errorMessages = Object.entries(data.errors)
                            .map(([key, value]) => `${key}: ${value.join(', ')}`)
                            .join('\n');
                        alert('Validation errors:\n' + errorMessages);
                        return;
                    }
                    throw new Error(data.message || 'Failed to add admin');
                }

                showNotification('Admin added successfully!', 'success');
                closeModal('addAdminModal');
                this.reset();
                
                const genderSelect = document.getElementById('gender');
                if (genderSelect) genderSelect.selectedIndex = 0;
                
                await loadAdminTable();
                
            } catch (error) {
                console.error('Error adding admin:', error);
                showNotification(error.message || 'Failed to add admin. Please try again.', 'error');
            }
        });
    }

    // Change Password Form Handler
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmNewPassword').value;
            
            if (newPassword !== confirmPassword) {
                showNotification('Passwords do not match!', 'error');
                return;
            }
            
            const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
            if (!passwordRegex.test(newPassword)) {
                showNotification('Password must meet all requirements!', 'error');
                return;
            }
            
            closeModal('changePasswordModal');
            this.reset();
            showNotification('Password changed successfully!', 'success');
        });
    }
});

// Add CSS styles
const style = document.createElement('style');
style.textContent = `
    .status-select.active {
        background-color: #22c55e;
    }
    
    .status-select.inactive {
        background-color: #ef4444;
    }
    
    .status-select:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .notification {
        animation: fadeIn 0.3s ease-out;
    }
`;
document.head.appendChild(style);