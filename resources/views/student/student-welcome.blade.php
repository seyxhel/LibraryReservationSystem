<!DOCTYPE html>
<html>
<head>
    <title>UNIARCHIVE</title>
    <link rel="stylesheet" href="{{ asset('css/Student.WelcomePage.css') }}">
</head>
<body>

    <div class="banner"> 

        <div class="navibar">
            <img src="{{ asset('assets/UNIARCHIVE.TRANS.png') }}" class="logo">
            <ul>
                <li><a href="#" data-target="home-section">Home</a></li>
                <li><a href="#" data-target="about-section">About</a></li>
            </ul>
        </div>
        
        <div class="sections">
            <!-- Home Section -->
            <div class="home-section content">
                <h2>Welcome to</h2> <h1>UNIARCHIVE!</h1>
                <p>
                    You can explore our collection of research books,
                    reserve them conveniently online,
                </p>
                <p>and stay updated with availability. 
                    Enhance your research experience with just a few clicks!
                </p>
        
                <div>
                    <p></p>
                    <button type="button" onclick="window.location.href='{{ route('student.signup') }}';"><span></span>SIGN UP</button>
                    <button type="button" onclick="window.location.href='{{ route('student.login') }}';"><span></span>SIGN IN</button>
                </div>
            </div>
        
            <!-- About Section -->
            <div class="about-section">
                <h2>About Us</h2>
                <p>Welcome to UNIARCHIVE, your all-in-one solution for efficient Research Library management. We are dedicated to simplifying the way libraries operate, making book reservations, inventory tracking, and user interactions seamless and hassle-free.</p>
                <p>Our mission is to empower libraries by providing a user-friendly platform that enhances accessibility for both librarians and patrons. Whether you're managing a bustling community library, a school library, or a private collection, [System Name] is designed to adapt to your needs, ensuring smooth operations and a delightful user experience.</p>
                <h3>What We Offer:</h3>
                <ul>
                    <li><strong>Book Reservation:</strong> Allow users to browse, reserve, and renew books effortlessly from anywhere, ensuring convenience and accessibility.</li>
                    <li><strong>Inventory Management:</strong> Track your library's collection in real time, manage acquisitions, and streamline cataloging for better organization.</li>
                    <li><strong>User-Friendly Interface:</strong> With an intuitive design, our system is easy to navigate for both student and administrators.</li>
                    <li><strong>Advanced Search Features:</strong> Enable users to find the books they need quickly through keyword searches, filters, and recommendations.</li>
                    <li><strong>Data Insights:</strong> Access reports and analytics to understand borrowing trends, popular titles, and inventory turnover.</li>
                </ul>
                <p>At UNIARCHIVE, we believe in the transformative power of knowledge and the critical role libraries play in communities. Our system is built with the latest technology to support your library's mission of fostering learning, exploration, and connection.</p>
            </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const navLinks = document.querySelectorAll(".navibar ul li a");
            const sections = document.querySelectorAll(".sections > div");
    
            // Add click event listener to each nav link
            navLinks.forEach(link => {
                link.addEventListener("click", (event) => {
                    event.preventDefault(); // Prevent default behavior
    
                    // Get the target section's class name
                    const targetClass = link.dataset.target;
    
                    // Hide all sections
                    sections.forEach(section => section.style.display = "none");
    
                    // Show the target section
                    const targetSection = document.querySelector(`.${targetClass}`);
                    if (targetSection) {
                        targetSection.style.display = "block";
                    }
                });
            });
        });
    </script>

</body>
</html>