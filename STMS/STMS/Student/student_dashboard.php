<?php
session_start();
include('../includes/config.php');

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to student login page if not logged in
    header("Location: StudentLoginPage.php");
    exit;
}

// Fetch student's ID
$student_id = $_SESSION['student_id'];

// Fetch registered courses for the student
$sql_registered_courses = "SELECT c.course_id, c.course_name , c.course_description
                            FROM courses c 
                            INNER JOIN registered_courses rc ON c.course_id = rc.course_id 
                            WHERE rc.student_id = $student_id";
$result_registered_courses = $conn->query($sql_registered_courses);
$registered_courses = array();
if ($result_registered_courses->num_rows > 0) {
    while ($row_registered_course = $result_registered_courses->fetch_assoc()) {
        $registered_courses[] = $row_registered_course;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    
</head>

<style> /* Base styles */
:root {
  --white-color: #fff;
  --paraText-color: #777;
  --heading-color: #333;
  --primary-color: rgb(31, 153, 167);
  --secondary-color: rgb(94, 7, 40);
}

 
 


.course-card {
    display: flex;
    flex-direction: column; /* Change direction for better alignment */
    align-items: center; /* Align items to center */
    padding: 15px;
    margin-bottom: 20px;
     background-color: var(--white-color);
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.content {
            display: flex;
            justify-content: space-between; /* Align the main content and the aside */
            padding-top: 20px; /* Adjust this value as needed */

        }
.course-section {
            flex-grow: 2; /* Allows the course section to take more space */
            margin-right: 20px; /* Provides some spacing between the courses and the quote/calendar */
 
            
        }

 

.card-header {
    background-color: var(--primary-color);
    color: var(--white-color);
    text-align: center;
}

.star-course {
    display: flex;
    align-items: center;
    justify-content: center; /* Center the star checkbox */
    width: 100%; /* Full width for better alignment */
}

.star-checkbox + .star-label {
    margin-left: 5px;
}

.sidebar {
flex-grow: 1; /* Allows the sidebar to take necessary space */
max-width: 300px; /* Adjust based on your preference */
        }
  

 .card-title {
    font-size: 20px; /* Increase the font size */
    margin-bottom: 15px; /* Adjust spacing as needed */
}

/* Adjusting the text font size within the cards */
.card-text {
    font-size: 20px; /* Increase the font size */
 }
/* Ensuring the cards container (row) is a flex container */
.row {
    display: flex;
    flex-wrap: wrap;
}

/* Equal width and flexible cards */
.card {
    flex: 1 0 calc(33.333% - 20px); /* Adjust based on the number of cards per row */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin: 10px; /* Adjust spacing around cards */
}

/* Optional: Equal height for card bodies */
.card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}




        .calendar-container {
    margin-top: 30px; /* Adjust this value as needed to create more or less space */
    padding: 20px;
    border: 2px solid var(--primary-color);
    background-color: var(--white-color);
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    width: 350px;
    position: sticky;
    top: 420px; /* You might need to adjust this value as well */
    margin-left: auto; /* Keep the calendar to the right */
}

 
.calendar-days span {
    display: inline-block;
    width: 40px;
    text-align: center;
 }

.calendar {
    background: var(--white-color);
    padding: 15px;
 }

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
}

.date, .calendar-days span {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    background-color: var(--white-color);
    border-radius: 5px;
}

.content .d-flex {
    display: flex;
}

.content .flex-grow-1 {
    flex: 1; 
}

 
.quotes-container {
    padding-bottom: 50px; /* Adjust this value as needed */
    margin-left: 50px;
    width: 600px;
    height: 400px;

    margin-top: 50px;


 
 
 }

.calendar-container {
    margin-top: 50px; /* Add some space between the quote and the calendar */
 margin-left: 0px;
 height: 400px;
 width: 600px;
}


@media (max-width: 768px) {
    /* Stack the layout on smaller screens */
    .content .d-flex {
        flex-direction: column;
    }
}


@media (max-width: 992px) {
    .quotes-container, .calendar-container {
        width: 100%;
        position: static; /* Remove sticky and allow natural flow */
    }
    .calendar-container {
        order: 3; /* Ensure calendar is below other content */
        margin-top: 20px;
    }
}

@media (max-width: 768px) {
    .quotes-container, .calendar-container {
        width: 100%;

    }
}

@media (max-width: 576px) {
    .quotes-container, .calendar-container {
        padding: 10px;
    }
}



.card {
    transition: transform 0.3s ease-in-out; /* Smooth transition for the transform property */
}

.card:hover {
    transform: translateY(-10px); /* Moves the card up by 10 pixels on hover */
}

.card-text {
    font-size: 20px; /* Increase the font size as needed */
}


.quotes-container p {
    font-size: 24px; /* Increase the font size */
    margin-top: 150px;
  }


 
 




.quotes-container {
    /* Keep existing styles */
    position: relative; /* Ensure relative positioning for absolute positioning of pseudo-element */
    overflow: hidden; /* Hide overflow to prevent image from overflowing */
    border-radius: 20px;
}


.quotes-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 200%; /* Double the height to cover enough space for vertical movement */
    background-image: url('../images/image7.png'); /* Correct the path if necessary */
    background-size: cover;
    background-position: center;
    animation: slideBackground 20s linear infinite; /* Adjust animation duration and timing function as needed */
}

