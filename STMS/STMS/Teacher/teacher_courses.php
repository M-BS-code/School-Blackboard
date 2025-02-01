<?php
session_start();
include('../includes/config.php');

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    // Redirect to teacher login page if not logged in
    header("Location: teacherLoginPage.php");
    exit;
}

// Fetch courses for the teacher
$sql_courses = "SELECT * FROM Courses WHERE instructor_id = {$_SESSION['teacher_id']}";
$result_courses = $conn->query($sql_courses);
$courses = [];
if ($result_courses->num_rows > 0) {
    while ($row_course = $result_courses->fetch_assoc()) {
        $courses[] = $row_course;
    }
}

 

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.0/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
 



<style>

 
 

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.header-title {
    position: absolute;
    width: 100%;
    left: 0;
    text-align: center;
    font-size: 24px; /* Adjust font size as needed */
    font-weight: bold;
    color: var(--white-color); /* Assuming you have this color variable defined */
}

.hamburger {
    z-index: 1000; /* Ensure it's above the sidebar */
    position: relative; /* May already be set */
}
 
header {
    background-color: var(--secondary-color);

    z-index: 1; /* Ensure this is lower than the sidebar's z-index */
    /* Other properties remain unchanged */
}

.hamburger {
    z-index: 1; /* This ensures it is not above the sidebar */
    /* Other properties remain unchanged */
}


 
header {
    position: relative; /* Ensures absolute positioning is relative to the header */
    display: flex;
    justify-content: center; /* Center the items horizontally */
    align-items: center; /* Align items vertically */
    /* Other properties remain unchanged */
}


.sidebar {
    width: 0;
    height: 100%;
    position: fixed;
    z-index: 2;
    top: 0;
    transition: 0.5s;
    /* Adjust 'left' or 'right' based on slide direction */
    left: 0; /* For left slide-in */
    /* right: 0; For right slide-in */
    overflow-x: hidden;
    padding-top: 60px; /* Adjust as needed */
    /* Rest of the sidebar styling */
}

 
.main-content {
    transition: margin-left 0.3s ease; /* Adjust the duration and timing function as needed */
}

.main-content-shifted {
    margin-left: 250px; /* Adjust this value based on the actual sidebar width */
}

.sidebar-shifted {
            width: 250px;
        }
 

.container-flex {
    display: flex;
    flex-wrap: wrap; /* Allows items to wrap as needed */
    gap: 20px; /* Space between courses and calendar */
    margin: 20px; /* Outer margin for overall spacing */
}

.courses-container {
    flex: 3; /* Takes up more space in the flex container */
    display: flex;
    flex-direction: column;
    gap: 0px; /* Space between individual course cards */
}

.calendar-container {
    flex: 2; /* Allows the calendar to take up less space than courses */
    height: auto; /* Adjust height as needed */
}

/* Ensure calendar iframe resizes correctly */
.calendar-container iframe {
    width: 100%; /* Full width of its container */
    height: 300px; /* Adjust height to your preference */
    border: 0; /* Remove any default border */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container-flex {
        flex-direction: column; /* Stack them on smaller screens */
    }

    .calendar-container iframe {
        height: 500px; /* Adjust height for smaller screens */
    }
}

:root {
    --white-color: #fff;
    --paraText-color: #777;
    --heading-color: #333;
    --primary-color: rgb(31, 153, 167);
    --secondary-color: rgb(94, 7, 40);
}
body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    font-family: 'Nunito', sans-serif; /* Example of modern font */
}

header {
    color: var(--white-color);
    background-color: var(--secondary-color);
    padding: 20px 0;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    width: 100%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* subtle shadow for depth */
}

/* Enhancing the Course Cards with the new palette */
.course-card {
    background-color: var(--white-color);
    border: none;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Soft shadow for depth */
    border-radius: 8px; /* Rounded corners */
    transition: transform .3s; /* Smooth transition for hover effect */
}

.course-card:hover {
    transform: scale(1.03); /* Slight scale up on hover */
    box-shadow: 0 6px 12px rgba(0,0,0,0.2); /* Deeper shadow on hover */
}

/* Utilizing primary color for interactive elements */
.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: darken(var(--primary-color), 10%);
    border-color: darken(var(--primary-color), 10%);
}

/* Additional style adjustments for course details */
.course-title {
    color: var(--heading-color);
    font-size: 18px;
    font-weight: bold;
}

.card-text {
    color: var(--paraText-color);
}

/* Custom scrollbar for a modern look */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--white-color);
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
}

