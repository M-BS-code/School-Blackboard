<?php
session_start();
include('../includes/config.php');

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to student login page if not logged in
    header("Location: StudentLoginPage.php");
    exit;
}

// Fetch student's submitted assignments with grades
$student_id = $_SESSION['student_id'];
$sql_assignments = "SELECT assignments.assignment_name, assignments.due_date, assignments.link, grades.grade
                    FROM assignments
                    LEFT JOIN grades ON assignments.assignment_id = grades.assignment_id
                    WHERE grades.user_id = $student_id";
$result_assignments = $conn->query($sql_assignments);

// Check for errors
if (!$result_assignments) {
    echo "Error: " . $conn->error; // Output error message
    exit; // Stop script execution
}

$assignments = array();
if ($result_assignments->num_rows > 0) {
    while ($row_assignment = $result_assignments->fetch_assoc()) {
        $assignments[] = $row_assignment;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assignment Marks</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
        :root {
        --white-color: #fff;
        --paraText-color: #777;
        --heading-color: #333;
        --primary-color: rgb(31, 153, 167);
        --secondary-color: rgb(94, 7, 40);
    }
    /* Center-align content */
    .content {
        text-align: center;
    }

    
    .table-responsive {
    max-width: 80%;
    margin: 0 auto 0 auto; 
    margin-top: 50px; 
    font-size: 16px; 
    }


    .chart-container {
        width: 100%;
        max-width: 800px; /* Adjust maximum width as needed */
        margin: 0 auto;
    }

    /* Adjust scatter plot x-axis label */
    .chartjs-chart-tooltip {
        max-width: none; /* Allow tooltip to expand */
        white-space: nowrap; /* Prevent wrapping */
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
                <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i> My Assignment Marks</h1>
            </div>
        </div>
    </div>
    
        <!-- Content -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Assignment Name</th>
                                    <th>Due Date</th>
                                    <th>Link</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $assignment) { ?>
                                    <tr>
                                        <td><?php echo $assignment['assignment_name']; ?></td>
                                        <td><?php echo $assignment['due_date']; ?></td>
                                        <td><a href="<?php echo $assignment['link']; ?>" target="_blank">View Assignment</a></td>
                                        <td><?php echo isset($assignment['grade']) ? $assignment['grade'] : "Not Graded"; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="content">
                        <h2 class="text-center mb-4">My Assignment Marks Scatter Plot</h2>
                        <div class="chart-container">
                            <canvas id="scatterPlot"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Prepare data for scatter plot
            var assignmentsData = <?php echo json_encode($assignments); ?>;
        
            // Extracting unique assignment names for the x-axis categories
            var assignmentNames = assignmentsData.map(function(assignment) {
                return assignment.assignment_name;
            });
        
            // Create unique labels from assignment names for x-axis
            var uniqueAssignmentNames = [''].concat([...new Set(assignmentNames)]); // Add empty string to start labels from 1
        
            var scatterData = assignmentsData.map(function(assignment) {
                return {
                    x: uniqueAssignmentNames.indexOf(assignment.assignment_name), // Now it correctly starts from 1
                    y: assignment.grade ? parseFloat(assignment.grade) : null // Use grade as y value
                };
            });
        
            // Create scatter plot
            var ctx = document.getElementById('scatterPlot').getContext('2d');
            var scatterPlot = new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Assignment Grades',
                        data: scatterData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Changed to pinkish-red with some transparency
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'category', // Use 'category' for discrete values
                            labels: uniqueAssignmentNames.slice(1), // Use labels starting from actual first assignment name
                            title: {
                                display: true,
                                text: 'Assignment Name'
                            },
                            ticks: {
                                autoSkip: false, // Ensure no labels are skipped
                                callback: function(value, index, values) {
                                    return value || ''; // Ensure the first label (which is empty) does not display
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Grade'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = 'Grade: ' + context.parsed.y;
                                    label += ', Assignment: ' + uniqueAssignmentNames[context.parsed.x];
                                    return label;
                                }
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 0 // Ensure no line is drawn connecting the dots
                        }
                    }
                }
            });
        </script>
        
        
        
    
    </body>
    </html>