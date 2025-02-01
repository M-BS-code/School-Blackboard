<?php 
session_start(); 
include('../includes/config.php'); 

// Check if teacher is logged in 
if (!isset($_SESSION['teacher_id'])) { 
    // Redirect to teacher login page if not logged in 
    header("Location: teacherLoginPage.php"); 
    exit; 
} 

// Get teacher's courses 
$teacher_id = $_SESSION['teacher_id']; 
$sql_courses = "SELECT COUNT(*) as num_courses FROM Courses WHERE instructor_id = $teacher_id"; 
$result_courses = $conn->query($sql_courses); 
$num_courses = 0; 
if ($result_courses->num_rows > 0) { 
    $row_course = $result_courses->fetch_assoc(); 
    $num_courses = $row_course['num_courses']; 
} 
?> 


<!DOCTYPE html>
<html>
<head>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../css/styles.css">
    <style>
 



 :root {
    --white-color: #fff;
    --paraText-color: #777;
    --heading-color: #333;
    --primary-color: rgb(31, 153, 167);
    --secondary-color: rgb(94, 7, 40);
}






 .content-shift {
    transform: translateX(250px); /* Shift content to the right */
    transition: transform 0.3s ease; /* Smooth transition */
}

/* For smaller screens, you might not want to shift the content or use a smaller value */
@media (max-width: 768px) {
    .content-shift {
        transform: translateX(150px); /* Adjust according to your design */
    }
}


.sidebar {
    z-index: 2; /* Or any value higher than the content's z-index */
    /* Existing styles */
}


        body {
            background-color: var(--white-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--paraText-color);
            transition: background-color 0.5s ease;
        }

        .card {
            background-color: var(--white-color);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .card-header {
            background-color: var(--secondary-color);
            color: var(--white-color);
            border-bottom: none;
        }

     


 
 
        .dashboard-image-frame {
    width: 400px; /* Adjusted width of the container */
    height: 450px; /* Increased height of the container */
    border-radius: 10px; /* Add border radius */
    overflow: hidden; /* Ensure the border doesn't overflow */
    position: relative; /* Set container position to relative */
}


.dashboard-image-frame img {
    width: 100%; /* Make the image fill the container width */
    height: 100%; /* Make the image fill the container height */
    object-fit: cover; /* Maintain aspect ratio */
    transition: transform 0.3s ease-in-out;
    position: absolute; /* Set image position to absolute */
    margin-top: 80px; /* Move the top of the image down by 50% of the container height */
  }




.dashboard-image-frame:hover img {
    transform: scale(1.03); /* Subtle zoom effect on hover */
}



        a, .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        a:hover, .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        .logo:hover {
            transform: scale(1.1);
        }






 
body, html {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
}

 


.hamburger {
  cursor: pointer;
  padding: 15px;
  display: flex;
  flex-direction: column;
  gap: 5px; 
 }


.hamburger .line {
  width: 30px; 
  height: 3px; 
  background-color: white;
  transition: all 0.3s ease;
}


.sidebar {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 2;
  top: 0;
  left: 0;
  background-color: var(--secondary-color);
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
  border-top-right-radius: 25px; 
  border-bottom-right-radius: 25px; 
}


.sidebar ul {
  list-style-type: none;
  padding: 0;
}


.sidebar-title {
  color: var(--white-color); 
  font-size: 32px; 
  padding: 20px 15px; 
  text-align: center; 
  border-bottom: 1px solid var(--white-color); 
  margin-bottom: 20px; 
}

.sidebar ul li a, .dropbtn {
  padding: 8px 15px; 
  text-decoration: none;
  font-size: 18px; 
  color: var(--white-color);
  display: block;
  transition: color 0.3s, background-color 0.3s;
  background-color: transparent; /* Set background color to transparent */
}

.sidebar ul li a:hover, .dropbtn:hover {
  color: var(--primary-color);
  background-color: var(--paraText-color); 
}
.dropdown {
  position: relative;
  display: block;
}



.dropdown {
  position: relative;
  display: block;
}

.dropbtn {
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
  background-color: inherit; 
  width: 100%; 
  text-align: left; 
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: var(--secondary-color);
  min-width: 100%; 
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}


.dropdown-content a {
  color: var(--secondary-color); 
  padding: 12px 16px; 
  text-decoration: none;
  display: block;
  text-align: left; 
  font-size: 10px; 
  margin-left: 25px; 
}

.dropdown-content a:hover {
  background-color: var(--paraText-color); 
}


.dropdown:hover .dropdown-content {
  display: block;
}

.sidebar ul li:hover .dropdown-content {
  display: block;
}



.closebtn {
  position: absolute;
  top: 0;
  right: 10px;
  font-size: 36px;
  margin-left: 50px;
  color: white; 
  background-color: transparent; /* Set background color to transparent */
}

.closebtn:hover {
  color: #ccc; 
}



.dropdown-content .logout-separator {
  border-top: 1px solid var(--paraText-color); 
  margin: 10px 0;
}

.dropdown-content .logout {
  padding-top: 12px; 
}

.dropdown:hover .dropdown-content, .sidebar ul li:hover .dropdown-content {
  display: block;
  position: static; 
}





.col-md-7 {
    flex: 0 0 70%;
    max-width: 70%;
}

.col-md-5 {
    flex: 0 0 30%;
    max-width: 30%;
}
 

.logos-row {
    display: flex;
    justify-content: center;
    align-items: center;
}

.logo-item {
    flex-basis: 20%; /* Adjust the width as needed */
    flex-grow: 1;
    margin: 10px;
}

.logo {
    font-size: 48px; /* Larger icon size */
    display: block; /* Makes it a block-level element for vertical alignment */
    margin-bottom: 0.5em; /* Space between icon and text */
}

h4 {
    margin-bottom: 0.5em; /* Space between heading and paragraph */
}

p {
    font-size: 1rem; /* Adjust the font size as needed */
}


.header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* This will space out the hamburger menu and the title */
    padding: 10px 20px;
    background-color: var(--secondary-color);
    color: var(--white-color);
}

