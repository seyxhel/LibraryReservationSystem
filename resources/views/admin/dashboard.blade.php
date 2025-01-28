<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport" 
          content="width=device-width, 
                   initial-scale=1.0">
    <title>UNIARCHIVE - Admin Home</title>
    <link rel="stylesheet" 
          href="{{ asset('css/ADMIN-Dashboard1.css') }}">
    <link rel="stylesheet" []
          href="{{ asset('css/AA-Responsive-Dashboard.css') }}">
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

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <div class="nav-option option1">
                        <img src="{{ asset('assets/04-dashboard.png') }}"
                            class="nav-img" 
                            alt="dashboard">
                        <h3> Dashboard</h3>
                    </div>

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
 
                    <div class="option5 nav-option" id="book-inventory">
                        <img src="{{ asset('assets/13-inventory.png') }}"
                            class="nav-img"
                            alt="Book Inventory">
                        <h3>Book Inventory</h3>
                    </div>

                    <script>
                        document.getElementById('book-inventory').addEventListener('click', function() {
                            window.location.href = "{{ route('admin.book-inventory') }}";
                        });
                    </script>
            </nav>
        </div>

        <div class="main">

            <div class="searchbar2">
                <input type="text" 
                       name="" 
                       id="" 
                       placeholder="Search">
                <div class="searchbtn">
                  <img src= "{{ asset('assets/02-search.png') }}"
                        class="icn srchicn" 
                        alt="search-button">
                  </div>
            </div>

            <div class="box-container">

                <div class="box box1">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Total Reservation</h2>
                    </div>

                    <img src= "{{ asset('assets/08-bookmark.png') }}"
                        alt="Views">
                </div>

                <div class="box box2">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Canceled Reservations</h2>
                    </div>

                    <img src= "{{ asset('assets/09-cancel.png') }}" 
                         alt="likes">
                </div>

                <div class="box box3">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Number of Books per Category</h2>
                    </div>

                    <img src= "{{ asset('assets/10-books.png') }}"
                        alt="comments">
                </div>

                <div class="box box4">
                    <div class="text">
                        <h2 class="topic-heading">000</h2>
                        <h2 class="topic">Books Available</h2>
                    </div>

                    <img src= "{{ asset('assets/11-available.png') }}" alt="published">
                </div>
            </div>

            <div class="report-container">
                <div class="report-header">
                    <h1 class="title-Header">List of All Research Books</h1>
                  <!--<button class="view">View All</button>-->
                </div>
                 <!--
                <div class="report-body">
                    <div class="report-topic-heading">
                        <h3 class="t-op">Article</h3>
                        <h3 class="t-op">Views</h3>
                        <h3 class="t-op">Comments</h3>
                        <h3 class="t-op">Status</h3>
                    </div>

                    <div class="items">
                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 73</h3>
                            <h3 class="t-op-nextlvl">2.9k</h3>
                            <h3 class="t-op-nextlvl">210</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 72</h3>
                            <h3 class="t-op-nextlvl">1.5k</h3>
                            <h3 class="t-op-nextlvl">360</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 71</h3>
                            <h3 class="t-op-nextlvl">1.1k</h3>
                            <h3 class="t-op-nextlvl">150</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 70</h3>
                            <h3 class="t-op-nextlvl">1.2k</h3>
                            <h3 class="t-op-nextlvl">420</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 69</h3>
                            <h3 class="t-op-nextlvl">2.6k</h3>
                            <h3 class="t-op-nextlvl">190</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 68</h3>
                            <h3 class="t-op-nextlvl">1.9k</h3>
                            <h3 class="t-op-nextlvl">390</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 67</h3>
                            <h3 class="t-op-nextlvl">1.2k</h3>
                            <h3 class="t-op-nextlvl">580</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 66</h3>
                            <h3 class="t-op-nextlvl">3.6k</h3>
                            <h3 class="t-op-nextlvl">160</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                        <div class="item1">
                            <h3 class="t-op-nextlvl">Article 65</h3>
                            <h3 class="t-op-nextlvl">1.3k</h3>
                            <h3 class="t-op-nextlvl">220</h3>
                            <h3 class="t-op-nextlvl label-tag">Published</h3>
                        </div>

                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>