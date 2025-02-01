<!-- Sidebar -->
<div class="hamburger" onclick="toggleMenu()">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
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