<?php
session_start();
include('../includes/config.php');

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to student login page if not logged in
    header("Location: StudentLoginPage.php");
    exit;
}

// Fetch student's major
$student_id = $_SESSION['student_id'];
$sql_major = "SELECT major_id FROM student_major WHERE student_id = $student_id";
$result_major = $conn->query($sql_major);
$major = $result_major->fetch_assoc();
$major_id = $major['major_id'];

// Fetch available courses for the student's major
$sql_courses = "SELECT course_id, course_name FROM courses WHERE major_id = $major_id";
$result_courses = $conn->query($sql_courses);
$courses = array();
if ($result_courses->num_rows > 0) {
    while ($row_course = $result_courses->fetch_assoc()) {
        $courses[] = $row_course;
    }
}

// Process course registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_course'])) {
    $course_id = $_POST['course_id'];

    // Check if the student is already registered for the course
    $sql_check = "SELECT * FROM registered_courses WHERE course_id = $course_id AND student_id = $student_id";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "You are already registered for this course.";
    } else {
        // Register the student for the course
        $sql_register = "INSERT INTO registered_courses (course_id, student_id) VALUES ($course_id, $student_id)";
        if ($conn->query($sql_register) === TRUE) {
            echo "Course registered successfully.";
        } else {
            echo "Error: " . $sql_register . "<br>" . $conn->error;
        }
    }
}
?><!DOCTYPE html>
<html>
<head>
    <title>Course Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
    /* Header styles */
    .header {
        background-color: var(--secondary-color);
        color: var(--white-color);
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header h2 {
        margin: 0;
        text-align: center;
        flex: 1;
    }

    /* Custom card title style */
    .custom-card-title {
        margin-bottom: 0;
    }

    .card-body {
        padding: 20px;
        padding-bottom: 50px;
    }

    .motivation {
        text-align: center;
        margin-bottom: 20px;
    }

    .motivation p {
        font-style: italic;
        color: #777;
    }

    /* AI Icon Styles */
    .ai-section {
        text-align: center;
        margin-top: 40px; /* Increased margin top */
    }

    .ai-title {
        margin-top: 20px; /* Margin top for the title */
        margin-bottom: 20px; /* Margin bottom for the title */
        text-align: center;
        font-size: 24px;
        font-weight: bold;
    }

    .ai-description {
        text-align: center;
        color: #777;
    }

    .ai-icons {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .ai-icon {
        text-align: center;
        margin: 30px; /* Increased margin around each icon */
        transition: transform 0.3s ease; /* Add transition effect */
    }

    .ai-icon i {
        font-size: 70px; /* Increased icon size */
        margin-bottom: 15px; /* Increased margin below icon */
    }

    /* Added spacing between icons */
    .ai-icons .ai-icon:not(:last-child) {
        margin-right: 50px; /* Increased right margin */
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

    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <!-- Menu Toggle Button -->
        <?php include('StudentNavbar.php'); ?>
        <!-- Header Title -->
        <h2 class="header-title mb-0">Courses Registration</h2>
    </div>
    <!-- End Header Section -->

    <div class="content">

    <div class="container">
        <div class="motivation" style="margin-top: 50px; margin-bottom: 50px;">
            <h3>Explore the Exciting World of Computer Science</h3>
            <p>“The best way to predict the future is to invent it.” - Alan Kay</p>
            <p>“Success is not final, failure is not fatal: It is the courage to continue that counts.” - Winston Churchill</p>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h2 class="custom-card-title">Register for Courses</h2>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="course">Select Course:</label>
                        <select class="form-control" name="course_id">
                            <?php foreach ($courses as $course) { ?>
                                <option value="<?php echo $course['course_id']; ?>"><?php echo $course['course_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="register_course">Register</button>
                </form>
            </div>
        </div>
    </div>

    <!-- AI Icons Section -->
    <div class="ai-section">
        <div class="ai-title">Interested in Computer Science?</div>
        <div class="ai-description">Explore the potential of Computer Science in various fields!</div>
        <div class="ai-icons">
            <div class="ai-icon" onmouseover="zoomIn(this)" onmouseout="zoomOut(this)">
                <i class="fas fa-user-md fa-3x"></i>
                <div class="ai-description">AI in Healthcare</div>
            </div>
            <div class="ai-icon" onmouseover="zoomIn(this)" onmouseout="zoomOut(this)">
                <i class="fas fa-graduation-cap fa-3x"></i>
                <div class="ai-description">AI in Education</div>
            </div>
            <div class="ai-icon" onmouseover="zoomIn(this)" onmouseout="zoomOut(this)">
                <i class="fas fa-chart-line fa-3x"></i>
                <div class="ai-description">AI in Finance</div>
            </div>
        </div>
    </div>
    </div>
    <footer>
    <p>@ All Rights Reserved for Horizon Academy</p>
</footer>

    <script>
        function zoomIn(element) {
            element.style.transform = "scale(1.2)"; /* Zoom in effect */
        }

        function zoomOut(element) {
            element.style.transform = "scale(1)"; /* Zoom out effect */
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
</body>
</html>
