<?php
session_start();
include('../includes/config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login page if not logged in
    header("Location: AdminSignup.php");
    exit;
}

// Fetch number of students
$sqlStudents = "SELECT COUNT(*) AS total_students FROM Users WHERE role='student'";
$resultStudents = $conn->query($sqlStudents);
$rowStudents = $resultStudents->fetch_assoc();
$totalStudents = $rowStudents['total_students'];

// Fetch number of teachers
$sqlTeachers = "SELECT COUNT(*) AS total_teachers FROM Users WHERE role='teacher'";
$resultTeachers = $conn->query($sqlTeachers);
$rowTeachers = $resultTeachers->fetch_assoc();
$totalTeachers = $rowTeachers['total_teachers'];

// Fetch number of courses
$sqlCourses = "SELECT COUNT(*) AS total_courses FROM Courses";
$resultCourses = $conn->query($sqlCourses);
$rowCourses = $resultCourses->fetch_assoc();
$totalCourses = $rowCourses['total_courses'];
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet"href="../css/styles.css">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
    :root {
        --white-color: #fff;
        --paraText-color: #777;
        --heading-color: #333;
        --primary-color: #d3ac7c;
        --secondary-color: rgb(0, 38, 128);
    }
    
    .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        /* Reset the margins for the row and column if they've been previously overridden */
.row {
    margin-right: -15px;
    margin-left: -15px;
}


        /* Adjust the padding inside the column to create space between cards */
        .col-md-4 {
    padding: 0.75rem; /* This value can be adjusted based on your spacing preference */
}
    /* You might also need to reset the padding for .card if it's affecting the layout */
        .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center; /* Horizontally centers the text */
    height: 100%; /* You might need to set a specific height if the content is not filling the card */
}

/* Additional styles */
.card {
    border-radius: 8px; /* Adjust as desired */
    margin: 1rem; /* Add some space around the card */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Optional: Adds a subtle shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease !important;
}

.card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 12px 24px rgba(0,0,0,0.2) !important;
}

    /* Additional styles for separation and emphasis */
    .content-section {
    margin-bottom: 20px; /* Space between sections */
}

.section-paragraph {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 0 0 8px 8px; /* Continue the card's rounded corners at the bottom */
}
h1 {
        text-align: center;
    }

    :root {
            --white-color: #fff;
            --paraText-color: #777;
            --heading-color: #333;
            --primary-color: rgb(31, 153, 167);
            --secondary-color: rgb(94, 7, 40);
        }
        .dashboard-header {
            background-color: var(--secondary-color);
            padding: 20px;
            display: flex;
            justify-content: space-between; 
            align-items: center; 
        }

        .menu-icon {
            font-size: 24px; 
            cursor: pointer; 
            align-items: center; 
            

        }

        .dashboard-title {
            color: white;
            display: flex;
            align-items: center; 
            margin-right: 700px; 
            justify-content: center;
        }   
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">  

            <div class="col-md-12 dashboard-header">
            <?php include('AdminNavbar.php'); ?>
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>Welcome to Admin Dashboard</h1>
            </div>
        </div>
    </div>

    <div class ="container-fluid">
    <div class="row">
        <!-- Students Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Number of Students</h5>
                    <p class="card-text"><?php echo $totalStudents; ?></p>
                    <p>Our vibrant student community is the heartbeat of our institution. Each individual brings a unique set of skills, interests, and cultural backgrounds, contributing to a rich and diverse learning environment. We are proud to support their academic journey, offering a wide range of programs designed to empower them for future success.</p>
                </div>
            </div>
        </div>
        <!-- Teachers Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Number of Teachers</h5>
                    <p class="card-text"><?php echo $totalTeachers; ?></p>
                    <p>Our dedicated teachers are the cornerstone of our educational mission. With a passion for teaching and a commitment to excellence, they inspire students to reach their full potential. Their expertise and innovative teaching methods ensure a high-quality learning experience that prepares students for the challenges ahead.</p>
                </div>
            </div>
        </div>
        <!-- Courses Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Number of Courses</h5>
                    <p class="card-text"><?php echo $totalCourses; ?></p>
                    <p>Our comprehensive curriculum spans a wide range of disciplines, designed to meet the evolving needs of our students and the workforce. From foundational courses to advanced specialization topics, our programs are crafted to foster critical thinking, creativity, and hands-on experience, equipping students with the skills necessary for lifelong success.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
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
    </script>
</body>
</html>

