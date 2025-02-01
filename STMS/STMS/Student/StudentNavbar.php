<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <title>Student Sidebar Menu</title>
    <style>
        .hamburger .line {
         background-color: white;
        }

    </style>
</head>
<body>
<div class="hamburger" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <div class="sidebar" id="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <div class="sidebar-title">Student</div>
        <ul>
            <li><a href="student_dashboard.php">Dashboard</a></li>
            <li><a href="student_register_courses.php"> Register Courses</a></li>      
            <li><a href="student_registered_courses.php"> My Courses</a></li>
            <li><a href="Notes.php">My Notes</a></li>

            <li class="menu-item dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown(this)"> Grades</a>
                <div class="dropdown-content">
                    <a href="student_assignments_grades.php">Assignments</a>
                    <a href="student_quizes_grades.php">Quizzes</a>
                    <a href="student_final_grades.php">Finals</a>
                </div>
            </li>
            <li><a href="logout.php">Logout</a></li> 
        </ul>
    </div>









<script>
function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    var notesWrapper = document.querySelector(".wrapper");
    var isExpanded = sidebar.style.width === "250px"; 
    sidebar.style.width = isExpanded ? "0" : "250px"; 
    notesWrapper.style.marginLeft = isExpanded ? "0px" : "250px";
}

function closeNav() {
    var sidebar = document.getElementById("sidebar");
    var notesWrapper = document.querySelector(".wrapper");
    sidebar.style.width = "0"; 
    notesWrapper.style.marginLeft = "50px"; 
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
</html>
