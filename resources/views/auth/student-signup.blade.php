<!DOCTYPE html>
<html>
<head>
    <title>Student Sign Up</title>
    <!-- Link to External CSS -->
    <link rel="stylesheet" href="{{ asset('css/Student.SignUpPage.css') }}">
</head>
<body>

<div class="form-box">
        <div class="login-header">
            <img src="{{ asset('assets/UNIARCHIVE.TRANS.png') }}" class="logo">
        </div>

        <!------------------- registration form -------------------------->
        <div class="register-container" id="register">
            <div class="top">
                <header>Create Account</header>
            </div>
    
            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

    <form method="POST" action="{{ route('student.signup.post') }}" id="registrationForm" class="form-container">
        @csrf

            <div class="input-box">
                <input type="text" class="input-field" name="school_id" placeholder="School ID: (ex: 2025-00001-CM-0)" required>
                <i class="bx bx-id-card"></i>
                @error('school_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        <div class="four-forms">

            <div class="input-box">
                <input type="text" class="input-field" name="last_name" placeholder="Last Name:" required>
                <i class="bx bx-user"></i>
            </div>

            <div class="input-box">
                <input type="text" class="input-field" name="first_name" placeholder="First Name:" required>
                <i class="bx bx-user"></i>
            </div>

            <div class="input-box">
                <input type="text" class="input-field" name="middle_name" placeholder="Middle Name:">
                <i class="bx bx-user"></i>
            </div>

        <div class="input-box">
            <select name="suffix" class="input-field" >
                <option value="" selected>Suffix:</option>
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
            </select>
            <i class="bx bx-chevron-down"></i>
        </div>
        </div>
        <div class="two-forms">
            <div class="input-box">
                <select name="gender" class="input-field" required>
                <option value="" disabled selected>Gender:</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                    <option value="prefer-not-to-say">Prefer not to say</option>
                </select>
                <i class="bx bx-chevron-down"></i>
            </div>

            <div class="input-box">
                <select name="program_id" class="input-field" required>
                    <option value="" disabled selected>Select College Program:</option>
                    <option value="1">Information Technology</option>
                    <option value="2">Home Economics</option>
                    <option value="3">Information Communication and Technology</option>
                    <option value="4">Human Resource Management</option>
                    <option value="5">Marketing Management</option>
                    <option value="6">Entrepreneurship</option>
                    <option value="7">Fiscal Administration</option>
                    <option value="8">Office Management Technology</option>
                </select>
                <i class="bx bx-chevron-down"></i>
            </div>
        </div>

        <div class="input-box">
            <input type="text" class="input-field" maxlength=11 name="contact_number" placeholder="Contact Number:" required>
            <i class="bx bx-contact"></i>
        </div>

        <div class="input-box">
            <input type="email" class="input-field" name="email" placeholder="Email:" required>
            <i class="bx bx-envelope"></i>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <p style="font-size: 12px; margin-left:20px; font-family: Arial; color: white;">
            Password must be at least 8 characters long, contain at least one number, one uppercase letter, and one special character.
        </p>

        <div class="two-forms">
            <div class="input-box">
                <input type="password" class="input-field" name="password" placeholder="Password:" required>
                <i class="bx bx-lock"></i>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="password_confirmation" placeholder="Confirm Password:" required>
                <i class="bx bx-lock"></i>
                @error('password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="two-col">
            <div class="two">
                <label>
                    <input type="checkbox" class="policy-summary-checkbox" id="two-col-checkbox" disabled> 
                </label>
            </div>
            <div class="one">
                <a href="#" class="trigger-readable" onclick="togglePolicyContainer()">Privacy Policy | Terms & Conditions</a>
            </div>
        </div>
        <div class="overlay" id="overlay" style="display: none;" onclick="closePolicyContainer()"></div> <!-- Background overlay -->
        <div class="overlay" id="overlay" style="display: none;"></div> <!-- Background overlay -->

        <!-- Combined Container for Privacy Policy and Terms & Conditions -->
        <div id="combined-container" class="slide-container" style="display: none;">
            <div class="scrollable-content">

                <div class="policy-section">
                    <h3>Privacy Policy</h3>
                    <p>Your privacy is important to us. This Privacy Policy outlines how UNIARCHIVE collects, uses, and protects your information when you use our library book reservation and inventory management system.</p>
                    <ol>
                        <li>
                            <strong>Information We Collect:</strong> 
                            <ul>
                                <li>Personal Information: Name, email address, or other identifiers provided during account registration.</li>
                                <li>Usage Data: Information about how you interact with the System, such as login times, book reservations, and search history.</li>
                                <li>Device Information: IP address, browser type, and operating system details for security and analytics purposes.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>How We Use Your Information:</strong>
                            <ul>
                                <li>To provide and manage the System's services.</li>
                                <li>To process book reservations and maintain accurate inventory records.</li>
                                <li>To communicate updates or respond to your inquiries.</li>
                                <li>To enhance user experience and improve the System.</li>
                                <li>To comply with legal and regulatory requirements.</li>
                            </ul>
                        </li>
                        <li>
                                        <strong>Data Sharing and Disclosure:</strong>
                                        <p>We do not sell, trade, or rent your personal information. However, we may share data:</p>
                                        <ul>
                                            <li>With service providers assisting us in operating the System.</li>
                                            <li>When required by law, regulation, or legal process.</li>
                                            <li>To protect the rights, safety, or property of our users or the public.</li>
                                        </ul>
                                    </li>
                                    <li>
                                        <strong>Data Security:</strong>
                                        <p>We implement industry-standard security measures to protect your information. However, no system can be completely secure. We encourage users to safeguard their account credentials.</p>
                                    </li>
                                    <li>
                                        <strong>Your Rights:</strong>
                                        <ul>
                                            <li>Access, update, or delete your personal information.</li>
                                            <li>Opt out of non-essential communications.</li>
                                            <li>Withdraw consent for data processing where applicable.</li>
                                        </ul>
                                    </li>
                                    <li>
                                        <strong>Changes to This Policy:</strong>
                                        <p>We may update this Privacy Policy periodically. Changes will be posted with an updated "Effective Date."</p>
                                    </li>
                                    <li>
                                        <strong>Contact Us:</strong>
                                        <p>If you have questions about this Privacy Policy, please contact us at ashleyyyerillo@gmai.com, elisataan01@gmail.com.</p>
                                    </li>
                    </ol>
                </div>

                <div class="policy-section">
                    <h3>Terms and Conditions</h3>
                    <p>These Terms and Conditions govern your use of the library book reservation and inventory management system provided by UNIARCHIVE. By accessing or using the System, you agree to these Terms.</p>
                    <ol>
                        <li><strong>Account Responsibilities:</strong>
                            <ul>
                                <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
                                <li>Notify us immediately of unauthorized access to your account.</li>
                            </ul>
                        </li>
                        <li><strong>Usage Guidelines:</strong>
                            <ul>
                                <li>Use it solely for lawful purposes.</li>
                                <li>Refrain from interfering with its operation or security.</li>
                                <li>Not engage in any fraudulent activities, such as reserving books without intent to borrow.</li>
                            </ul>
                        </li>
                        <li><strong>Reservations and Borrowing:</strong>
                                        <ul>
                                            <li>Book reservations are subject to availability.</li>
                                            <li>Reserved books must be picked up within [specified time frame, e.g., 3 days] or the reservation will be canceled.</li>
                                            <li>Overdue books may incur penalties as outlined by the library’s policies.</li>
                                        </ul>
                                    </li>
                                    <li><strong>Intellectual Property:</strong>
                                        <p>All content and software in the System are owned by us or licensed to us. Unauthorized use, reproduction, or distribution is prohibited.</p>
                                    </li>
                                    <li><strong>Limitation of Liability:</strong>
                                        <p>We are not responsible for:</p>
                                        <ul>
                                            <li>System downtime, data loss, or technical issues.</li>
                                            <li>Unauthorized access to your account due to your negligence.</li>
                                            <li>Damages arising from your use of the System.</li>
                                        </ul>
                                    </li>
                                    <li><strong>Termination:</strong>
                                        <p>We reserve the right to suspend or terminate your access to the System for violating these Terms or engaging in unlawful activities.</p>
                                    </li>
                                    <li><strong>Changes to These Terms:</strong>
                                        <p>We may update these Terms periodically. Continued use of the System after updates constitutes acceptance of the revised Terms.</p>
                                    </li>
                                    <li><strong>Contact Us:</strong>
                                        <p>For questions about these Terms, please contact us at ashleyyyerillo@gmai.com, elisataan01@gmail.com.</p>
                                    </li>
                    </ol>
                </div>
            </div>

    <!-- Accept Checkboxes -->
    <div class="checkbox-section" style="text-align: center;">
        <label>
            <input type="checkbox" class="policy-checkbox" id="privacy-checkbox"> I agree to the Privacy Policy
        </label>
        <label>
            <input type="checkbox" class="policy-checkbox" id="terms-checkbox"> I agree to the Terms and Conditions
        </label>
    </div>
</div>

<script>
    // Toggle visibility of the Privacy Policy and Terms container
    function togglePolicyContainer() {
        const overlay = document.getElementById('overlay');
        const container = document.getElementById('combined-container');
        
        container.style.display = 'block';
        overlay.style.display = 'block';
    }

    // Close the container when clicking outside of it
    function closePolicyContainer() {
        const overlay = document.getElementById('overlay');
        const container = document.getElementById('combined-container');

        container.style.display = 'none';
        overlay.style.display = 'none';
    }
</script>



        <div class="input-box">
            <button type="submit" class="submit-btn" id="continueButton" disabled>Continue</button>
        </div>
    </form>

    <!-- Overlay and Slide Container -->
    <div class="overlay" id="overlay"></div>
    <div class="slide-container" id="slideContainer">
        <h3>Privacy Policy</h3>
        <p>Your privacy is important to us. This Privacy Policy outlines how UNIARCHIVE collects, uses, and protects your information when you use our library book reservation and inventory management system.</p>
                        <ol>
                            <li>
                                <strong>Information We Collect:</strong> 
                                <ul>
                                    <li>Personal Information: Name, email address, or other identifiers provided during account registration.</li>
                                    <li>Usage Data: Information about how you interact with the System, such as login times, book reservations, and search history.</li>
                                    <li>Device Information: IP address, browser type, and operating system details for security and analytics purposes.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>How We Use Your Information:</strong>
                                <ul>
                                    <li>To provide and manage the System's services.</li>
                                    <li>To process book reservations and maintain accurate inventory records.</li>
                                    <li>To communicate updates or respond to your inquiries.</li>
                                    <li>To enhance user experience and improve the System.</li>
                                    <li>To comply with legal and regulatory requirements.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Data Sharing and Disclosure:</strong>
                                <p>We do not sell, trade, or rent your personal information. However, we may share data:</p>
                                <ul>
                                    <li>With service providers assisting us in operating the System.</li>
                                    <li>When required by law, regulation, or legal process.</li>
                                    <li>To protect the rights, safety, or property of our users or the public.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Data Security:</strong>
                                <p>We implement industry-standard security measures to protect your information. However, no system can be completely secure. We encourage users to safeguard their account credentials.</p>
                            </li>
                            <li>
                                <strong>Your Rights:</strong>
                                <ul>
                                    <li>Access, update, or delete your personal information.</li>
                                    <li>Opt out of non-essential communications.</li>
                                    <li>Withdraw consent for data processing where applicable.</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Changes to This Policy:</strong>
                                <p>We may update this Privacy Policy periodically. Changes will be posted with an updated "Effective Date."</p>
                            </li>
                            <li>
                                <strong>Contact Us:</strong>
                                <p>If you have questions about this Privacy Policy, please contact us at ashleyyyerillo@gmai.com, elisataan01@gmail.com.</p>
                            </li>
                        </ol>

        <h3>Terms & Conditions</h3>
        <p>By using this platform, you agree to the following terms...</p>
        <button onclick="closeOverlay()">Close</button>
    </div>

    <script>
        const privacyCheckbox = document.getElementById('privacy-checkbox');
        const termsCheckbox = document.getElementById('terms-checkbox');
        const continueButton = document.getElementById('continueButton');
        const overlay = document.getElementById('overlay');
        const slideContainer = document.getElementById('slideContainer');

        function toggleContinueButton() {
            continueButton.disabled = !(privacyCheckbox.checked && termsCheckbox.checked);
        }

        privacyCheckbox.addEventListener('change', () => {
            continueButton.disabled = !privacyCheckbox.checked;
        });
        termsCheckbox.addEventListener('change', toggleContinueButton);

        function openOverlay() {
            overlay.style.display = 'block';
            slideContainer.style.display = 'block';
        }

        function closeOverlay() {
            overlay.style.display = 'none';
            slideContainer.style.display = 'none';
        }
    </script>
</body>
</html>