.header h1 {
    flex-grow: 1;
    text-align: center;
    margin: 0; /* Removes default margin from the h1 tag for better alignment */
}

.hamburger {
    cursor: pointer;
 }
 
 .footer {
    padding: 20px 0; /* Adjust padding to increase/decrease space */
     color: #777; /* Footer text color */
    text-align: center; /* Centers the footer text */
     position: relative; /* Ensures footer is placed at the bottom */
    bottom: 0; /* Aligns footer at the bottom */
    width: 100%; /* Footer width */
    margin-top: 20px;
}

.container {
    width: 80%; /* Container width */
    margin: auto; /* Centers the container */
}


@media (max-width: 768px) {
            .dashboard-image-frame {
                display: none; /* Hide the image container on smaller screens */
            }

            .col-md-6 {
                width: 100%; /* Make the course section take full width on smaller screens */
            }
        }

        @media (max-width: 768px) {
    .chart-container {
        width: 90%; /* Adjust for smaller screens */
        margin: auto;
    }
}
.main-content {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 20px;
}

.card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0px 4px 6px #0002;
    padding: 20px;
    margin-bottom: 20px;
}

/* Add responsive designs as needed with media queries */
@media (max-width: 768px) {
    .main-content {
        flex-direction: column;
    }
}

.description h2 {
    margin-bottom: 20px; /* Adds space below the heading */
    color: var(--heading-color); /* Uses the color defined in your :root */
}
.description {
    margin-top: 90px; /* Adjust the value as needed */
}


.description p {
    line-height: 1.6; /* Improves readability */
    color: var(--paraText-color); /* Uses the color defined in your :root */
}


.chart-container {
    width: 900px; /* Adjust width as needed */
    max-width: 1000px; /* Set a maximum width if necessary */
    padding: 20px; /* Adjust or remove padding as needed */
    border-radius: 10px; /* Add border radius */
    margin-right: -50px; /* Move the chart to the left */
    margin-left: auto; /* Center the chart horizontally */
    margin-right: auto; /* Center the chart horizontally */
    display: flex; /* Use flexbox */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
}




/* Ensures the chart is responsive and fits well in its container */
#classScheduleChart {
    height: 400px !important; /* Example fixed height */
}


@keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .animated {
            animation: slideIn 0.5s ease forwards;
        }







        @keyframes enlarge {
            0% {
                font-size: 3rem;
            }
            100% {
                font-size: 10rem;
            }
        }

        @keyframes shrink {
            0% {
                font-size: 10rem;
            }
            100% {
                font-size: 3rem;
            }
        }

        #welcome-msg {
            animation-name: enlarge;
            animation-duration: 2s;
            animation-fill-mode: forwards; /* Keeps the element at the final keyframe state */
        }

        #welcome-msg.shrink {
            animation-name: shrink;
            animation-duration: 2s;
            animation-fill-mode: forwards; /* Keeps the element at the final keyframe state */
        }


        .content-container {
    width: 100%; /* Adjust the width as desired */
    margin: auto; /* Center the content container horizontally */
}


    </style>
