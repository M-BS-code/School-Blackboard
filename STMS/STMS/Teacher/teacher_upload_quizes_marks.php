<?php
session_start();
include('../includes/config.php');

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    // Redirect to teacher login page if not logged in
    header("Location: teacherLoginPage.php");
    exit;
}

// Fetch courses taught by the teacher
$teacher_id = $_SESSION['teacher_id'];
$sql_courses = "SELECT * FROM courses WHERE instructor_id = $teacher_id";
$result_courses = $conn->query($sql_courses);

// Fetch users (students)
$sql_users = "SELECT user_id, username FROM users WHERE role = 'student'";
$result_users = $conn->query($sql_users);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_marks'])) {
    $course_id = $_POST['course_id'];
    $user_id = $_POST['user_id'];
    $quiz_name = $_POST['quiz_name'];
    $marks_obtained = $_POST['marks_obtained'];

    // Validate marks obtained
    if ($marks_obtained < 0 || $marks_obtained > 10) {
        echo "Invalid marks obtained. Please enter a number between 0 and 10.";
    } else {
        // Insert marks into the database
        $sql_insert_marks = "INSERT INTO quizes (user_id, course_id, quiz_name, marks_obtained) VALUES ('$user_id', '$course_id', '$quiz_name', '$marks_obtained')";
        if ($conn->query($sql_insert_marks) === TRUE) {
            echo "Marks uploaded successfully.";
        } else {
            echo "Error: " . $sql_insert_marks . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Quiz Marks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
    --white-color: #fff;
    --paraText-color: #777;
    --heading-color: #333;
    --primary-color: rgb(31, 153, 167);
    --secondary-color: rgb(94, 7, 40);
}
.page-header {
    display: flex;
    justify-content: space-between; /* Align items to the start and end */
    align-items: center; /* Center items vertically */
    background-color: var(--secondary-color);
    color: var(--white-color);
    padding: 10px 20px; /* Adjust padding as needed */
    font-size: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,.1);
}

.page-header h1 {
    margin: 0; /* Remove default margin */
    text-align: center; /* Center the text */
    flex-grow: 1; /* Allow the text to grow to fill remaining space */
}

.hamburger {
    cursor: pointer;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}


.container {
    transition: margin-left 0.5s ease; /* Add transition for smooth shifting */
    width: auto;
    max-width: 800px; /* Or your preferred max width */
    margin: 0 auto; /* Center container */
}

.container-shifted {
    margin-left: 250px; /* Adjust based on sidebar width */
}

 
  /* General body styling for a more modern look */
body {
    font-family: 'Nunito', sans-serif; /* A more modern font choice */
    background-color: #f8f9fa;
    color: var(--paraText-color);
    margin: 0;
    padding: 0;
    transition: margin-left .5s; /* Smooth transition for toggling the sidebar */
}

 

 
/* Directly style the pie chart image */
.custom-pie-chart {
    width: 200px; /* Fixed width */
    height: auto; /* Maintain aspect ratio */
    display: block; /* Removes extra space below the image */
    margin: 0 auto; /* Center the image */
    border-radius: 8px; 
}


/* Optional: If you want to remove or simplify the container styling */
 
/* If you still want to maintain a certain max-width but make it larger */
.custom-container {
    max-width: 2000px; /* Increased max-width for a larger content area */
    margin: 30px auto; /* Center the container with a vertical margin */
    padding: 20px; /* Internal spacing */
    background: #ffffff; /* Background color */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Depth with shadow */
    border-radius: 8px; /* Rounded corners */
}

.container {
    /* If .container is used within .custom-container, consider removing max-width or ensuring it does not conflict */
    margin: auto; /* Center the container */
    padding: 15px; /* Internal spacing */
    /* Ensure there's no max-width set here if it's used within .custom-container, or adjust it to be less than .custom-container */
}

/* Form and content styling for a unified look */
.description-container {
    padding: 20px;
    background: #ffffff;
    border-radius: 8px;
    margin-bottom: 20px; /* Space between sections */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    
}

.custom-form-container {
    width: 70%; /* Adjust the width as needed */
    margin: 0 auto; /* Keep the form centered */
    padding: 20px; /* Adjust padding if needed */
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}


@media (min-width: 992px) { /* For large screens */
    .custom-form-container {
        width: 70%; /* Wider form on large screens */
    }
}

