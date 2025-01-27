// Select all required elements
const contactInput = document.getElementById("contact");
const formFields = document.querySelectorAll("input:not([type='checkbox']), select");
const privacyCheckbox = document.getElementById('privacy-checkbox');
const termsCheckbox = document.getElementById('terms-checkbox');
const continueButton = document.getElementById('continueButton'); // Use id for better targeting
const overlayContainer = document.querySelector('.overlay');
const slideContainer = document.getElementById('combined-container');
const readOnlyTrigger = document.querySelector('.trigger-readable');
const registrationForm = document.getElementById('registrationForm');

// Initially disable the Continue button
continueButton.disabled = true;

// Function to check if all fields are filled
function areFieldsFilled() {
    return Array.from(formFields).every(field => {
        if (field.required) {
            return field.value.trim() !== ''; // Check if required field is filled
        }
        return true;
    });
}

// Function to enable or disable the Continue button
function validateForm() {
    const allFieldsFilled = areFieldsFilled();
    const policiesChecked = privacyCheckbox.checked && termsCheckbox.checked;

    continueButton.disabled = !(allFieldsFilled && policiesChecked); // Enable only if all conditions are met
}

// Add event listeners to form fields for validation
formFields.forEach(field => {
    field.addEventListener('input', validateForm); // Trigger validation on input
});

// Add event listeners for Privacy and Terms checkboxes
privacyCheckbox.addEventListener('change', validateForm);
termsCheckbox.addEventListener('change', validateForm);

// Event listener for "Continue" button
continueButton.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent default behavior to avoid unintended actions
    if (!continueButton.disabled) {
        // Log debug information for verification
        console.log("Form submitted!");
        console.log("Serialized form data: ", new FormData(registrationForm));
        
        // Submit the form
        registrationForm.submit();
    }
});

// Function to close the overlay
function closeOverlay() {
    overlayContainer.classList.remove('active');
    slideContainer.classList.remove('active');
}

// Handle clicking the "Privacy Policy | Terms & Conditions" link
readOnlyTrigger.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent the default action of the link
    overlayContainer.classList.add('active');
    slideContainer.classList.add('active');
});

// Optional: Close the overlay when clicking outside or the close button
overlayContainer.addEventListener('click', closeOverlay);
document.querySelectorAll('.slide-container .close-btn').forEach(button => {
    button.addEventListener('click', closeOverlay);
});
