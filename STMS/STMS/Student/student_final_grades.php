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

// Fetch final exam marks of the student
$sql_final_exam_marks = "SELECT c.course_name, fem.marks_obtained, fem.total_marks
                        FROM final_exam_marks fem
                        INNER JOIN courses c ON fem.course_id = c.course_id
                        WHERE fem.student_id = $student_id";
$result_final_exam_marks = $conn->query($sql_final_exam_marks);
$final_exam_marks = array();
if ($result_final_exam_marks->num_rows > 0) {
    while ($row_final_exam_mark = $result_final_exam_marks->fetch_assoc()) {
        $final_exam_marks[] = $row_final_exam_mark;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Final Exam Marks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<style>
    :root {
        --white-color: #fff;
        --paraText-color: #777;
        --heading-color: #333;
        --primary-color: rgb(31, 153, 167);
        --secondary-color: rgb(94, 7, 40);
    }
    .content {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 20px auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        width: 80%;
    }

    .chart-and-image {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .chart-container, .image-container {
        flex: 1;
        margin: 10px;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        max-width: 600px; /* You might want to adjust this based on your layout */
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .image-hover:hover .img-fluid {
        transform: scale(1.05); /* Slightly enlarges the image on hover */
        transition: transform 0.5s ease;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

</style>
<body>
  
<div class="container-fluid">
    <div class="row">  
    
        <!-- Dashboard Header -->
        <div class="col-md-12 dashboard-header">
            <?php include('StudentNavbar.php'); ?>
            <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i> My Final Exam Marks</h1>
        </div>
    </div>
</div>

    <div class="content">
        <h2 class="text-center mb-4">My Final Exam Marks</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Obtained Marks</th>
                        <th>Total Marks</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php foreach ($final_exam_marks as $final_exam_mark) { ?>
                        <tr>
                            <td><?php echo $final_exam_mark['course_name']; ?></td>
                            <td><?php echo $final_exam_mark['marks_obtained']; ?></td>
                            <td><?php echo $final_exam_mark['total_marks']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    

        <div class="d-flex justify-content-center align-items-center chart-and-image">
            <div class="chart-container mr-3">
                <canvas id="barChart"></canvas> <!-- Changed ID to barChart -->
            </div>
            <div class="image-container image-hover">
                <!-- Image Section can remain unchanged -->
                <img src="../images/finalExams.JPG" alt="Your Image Description" class="img-fluid">
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

    document.addEventListener('DOMContentLoaded', function () {
        var finalExamMarks = <?php echo json_encode($final_exam_marks); ?>;

        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: finalExamMarks.map(item => item.course_name),
                datasets: [{
                    label: 'Marks Obtained',
                    data: finalExamMarks.map(item => parseInt(item.marks_obtained)),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Final Exam Marks (Bar Chart)'
                    }
                }
            }
        });
    });
</script>

    
    

</body>



</html>