::-webkit-scrollbar-thumb:hover {
    background: darken(var(--primary-color), 10%);
}

    .course-color-0 {
        background-color: #F0F8FF; /* Alice Blue */
    }
    .course-color-1 {
        background-color: #FFFAF0; /* Floral White */

     }
    .course-color-2 {
        background-color: #FFCCFF; /* Lavender Pink */
    }
    .course-color-3 {
        background-color: #F0F8FF; /* Alice Blue */
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
  background-color: #fff; /* Makes the lines white */
  transition: all 0.3s ease;
}

 

.sidebar {
  height: 100%;
  z-index: 500; /* Adjusted to be lower than the hamburger menu */

  width: 0;
  position: fixed;
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

.teacher-avatar-icon {
    color: black; /* Adjust the color as needed */
    margin-right: 0.5rem; /* Adds spacing to the right of the icon */
}
.task-overview .progress-wrapper {
    margin-bottom: 10px;
}

.task-overview .task-label {
    font-size: 14px;
    margin-bottom: 5px;
    margin-top: 30px;

    display: block;
}


.task-overview h3 {
    font-size: 24px; /* Example size, adjust as needed */
    color: #808080; /* Light grey color */
    margin-top: 40px; /* Adds space above the heading */
    margin-bottom: 10px; /* Adds space below the heading */
}


.progress-bar {
    transition: width 1s ease-in-out;
}

/* Customize progress-bar colors as needed */
.progress-bar.bg-success {
    background-color: #28a745;
}

.progress-bar.bg-info {
    background-color: #17a2b8;
}
.dashboard-sections {
    display: flex;
    justify-content: space-between; /* Adjust alignment as needed */
}

 
 

/* CSS styles */
.container-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px;
}

.courses-container {
    flex: 1; /* Adjust the size of the courses container */
}

.calendar-container {
    flex: 1; /* Adjust the size of the calendar container */
    margin-left: 200px; /* Push the calendar to the right */
    max-width: calc(50% - 20px); /* Set the maximum width to 50% of the container width minus the margin */
}


 
/* Responsive design */
@media (max-width: 768px) {
    .dashboard-top-row {
        flex-direction: column;
    }

    .welcome-section {
        margin-right: 0; /* No margin on the right in small screens */
        margin-bottom: 20px; /* Adds space between welcome section and courses section */
    }

    /* Ensure the courses and calendar sections take full width on smaller screens */
    .courses-section{
        width: 100%;
    }
}


 

/* Optional: Adjust padding for better spacing */
.courses-section .card-body {
    padding: 20px; /* Adjust padding as needed */
}


.course-card {
    margin-bottom: 2rem; /* Standardizes bottom margin */
    padding: 1.5rem; /* Increases padding inside the card for more space */
    box-shadow: 0 6px 12px rgba(0,0,0,0.1); /* Enhances shadow for depth */
}

#courses-section .col-md-12 {
    max-width: 100%; /* Ensures it takes full available width */
    /* Add more styles here as needed */
}
 
.welcome-section {
    flex: 1; /* Adjust the size as needed */
    margin-right: 20px; /* Add spacing between sections */
}

.courses-section {
    flex: 1; /* Adjust the size as needed */
    margin-left: 20px; /* Add spacing between sections */
}

.welcome-container {
    display: flex;
    justify-content: center; /* Center horizontally */
 }




 

 .task-manager h4 {
    color: #808080; /* Dark grey for the title */
    margin-bottom: 15px; /* Space below the title */
}
 

/* Ensure proper layout for the container-flex */
.container-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 20px;
}

.card-title {
    color: #333; /* Darker shade for better contrast */
    font-size: 32px; /* Larger font size for emphasis */
    font-weight: bold; /* Bold weight for prominence */
    margin-bottom: 20px; /* Added space below the title for better separation */
    text-align: center; /* Keeps title centered */
}

.description-text {
    color: #444; /* Slightly darker for improved readability */
    font-size: 22px; /* Increased font size for visibility */
    line-height: 1.6; /* Adjusted line height for readability */
    margin: 20px 0; /* Added top and bottom margin for better spacing */
    text-align: justify; /* Justified text for a cleaner look */
    padding: 0 20px; /* Padding on the sides for breathing room */
}

/* Style adjustments for the card containing the description for added emphasis */
.card {
    background-color: #fff; /* Light background to keep focus on content */
    border: 1px solid #eee; /* Subtle border for definition */
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Soft shadow for depth */
    transition: box-shadow 0.3s ease-in-out; /* Smooth transition for hover effect */
}

.card:hover {
    box-shadow: 0 8px 12px rgba(0,0,0,0.2); /* Deeper shadow on hover for interactivity */
}

/* Additional styles for responsiveness and padding adjustments */
@media (max-width: 768px) {
    .card-title {
        font-size: 28px; /* Slightly smaller title on small screens */
    }

    .description-text {
        font-size: 20px; /* Adjusted font size for smaller screens */
        padding: 0 10px; /* Reduced padding on the sides */
    }

    .card {
        margin: 10px; /* Smaller margin to utilize space efficiently */
    }
}
.description-section {
    width: auto; /* Adjust width as needed, not more than 100% */
    max-width: 1200px; /* Example max-width, adjust as needed */
    margin: auto;
 
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 8px;
    top: 10%;
    left: 20%;
    
 
  }

 

 
 



