<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <title>Admin Sidebar Menu</title>
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
        <div class="sidebar-title">Admin</div>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li class="menu-item dropdown">
                <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown(this)">Users</a>
                <div class="dropdown-content">
                    <a href="admin_students.php">Students</a>
                    <a href="admin_teachers.php">Teachers</a>
                    <a href="admin_admins.php">Admins</a>
                </div>
            </li>
            <li><a href="admin_majors.php">Majors</a></li>
            <li><a href="admin_assign_major.php">Assign Majors</a></li>
            <li><a href="admin_courses.php">Courses</a></li>

            
            <li><a href="logout.php">Logout</a></li> 
        </ul>
    </div>
</body>


<script>
  function toggleMenu() {
    var sidebar = document.getElementById("sidebar");
    sidebar.style.width = sidebar.style.width === "250px" ? "0" : "250px";
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



</script>
</html>