</head>
<body>
<div class="header" style="background-color: var(--secondary-color); color: var(--white-color);">
    <div class="hamburger" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <h1 style="flex-grow: 1; text-align: center;">Dashboard</h1>
</div>
    <div class="sidebar" id="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <div class="sidebar-title">Teacher</div>
        <ul>
            <li><a href="teacher_dashboard.php">Dashboard</a></li>
            <li><a href="teacher_courses.php">Course</a></li>
            <li class="menu-item dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown(this)">Upload Grades</a>
                <div class="dropdown-content">
                    <a href="teacher_upload_assignments_grades.php">Assignments</a>
                    <a href="teacher_upload_quizes_marks.php">Quizzes</a>
                    <a href="teacher_upload_final_exam_marks.php">Finals</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li> 
        </ul>
    </div>   


    
    <body>
    <div class="container-fluid my-5 content-container">


    <div class="container my-5 content-container">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h1 id="welcome-msg">Welcome Back, <?php echo $_SESSION['teacher_username']; ?>!</h1>
            </div>
        </div>
    </div>


 
    <script>
        // After 3 seconds, add class to shrink the text
        setTimeout(() => {
            document.getElementById('welcome-msg').classList.add('shrink');
        }, 3000);
    </script>












    
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white text-center">
                    <h3>Your Courses</h3>
                </div>
                <div class="card-body">
                    <p id="num-courses" class="text-center" style="font-size: 24px;">Total Courses: <?php echo $num_courses; ?></p>
                </div>
            </div>
        </div>
    </div>
 

<script>
    // JavaScript for adding animation and interactivity
    document.addEventListener("DOMContentLoaded", function() {
        // Add animation to the number of courses
        var numCourses = document.getElementById('num-courses');
        numCourses.classList.add('animated');
        
        // Example of adding interactivity (click event)
        numCourses.addEventListener('click', function() {
            alert('You clicked on the number of courses!');
        });
    });
</script>


 

    <div class="row justify-content-center mt-5">
    <div class="col text-center">
        <h2 class="display-4">Empowering Innovation</h2>
        <p class="lead">Dive into the World of Artificial Intelligence and Computer Science</p>
    </div>
</div>
<!-- Icons row will follow here -->


<div class="row justify-content-center mt-5">
    <div class="col text-center">
        <!-- Logos Row -->
        <div class="logos-row">
            <!-- AI Fundamentals -->
            <div class="logo-item mx-2">
                <i class="fas fa-robot logo"></i>
            <h4>AI Fundamentals</h4>
            <p>Start your journey into the world of AI with core concepts and methodologies.</p>
        </div>
        
        <!-- Machine Learning -->
        <div class="logo-item mx-2 d-inline-block text-center">
        <i class="fas fa-brain logo"></i>
            <h4>Machine Learning</h4>
            <p>Discover the power of data with machine learning algorithms and predictive models.</p>
        </div>

        <!-- Neural Networks -->
        <div class="logo-item mx-2 d-inline-block text-center">
        <i class="fas fa-network-wired logo"></i>
            <h4>Neural Networks</h4>
            <p>Unlock complex pattern recognition with neural network architectures.</p>
        </div>

        <!-- Big Data -->
        <div class="logo-item mx-2 d-inline-block text-center">
        <i class="fas fa-database logo"></i>
            <h4>Big Data & AI</h4>
            <p>Big data analytics and its synergy with AI for informed decision-making.</p>
        </div>

        <!-- AI Programming -->
        <div class="logo-item mx-2 d-inline-block text-center">
        <i class="fas fa-code logo"></i>
            <h4>AI Programming</h4>
            <p>Code intelligent systems using the latest AI programming techniques.</p>
        </div>
    </div>
</div>





<div class="row justify-content-center"> <!-- Center the content horizontally -->
    <div class="col-lg-12"> <!-- Adjust column width to cover the entire width -->
        <div class="mt-5" style="background-color: var(--white-color); width: 110vw; padding: 30px;"> <!-- Set background color for the entire width of the page -->
            <!-- Description Column: Takes up full width on medium and large screens -->
            <div class="description text-center" style="border-radius: 10px; padding: 20px; background-color: var(--secondary-color);"> <!-- Center the text -->
                <h2 style="margin-bottom: 20px; color: #f0f0f0;">Class Schedule Overview</h2>
                <p style="line-height: 1.6; color: #f0f0f0;">This graph represents the distribution of your teaching hours across the week.</p>
                <p style="line-height: 1.6; color: #f0f0f0;">It helps in visualizing the time allocated for each course, enabling better time management and planning.</p>

            </div>
        </div>
    </div>