@media (max-width: 991px) { /* For medium screens and below */
    .custom-form-container {
        width: 90%; /* Slightly narrower form on smaller screens */
    }
}

@media (max-width: 576px) { /* For extra small screens */
    .custom-form-container {
        width: 100%; /* Full width on small screens */
    }
}


/* Adjust button styles for a more engaging interaction */
.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    padding: 10px 15px; /* Larger hit area */
}

.btn-primary:hover {
    background-color: darken(var(--primary-color), 10%);
}

/* Enhance readability of select and input fields */
.form-control {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}

/* Ensure responsive images within content */
.custom-image-size {
    width: 100%; /* Responsive width */
    height: auto; /* Maintain aspect ratio */
    border-radius: 8px;
}

        .custom-pie-chart-container {
            margin-top: 20px;
            padding-left: 10px;
        }

        .custom-image-size {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .custom-image-size:hover {
            transform: scale(1.05);
        }

        .custom-pie-chart {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
}



.sidebar ul li a:hover, .dropbtn:hover {
  color: var(--secondary-color);
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

   

  

        .custom-image-size:hover {
            transform: scale(1.05); /* Slightly enlarge the image on hover */
        }

    
       /* Increase width of form container */
/* Increase width of form container */
.form-container {
    width: 200%; /* Change width as needed */
    padding: 40px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f8f8f8;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
    margin-right: 20px; /* Add margin to the right to create space */
    margin-bottom: 20px; /* Add margin between form and second image */
}

 

.icon-container {
    width: 90%; /* Or any specific width */
    margin-left: auto;
    margin-right: auto; /* Center the container */
    display: block; /* Ensure it's properly formatted */
}


.custom-image-size {
    width: 100%; /* Adjust the width as needed */
    height: 520px; /* Maintain aspect ratio */
    border-radius: 15px; /* Keep the rounded corners */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Keep the shadow */
    transition: transform 0.3s ease; /* Keep the smooth scaling on hover */
}



.custom-form-container {
    width: 100%; /* Adjust width as needed */
    padding: 40px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}


footer {
    height: 50px; /* Adjust the height as needed */
 }


 .row.justify-content-center {
    display: flex;
    flex-wrap: wrap;
}
.col-md-6 {
    display: flex;
    flex-direction: column;
    justify-content: center; /* This vertically centers the content if you need */
}

.description-container {
    padding: 30px; /* Increased padding for more space around the text */
    background: #ffffff; /* Retains the white background */
    border-radius: 8px; /* Soft rounded corners */
    margin-bottom: 30px; /* Increased bottom margin for better separation */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slightly deeper shadow for more depth */
    border-left: 5px solid var(--primary-color); /* Adds a colored border to the left for visual interest */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern, legible font */
    color: var(--heading-color); /* Use the heading color for better contrast */
}
 

.pie-chart-description {
    font-size: 18px; /* Slightly larger font size for readability */
    line-height: 1.6; /* Improved line spacing for easier reading */
    text-align: justify; /* Justified text for a cleaner look */
}



.pie-chart-container {
    margin-top: 20px; /* Add margin between description and pie chart */
    padding-left: 10px; /* Add padding to the left to create space between description and pie chart */
    width: 100%; /* Adjust the width as needed */
}

.pie-chart-container img {
    max-width: 100%; /* Ensure the image fits within its container */
}

.page-header {
    width: 100%;
    background-color: var(--secondary-color);
    color: var(--white-color);
    text-align: center;
    padding: 20px 0;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.container {
    transition: margin-left 0.5s ease;
    width: auto;
    max-width: 800px; /* Or your preferred max width */
    margin: 0 auto; /* Center container */
}

.custom-form-container {
    /* Your existing styles */
    background-color: #f9f9f9; /* Slightly off-white background for the form area */
    padding: 25px; /* Adjust padding as needed */
}

.btn-primary {
    background-color: var(--secondary-color); /* Use the secondary color for primary buttons */
    border: none; /* Remove the border */
    padding: 12px 20px; /* Larger padding for a more prominent button */
    font-size: 16px; /* Larger font size for clarity */
    border-radius: 5px; /* Rounded corners for the button */
}

.btn-primary:hover {
    background-color: darken(var(--secondary-color), 10%); /* Darken button on hover for feedback */
}

.custom-container {
    max-width: 1200px; /* Increased max width for larger screens */
    margin: 30px auto; /* Increased top and bottom margin for more space */
    padding: 40px; /* Increased padding for more internal space */
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Subtle shadow for depth */
    border-radius: 8px; /* Soften the edges */
}

/* Adjust this class based on sidebar state */
.container-shifted {
    margin-left: 250px; /* Adjust based on sidebar width */
}

        
    </style>
</head>
<body>


 
<header class="page-header">
    <div class="hamburger" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <h1>Upload Quiz Marks</h1>
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

    
    <!-- Main Content -->
 
    <div class="container custom-container" id="main-content">
    <div class="row justify-content-center">
        <!-- Quiz Image Container -->
        <div class="col-md-6">
            <div class="icon-container">
                            <img src="../images/quiz.png" alt="Quiz" class="custom-image-size" style="width:100%;">
                        </div>
                    </div>
                    <div class="col-md-6">
            <div class="form-container custom-form-container">
                            <!-- Quiz Marks Upload Form -->
                            <h1 class="text-center mb-4">Upload Quiz Marks</h1>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group">
                                    <label for="course_id">Select Course:</label>
                                    <select name="course_id" id="course_id" class="form-control" required>
                                        <option value="">Select Course</option>
                                        <?php while ($row_course = $result_courses->fetch_assoc()) { ?>
                                            <option value="<?php echo $row_course['course_id']; ?>"><?php echo $row_course['course_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="user_id">Select Student:</label>
                                    <select name="user_id" id="user_id" class="form-control" required>
                                        <option value="">Select Student</option>
                                        <?php while ($row_user = $result_users->fetch_assoc()) { ?>
                                            <option value="<?php echo $row_user['user_id']; ?>"><?php echo $row_user['username']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quiz_name">Quiz Name:</label>
                                    <input type="text" name="quiz_name" id="quiz_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="marks_obtained">Marks Obtained:</label>
                                    <input min="0" max="10" type="number" name="marks_obtained" id="marks_obtained" class="form-control" min="0" max="100" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" name="upload_marks">Upload Marks</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Description and Pie Chart container -->
                <div class="row mt-5">
                    <div class="col-md-6">
                        <!-- Description container -->
                        <div class="description-container custom-description-container">
                            <!-- Description of Quiz Grading -->
                            <p class="pie-chart-description">Description: This pie chart represents the distribution of marks obtained by students in the quiz.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                    
    
                    <div class="pie-chart-container" style="width: 100%; height: auto;">
    <canvas id="assessmentDistributionChart"></canvas>
</div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="mt-5"></footer>

 
<script>
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        var container = document.querySelector(".custom-container");

        if (sidebar.style.width === "250px" || sidebar.classList.contains("sidebar-open")) {
            sidebar.style.width = "0";
            sidebar.classList.remove("sidebar-open");
            container.classList.remove("container-shifted"); // Adjust the main content margin
        } else {
            sidebar.style.width = "250px";
            sidebar.classList.add("sidebar-open");
            container.classList.add("container-shifted"); // Adjust the main content margin
        }
    }

    function closeNav() {
        var sidebar = document.getElementById("sidebar");
        var container = document.querySelector(".custom-container");

        sidebar.style.width = "0";
        sidebar.classList.remove("sidebar-open");
        container.classList.remove("container-shifted"); // Adjust the main content margin
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





<script>
    const ctx = document.getElementById('assessmentDistributionChart').getContext('2d');
    const assessmentDistributionChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Finals', 'Assignment', 'Quiz'],
            datasets: [{
                label: 'Assessment Distribution',
                data: [35, 30, 35],
                backgroundColor: [
                    'rgba(66, 165, 245, 0.5)',
                    'rgba(255, 235, 59, 0.5)',
                    'rgba(239, 83, 80, 0.5)'
                ],
                borderColor: [
                    'rgba(66, 165, 245, 1)',
                    'rgba(255, 235, 59, 1)',
                    'rgba(239, 83, 80, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true, // Change to false if you want to control the height independently
            aspectRatio: 2, // You can adjust this value to control the aspect ratio
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Assessment Distribution: Assignment, Quiz, Finals'
                }
            }
        }
    });
</script>


</body>
</html>