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
    <title>My Quiz Marks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
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
    /* Styles.css content */
body {
    background-color: #f8f9fa; /* Light gray background */
    font-family: 'Arial', sans-serif; /* Set the default font */
}

.content {
    margin: 20px; /* Add some space around the main content */
    background-color: #ffffff; /* White background for the content area */
    padding: 20px; /* Space inside the content area */
    border-radius: 5px; /* Rounded corners for the content area */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Slight shadow for depth */
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6 !important; /* Ensures borders are visible */
}



.table-responsive {
    margin-bottom: 20px; /* Space below the table before the chart */
}

h2.text-center {
    color: #333; /* Dark gray color for the heading */
    margin-bottom: 30px; /* More space below the heading */
}

.chart-and-image {
    display: flex;
    justify-content: center; /* Center the children */
    align-items: center; /* Align the children vertically */
    width: 100%; /* Take the full width */
}

.chart-container, .image-container {
    flex: 1; /* This makes both the containers take up equal space */
    max-width: 50%; /* Each container can take up to half of the parent's width */
    margin: 10px; /* Optional: adds some space between the chart and the image */
    padding: 20px; /* Consistent padding */
    background-color: #fff; /* White background */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Shadow for depth */
    display: flex; /* This will help with the internal alignment */
    justify-content: center; /* Center-align the children horizontally */
    align-items: center; /* Center-align the children vertically */
}

.img-fluid {
    max-width: 100%; /* Ensure the image does not exceed its container */
    height: auto; /* Maintain aspect ratio */
}

.image-hover img {
    max-width: 100%; /* Ensure the image does not exceed its container's width */
    height: 70%; /* Maintain the aspect ratio of the image */
    transition: transform 0.5s ease; 
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}


.content {
    display: flex; /* Use flex to center children */
    flex-direction: column; /* Stack children vertically */
    align-items: center; /* Center children horizontally */
    margin: 20px auto; /* Center the .content itself and maintain margin */
    background-color: #ffffff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 80%; /* Or another fixed width, or max-width */
}


.image-container {
    width: 40%; /* Adjust based on your needs */
    margin: 0 20px; /* Add some space between the image and the chart */
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
            <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i> My Quiz Marks</h1>
        </div>
    </div>
</div>

    <div class="content">
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Quiz Name</th>
                        <th>Marks Obtained</th>
                    </tr>
                </thead>
                <tbody id="quizMarksBody">
                    <!-- Quiz marks will be inserted here using JavaScript -->
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center align-items-center chart-and-image">
            <div class="chart-container mr-3"> <!-- Added margin to the right -->
                <canvas id="quizMarksChart"></canvas>
            </div>
            <div class="image-container image-hover"> <!-- Added a class for hover effect -->
                <img src="../images/IMG_1962.JPG" alt="Your Image Description" class="img-fluid">
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

        // Sample data for demonstration
        var quizes = [
            { course_name: 'Math', quiz_name: 'Math Q1', marks_obtained: 75 },
            { course_name: ' Accounting', quiz_name: ' Accounting Q2', marks_obtained: 85 },
            { course_name: 'Data Science', quiz_name: 'Quiz 2', marks_obtained: 65 },
            { course_name: 'Data Science', quiz_name: 'Quiz 1', marks_obtained: 40 },
            { course_name: 'Data Science', quiz_name: 'Quiz 1', marks_obtained: 40 },


            // Add more quiz data here
        ];

        // Inserting quiz marks into the table
        var quizMarksBody = document.getElementById('quizMarksBody');
        quizes.forEach(quiz => {
            var row = '<tr>' +
                      '<td>' + quiz.course_name + '</td>' +
                      '<td>' + quiz.quiz_name + '</td>' +
                      '<td>' + quiz.marks_obtained + '</td>' +
                      '</tr>';
            quizMarksBody.innerHTML += row;
        });

        // Setting up the chart
        var ctx = document.getElementById('quizMarksChart').getContext('2d');
        var quizMarksChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: quizes.map(quiz => quiz.quiz_name),
                datasets: [{
                    label: 'Marks Obtained',
                    data: quizes.map(quiz => quiz.marks_obtained),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Quiz Marks Distribution'
                    }
                }
            }
        });
    </script>
</body>
</html>