@keyframes slideBackground {
    0% {
        transform: translateY(0%);
    }
    100% {
        transform: translateY(-100%); /* Adjust translation percentage based on desired movement */
    }
}

/* Ensure text remains visible over the moving background */
.quotes-container p {
    z-index: 1; /* Ensure text is above the pseudo-element */
    position: relative; /* Ensure relative positioning for z-index to work */
    color: white; /* Ensure text is readable */
    font-size: 24px; /* Adjust font size as needed */
    text-align: center; /* Center the text horizontally */
 }

       

.dashboard-header {
    background-color: var(--secondary-color);
    padding: 20px;
    display: flex;
    justify-content: space-between; /* Space content evenly */
    align-items: center; /* Center content vertically */
}

.menu-icon {
    font-size: 24px; /* Adjust the size of the menu icon */
    cursor: pointer; /* Show pointer cursor on hover */
    align-items: center; /* Center content vertically */
    

}

.dashboard-title {
    color: white; /* Set the color of the title */
    display: flex; /* Use flexbox */
    align-items: center; /* Center content vertically */
    margin-right: 600px; /* Push the title to the right */
}

.content-container {
    margin-left: 0;
    transition: margin-left 0.3s; /* Add transition for smooth shifting */
}

        .shifted {
            margin-left: 250px; /* Adjust left margin when sidebar is open */
        }


        footer {
     padding: 20px; /* Add some padding around the content inside the footer */
    text-align: center; /* Center-align the text */
}

footer p {
    margin: 0; /* Remove default margin */
    font-size: 14px; /* Adjust font size */
    color: #333; /* Text color */
}
.card-body {
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
}


</style>

<body>
 
                








<div class="container-fluid">
    <div class="row">  
    
        <!-- Dashboard Header -->
        <div class="col-md-12 dashboard-header">
            <?php include('StudentNavbar.php'); ?>
            <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i> Dashboard</h1>
        </div>
    </div>
</div>