.calendar-container {
    width: 100vw; /* Sets the container width to be 100% of the viewport width */
    position: relative; /* Needed if you plan to adjust the element's position */
    left: 38%; /* Moves the element to the center */
    transform: translateX(-50%); /* Centers the element with respect to its width */
    /* Other properties can remain as needed */
}


.calendar-container {
    max-width: 100%; /* Ensures the container doesn't overflow the parent's width */
    padding: 15px; /* Adds some spacing around the calendar */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Gives a slight shadow for depth */
    background-color: #ffffff; /* Sets the background color */
    border-radius: 8px; /* Rounds the corners for a modern look */
    margin-top: 20px; /* Adds space between this container and whatever is above it */
}

.calendar-container iframe {
    width: 100%; /* Ensures the calendar iframe takes up all the container width */
    border: none; /* Removes the default border */
    height: 600px; /* Sets a fixed height, adjust as needed */
}

/* Responsive adjustments for smaller screens */
@media (max-width: 768px) {
    .calendar-container {
        margin-top: 20px; /* Increases spacing on smaller screens */
        padding: 10px; /* Adjusts padding for smaller screens */
    }
    .calendar-container iframe {
        height: 400px; /* Adjusts height for smaller screens */
    }
}


 


</style>
 

</head>
<body>
<header>
        <div class="header-content">
            <div class="hamburger" onclick="toggleMenu()">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
            <div class="header-title">Courses Dashboard</div>
        </div>
    </header>
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
 
    <div class="main-content">

    <div class="container-flex">
    <div class="courses-container">
        <div class="card courses-section mb-4">
            <div class="card-header">
                <h3 class="card-title">Your Courses</h3>
            </div>
            <div class="card-body">
                <?php foreach ($courses as $index => $course) { ?>
                    <?php
                        $class_index = $index % 4;
                        $class_name = "course-color-" . $class_index;
                    ?>
                    <div class="course-card mb-4 <?php echo $class_name; ?>">
                        <div class="card-body">
                            <h5 class="course-title"><?php echo $course['course_name']; ?></h5>
                            <p class="card-text"><?php echo $course['course_description']; ?></p>
                            <a href="#" class="btn btn-primary add-assignment" data-course-id="<?php echo $course['course_id']; ?>">
                                <i class="fas fa-arrow-right"></i> Add Assignment
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>



  
    <div class="welcome-container">
    <div class="card welcome-section mb-4">
        <div class="card-body">
            <!-- User Icon and Welcome Message Container -->
            <div class="welcome-message d-flex align-items-center mb-3"> <!-- Added margin-bottom -->
                <i class="fas fa-user-circle teacher-avatar-icon fa-4x"></i>
                <div class="ml-3">
                    <h2 id="greetingMessage"><?php echo $_SESSION['teacher_username']; ?>, welcome back!</h2>
                </div>
            </div>
                    <div class="task-overview">
                        <h3>Here's what's happening today:</h3>
                        <div class="progress-wrapper mb-3">
                            <span class="task-label">Grade Assignments</span>
                            <div class="progress">
                                <div id="gradeProgress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <span class="task-label">Prepare for Meetings</span>
                            <div class="progress">
                                <div id="meetingProgress" class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="progress-wrapper mb-3">
                            <span class="task-label">Check Messages</span>
                            <div class="progress">
                                <div id="messageProgress" class="progress-bar bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!-- Task Manager Section -->
                        <div class="task-manager mt-4">
                            <h4>Task Manager</h4>
                            <p>Add your tasks for today and keep track of your progress.</p>
                            <!-- Task Form -->
                            <form id="taskForm" class="mt-3">
                                <div class="form-group d-flex align-items-center"> <!-- Align items vertically -->
                                    <input type="text" id="taskInput" class="form-control mr-2" placeholder="Add a new task">
                                    <button type="submit" class="btn btn-primary btn-sm"> <!-- Reduced button size -->
                                        Add Task
                                    </button>
                                </div>
                            </form>
                            <!-- Task List -->
                            <ul id="tasksList" class="list-group mt-3">
                                <!-- Tasks will be dynamically added here -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animated Progress Bars
    document.addEventListener("DOMContentLoaded", function() {
        animateProgressBar('gradeProgress', 60);
        animateProgressBar('meetingProgress', 40);
        animateProgressBar('messageProgress', 75);
    });

    function animateProgressBar(elementId, targetWidth) {
        var progressBar = document.getElementById(elementId);
        var currentWidth = 0;
        var interval = setInterval(function() {
            if (currentWidth >= targetWidth) {
                clearInterval(interval);
            } else {
                currentWidth++;
                progressBar.style.width = currentWidth + "%";
            }
        }, 20);
    }

   
</script>

           
<div class="container-flex">
    <!-- Task Management Section -->
    <div class="description-container">
        <div class="card description-section mb-4">
            <div class="card-body">
                <h2 class="card-title">Task Management</h2>
                <p class="description-text">
                    Professors or doctors can efficiently manage their tasks and schedules using the calendar below.
                </p>
                <!-- Add a button to toggle additional information -->
                <button id="toggleInfoBtn" class="btn btn-primary">Show More</button>
                <div id="additionalInfo" style="display: none;">
                    <p>To facilitate a streamlined and uniform approach to task management, educators can utilize the calendar presented below as a strategic tool for keeping abreast of their work schedule. This interactive calendar serves not merely as a daily planner but as a comprehensive organizational system that accommodates the long-term tracking of duties and objectives. Teachers can meticulously curate and upload their to-do lists, encompassing an array of activities and responsibilities spread over several years. By leveraging this calendar to its full potential, teachers can ensure that they remain on top of their commitments, maintaining a clear overview of upcoming tasks, deadlines, and milestones that shape the educational journey they navigate alongside their students. This proactive planning and foresight are instrumental in crafting a well-ordered and productive academic environment.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Section Moved Here -->
    <div class="calendar-container">
        <div class="card calendar-section mb-4">
            <div class="card-body">
                <iframe src="https://calendar.google.com/calendar/embed?src=67d55346daf51aecd970f69d9c5752fdd1c44c03a153f80749407dc060a1f718%40group.calendar.google.com&ctz=Asia%2FDubai" style="border: 0; width: 100%; min-width: 400px;" height="400" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
    </div>
</div>





<script>
 

 document.addEventListener('DOMContentLoaded', function() {
    // Array to store tasks
    let tasks = [];

    // Function to render tasks
    function renderTasks() {
        const tasksList = document.getElementById('tasksList');
        // Clear existing tasks
        tasksList.innerHTML = '';
        // Render each task
        tasks.forEach((task, index) => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.textContent = `${index + 1}. ${task.taskText}`; // Add task number
            // Add check button
            const checkButton = document.createElement('button');
            checkButton.className = 'btn btn-success btn-sm check-task';
            checkButton.textContent = 'Check';
            checkButton.addEventListener('click', function() {
                tasks.splice(index, 1);
                renderTasks();
            });
            listItem.appendChild(checkButton);
            tasksList.appendChild(listItem);
        });
    }

    // Function to handle form submission
    document.getElementById('taskForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const taskInput = document.getElementById('taskInput');
        const taskText = taskInput.value.trim();
        if (taskText !== '') {
            tasks.push({ taskText: taskText });
            taskInput.value = '';
            renderTasks();
        }
    });

    // Initial rendering of tasks
    renderTasks();
});

