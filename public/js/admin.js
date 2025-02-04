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
        console.log('Fetched students:', students); // ✅ Debug log

        const tableBody = document.getElementById('studentTableBody');
        tableBody.innerHTML = '';

        students.forEach(student => {
            console.log('Processing student:', student); // ✅ Debug each student

            const row = document.createElement('tr');

            row.dataset.schoolId = student.School_ID || 'N/A';
            row.dataset.email = student.Email || 'N/A';
            row.dataset.firstName = student.FirstName || 'N/A';
            row.dataset.middleName = student.MiddleName || 'N/A';
            row.dataset.surname = student.LastName || 'N/A';
            row.dataset.suffix = student.Suffix || 'N/A';
            row.dataset.gender = student.Gender || 'N/A';
            row.dataset.contactNum = student.ContactNumber || 'N/A';

            row.innerHTML = `
                <td>${student.School_ID || 'N/A'}</td>
                <td>${student.Email || 'N/A'}</td>
                <td>${student.FirstName || ''} ${student.LastName || ''}</td>
                <td>
                    <button class="view-btn" onclick="viewStudent(this)">View or Change Student Password</button>
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
        const adminId = row.dataset.adminId; // Ensure dataset.adminId is present

        // Show loading state
        const originalColor = select.style.backgroundColor;
        select.style.backgroundColor = '#ccc';
        select.disabled = true;

        const response = await fetch(`/admin/update-status/${adminId}`, {  // Use Admin_ID instead
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
window.viewStudent = function(button) {
    const row = button.closest('tr');
    if (!row) {
        console.error('Could not find the row.');
        return;
    }

    console.log('Row dataset:', row.dataset); // ✅ Debug row dataset

    const viewStudentModal = document.getElementById('viewStudentModal');
    if (!viewStudentModal) {
        console.error('View Student Modal not found.');
        return;
    }

    const studentEmail = row.dataset.email || 'N/A';

    // Update the change password button with the correct email
    const changePasswordBtn = viewStudentModal.querySelector('button[onclick*="openChangePasswordModal"]');
    if (changePasswordBtn) {
        changePasswordBtn.setAttribute('onclick', `openChangePasswordModal('${studentEmail}')`);
    }

    // Populate modal fields
    document.getElementById('viewStudentID').textContent = row.dataset.schoolId || 'N/A';
    document.getElementById('viewStudentEmail').textContent = studentEmail;
    document.getElementById('viewStudentFirstName').textContent = row.dataset.firstName || 'N/A';
    document.getElementById('viewStudentMiddleName').textContent = row.dataset.middleName || 'N/A';
    document.getElementById('viewStudentSurname').textContent = row.dataset.surname || 'N/A';
    document.getElementById('viewStudentSuffix').textContent = row.dataset.suffix || 'N/A';
    document.getElementById('viewStudentGender').textContent = row.dataset.gender || 'N/A';
    document.getElementById('viewStudentContactNum').textContent = row.dataset.contactNum || 'N/A';

    // Open modal
    openModal('viewStudentModal');
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

window.openChangePasswordModal = function(studentEmail) {
    if (!studentEmail) {
        showNotification('Student email not found', 'error');
        return;
    }
    document.getElementById('changePasswordStudentEmail').value = studentEmail;
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
    document.getElementById('changePasswordForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const studentEmail = document.getElementById('changePasswordStudentEmail').value; // Hidden field with email
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmNewPassword').value;

        if (newPassword !== confirmPassword) {
            showNotification('Passwords do not match!', 'error');
            return;
        }

        try {
            const response = await fetch('/admin/change-student-password', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: studentEmail, newPassword, confirmNewPassword: confirmPassword })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to update password.');
            }

            showNotification('Password changed successfully!', 'success');
            closeModal('changePasswordModal');

        } catch (error) {
            console.error('Error changing password:', error);
            showNotification(error.message || 'Failed to change password.', 'error');
        }
    });
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