</div>




        <div class="container mt-5">
    <div class="row justify-content-center"> <!-- Center the row horizontally -->
        <!-- Graph Column: Takes up 10 columns on large, medium, and small screens, centered -->
             <div class="chart-container">
                <canvas id="classScheduleChart"></canvas>
            </div>
        </div>
    </div>
</div>




<footer class="footer">
    <div class="container">
        <p class="text-muted">&copy; 2024 Horizon. All rights reserved.</p>
    </div>
</footer>


 
<script>
const scheduleCtx = document.getElementById('classScheduleChart').getContext('2d');
const classScheduleChart = new Chart(scheduleCtx, {
    type: 'bar',
    data: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], // Days of the week
        datasets: [
            {
                label: 'Web Development',
                data: [2, 0, 2, 0, 0], // Timing in hours for each day
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            },
            {
                label: 'Computer architecture',
                data: [0, 3, 0, 3, 0],
                backgroundColor: 'rgba(255, 193, 7, 0.5)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1
            },
            // Repeat for each course
        ]
    },
    options: {
        maintainAspectRatio: false, // Control aspect ratio
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Hours'
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    afterBody: function(context) {
                        // You can add additional information for tooltips here
                        return 'Class Timing: ' + context[0].raw + ' hours';
                    }
                }
            },
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});

</script>



<script>
        // Fade in effect for the content
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.querySelector('.container');
            content.style.opacity = 1;
        });

        // Animation for the welcome message
        document.addEventListener('DOMContentLoaded', function() {
            const welcomeMessage = document.querySelector('.card-title');
            welcomeMessage.style.animation = 'fadeIn 1s ease-out';
        });

        // Extra JavaScript for the logos
        const courseManagement = document.getElementById('courseManagement');
        const resources = document.getElementById('resources');
        const nutritionTips = document.getElementById('nutritionTips');

        courseManagement.addEventListener('click', function() {
            // Your JavaScript logic for Course Management
            console.log('Course Management clicked');
        });

        resources.addEventListener('click', function() {
            // Your JavaScript logic for Resources
            console.log('Resources clicked');
        });

        nutritionTips.addEventListener('click', function() {
            // Your JavaScript logic for Nutrition Tips
            console.log('Nutrition Tips clicked');
});

function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    var contentContainer = document.querySelector(".content-container"); // Selector for your main content container

    // Check if the sidebar is open (by checking its width)
    if (sidebar.style.width === "250px") {
        sidebar.style.width = "0"; // Close the sidebar
        contentContainer.style.marginLeft = "0"; // Move the content back to its original position
    } else {
        sidebar.style.width = "250px"; // Open the sidebar
        contentContainer.style.marginLeft = "250px"; // Shift the content to the right
    }
}


function closeNav() {
    document.getElementById("sidebar").style.width = "0";
}

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

function loadPage(url) {
    // Make an AJAX request to load the page content
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

document.addEventListener('DOMContentLoaded', function() {
    // Adjust content margin based on sidebar width on page load
    var sidebar = document.getElementById("sidebar");
    var content = document.getElementById("content");
    if (sidebar.style.width === "250px") {
        content.style.marginLeft = "250px";
    } else {
        content.style.marginLeft = "0";
    }
});

// Function to toggle the sidebar menu
function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".container"); // Make sure this selector targets your main content area

    // Check if the sidebar is open (by checking its width)
    if (sidebar.style.width === "250px") {
        sidebar.style.width = "0"; // Close the sidebar
        content.style.marginLeft = "0"; // Move the content back to its original position
    } else {
        sidebar.style.width = "250px"; // Open the sidebar
        content.style.marginLeft = "250px"; // Shift the content to the right
    }
}



function closeNav() {
    var sidebar = document.getElementById("sidebar");
    var content = document.querySelector(".container"); // Selector for your main content area

    sidebar.style.width = "0"; // Close the sidebar
    content.style.marginLeft = "200px"; // Reset the content margin to match the open sidebar width
}



// Close the sidebar menu if clicked outside
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
</body>
</html>


 