</script>







<script>


 
    // JavaScript to toggle additional information when the button is clicked
    document.getElementById('toggleInfoBtn').addEventListener('click', function() {
        var additionalInfo = document.getElementById('additionalInfo');
        if (additionalInfo.style.display === 'none') {
            additionalInfo.style.display = 'block';
            this.textContent = 'Show Less'; // Change button text
        } else {
            additionalInfo.style.display = 'none';
            this.textContent = 'Show More'; // Change button text
        }
    });
</script>


 
  
<!-- Include Bootstrap and other scripts if needed -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('.add-assignment').click(function(e){
            e.preventDefault();
            var courseId = $(this).data('course-id');
            if(confirm('Are you sure you want to add an assignment to this course?')){
                window.location.href = 'teacher_course_assignment.php?course_id=' + courseId;
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.add-assignment').click(function(e){
            e.preventDefault();
            var courseId = $(this).data('course-id');
            if(confirm('Are you sure you want to add an assignment to this course?')){
                window.location.href = 'teacher_course_assignment.php?course_id=' + courseId;
            }
        });
    });
</script>





 






 


<script>
function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    var mainContent = document.querySelector(".main-content"); // Changed to querySelector to target class

    if (sidebar.style.width === "250px") {
        sidebar.style.width = "0";
        mainContent.classList.remove("main-content-shifted"); // Use classList to remove the shifting class
    } else {
        sidebar.style.width = "250px";
        mainContent.classList.add("main-content-shifted"); // Use classList to add the shifting class
    }
}


function closeNav() {
    document.getElementById("sidebar").style.width = "0";
    var mainContent = document.querySelector(".main-content"); // Select main content by class name
    if (mainContent) mainContent.classList.remove("main-content-shifted"); // Remove shifting class
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



</script>
</body>
</html>