<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Quote Section -->
            <div class="col-md-6">
                <div class="quotes-container">
                    <p id="quote" style="color: black;">"The way to get started is to quit talking and begin doing." — Walt Disney</p>
                </div>
            </div>


    


       

 


            <div class="col-md-6">
                <div class="calendar-outer-container">
                    <div class="calendar-container">

                 <h3>March 2023</h3>
                 <div class="calendar">

                <div class="calendar-days">
                    <span>S</span>
                    <span>M</span>
                    <span>T</span>
                    <span>W</span>
                    <span>T</span>
                    <span>F</span>
                    <span>S</span>
                    <div class="date">1</div>
                    <div class="date">2</div>
                    <div class="date">3</div>
                    <div class="date">4</div>
                    <div class="date">5</div>
                    <div class="date">6</div>
                    <div class="date">7</div>
                    <div class="date">8</div>
                    <div class="date">9</div>
                    <div class="date">10</div>
                    <div class="date">11</div>
                    <div class="date">12</div>
                    <div class="date">13</div>
                    <div class="date">14</div>
                    <div class="date">15</div>
                    <div class="date">16</div>
                    <div class="date">17</div>
                    <div class="date">18</div>
                    <div class="date">19</div>
                    <div class="date">20</div>
                    <div class="date">21</div>
                    <div class="date">22</div>
                    <div class="date">23</div>
                    <div class="date">24</div>
                    <div class="date">25</div>
                    <div class="date">26</div>
                    <div class="date">27</div>
                    <div class="date">28</div>
                    <div class="date">29</div>
                    <div class="date">30</div>
                    <div class="date">31</div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div class="container-fluid">
    <div class="row">
        <!-- Courses Section -->
        <div class="col-md-12 mx-auto mt-5"> <!-- Centering the courses section and adding margin top -->
            <div class="card">
                <div class="card-header"><h2 class="text-center">Registered Courses</h2></div>
                <div class="card-body">
                    <?php if (!empty($registered_courses)) { ?>
                        <div class="row">
                            <?php foreach ($registered_courses as $course) { ?>
                                <div class="col-12"> <!-- Full width for each course card -->
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title"><strong><?php echo $course['course_name']; ?></strong></h5>
                                            <p class="card-text"><?php echo $course['course_description']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <p class="text-center">No courses registered yet.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>@ All Rights Reserved for Horizon Academy</p>
</footer>

                    </div>






 


                    <script>
    // Function to change the quote on page load
    document.addEventListener('DOMContentLoaded', function () {
        newQuote();
    });

    const quotes = [
        { text: "The best way to predict the future is to invent it.", author: "Alan Kay" },
        { text: "Life is 10% what happens to us and 90% how we react to it.", author: "Charles R. Swindoll" },
        { text: "It does not matter how slowly you go as long as you do not stop.", author: "Confucius" },
        { text: "Success is not final, failure is not fatal: It is the courage to continue that counts.", author: "Winston Churchill" },
        { text: "Believe you can and you're halfway there.", author: "Theodore Roosevelt" },
        { text: "Don't watch the clock; do what it does. Keep going.", author: "Sam Levenson" },
        { text: "You have brains in your head. You have feet in your shoes. You can steer yourself any direction you choose.", author: "Dr. Seuss" },
        { text: "Only I can change my life. No one can do it for me.", author: "Carol Burnett" },
        { text: "Optimism is the faith that leads to achievement. Nothing can be done without hope and confidence.", author: "Helen Keller" },
        { text: "With the new day comes new strength and new thoughts.", author: "Eleanor Roosevelt" }
    ];

    function newQuote() {
        const randomIndex = Math.floor(Math.random() * quotes.length);
        const selectedQuote = quotes[randomIndex];
        document.getElementById('quote').innerHTML = `"${selectedQuote.text}" — ${selectedQuote.author}`;
    }
</script>



 


<script>document.addEventListener("DOMContentLoaded", function() {
    // Reset sidebar width and content margin on page load
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".content");
    sidebar.style.width = "0";
    content.style.marginLeft = "0";
});

function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".content");

    if (sidebar.style.width === "250px") {
        sidebar.style.width = "0";
        content.style.marginLeft = "0";
    } else {
        sidebar.style.width = "250px";
        content.style.marginLeft = "250px";
    }
}

function closeNav() {
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".content");

    sidebar.style.width = "0";
    content.style.marginLeft = "0";
}

// Function to toggle dropdown content
function toggleDropdown(element) {
    var dropdownContent = element.nextElementSibling;
    dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
}

// Close the dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }
};



</script>
    </div>
</body>

</html>

