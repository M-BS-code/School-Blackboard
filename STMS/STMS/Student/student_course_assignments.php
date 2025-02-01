<?php
session_start();
include('../includes/config.php');

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to student login page if not logged in
    header("Location: StudentLoginPage.php");
    exit;
}

// Check if course ID is provided in the URL
if (!isset($_GET['course_id'])) {
    // Redirect to a suitable page or display an error message
    exit("Course ID is missing.");
}

$course_id = $_GET['course_id'];

// Fetch course details
$sql_course = "SELECT course_name FROM courses WHERE course_id = $course_id";
$result_course = $conn->query($sql_course);

if ($result_course->num_rows > 0) {
    $course = $result_course->fetch_assoc();
} else {
    exit("Course not found.");
}

// Fetch assignments for the selected course
$sql_assignments = "SELECT assignment_id, assignment_name, due_date, description, link 
                    FROM assignments 
                    WHERE course_id = $course_id";
$result_assignments = $conn->query($sql_assignments);
$assignments = array();

if ($result_assignments->num_rows > 0) {
    while ($row = $result_assignments->fetch_assoc()) {
        $assignments[] = $row;
    }
}

// Process assignment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_link'])) {
    $assignment_id = $_POST['assignment_id'];
    $student_id = $_SESSION['student_id'];
    $link = $_POST['submission_link'];

    // Insert the submitted link into the submitted_assignments table
    $sql_submission = "INSERT INTO submitted_assignments (assignment_id, student_id, submission_link, submission_date) 
                       VALUES ($assignment_id, $student_id, '$link',  CURDATE())";
    
    if ($conn->query($sql_submission) === TRUE) {
        echo "Assignment submitted successfully.";
    } else {
        echo "Error: " . $sql_submission . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course['course_name']; ?> Assignments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
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

.container {
    min-height: 100vh; /* Make container full height */
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
}

.content-box {
    max-width: 400px; /* Adjust based on your preference */
    width: 100%; /* Makes it responsive */
    margin: auto; /* Additional horizontal centering for smaller elements */
    /* Add other styles like padding, border here */
}

.todo-container {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 0 auto; /* Adjust if necessary */
    width: 100%; /* Make sure the container takes the full width */
    overflow: hidden; /* Ensures nothing spills out */
}

.todo-item {
    word-wrap: break-word; /* Breaks the text to prevent overflow */
    padding: 10px;
    margin: 5px 0;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    justify-content: space-between; /* Space between text and buttons */
    width: 100%; /* Ensure the item takes full width of its container */
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 10px 0; /* Adds space between items */
    
   
}


.todo-item span {
    flex-grow: 1; /* Ensures text spans the majority of the item's width */
}

.todo-item button {
    margin-left: 10px; /* Ensures space between buttons */
}


.img-fluid {
    max-width: 60%;
    height: 50%;
    display: block; 
    margin: 0 auto; /* Center the image if it's smaller than its container */
    max-height: 200px; /* Match the initial height of the to-do list, adjust as needed */
    object-fit: cover;
   
}

.input-todo {
    width: 100%;
    min-height: 34px; /* Minimum height to match typical form input */
    box-sizing: border-box; /* Ensure padding doesn't affect width */
}

#todoColor {
    width: 20%; /* Or any other size */
    display: block; /* To apply width correctly */
    margin-top: 5px; /* Adjust based on layout */
    max-height: 300px; /* Adjust based on your need */
    overflow-y: auto;
}

.todo-item button {
    margin-left: 10px; /* Space from the text */
    vertical-align: middle; /* Align with the text */
    cursor: pointer; /* Change cursor on hover */
    padding: 0 5px; /* Smaller padding for a more compact look */
    line-height: 1.5; /* Adjust line height to match the text */
    border-radius: 5px; /* Rounded corners */
    border: none; /* Remove default border */
    background-color: #ff6347; /* Tomato color, change as you like */
    color: white; /* White text color */
}

h2.mt-5 {
    /* Add your specific styles if needed */
    margin-bottom: 20px; /* Additional space below the title */
}

.my-custom-card {
    max-width: 400px; /* Or whatever width you desire */
    margin: auto; /* Center it horizontally */
    padding: 20px; /* Adjust padding as needed */
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


.my-custom-card {
    min-width: 240px; /* Adjust this value based on your design needs */
    /* Other styles */
}

.row.flex-nowrap {
    overflow-x: auto;
}


</style>
<body>
<div class="container-fluid">
    <div class="row">  
    
        <!-- Dashboard Header -->
        <div class="col-md-12 dashboard-header">
            <?php include('StudentNavbar.php'); ?>
            <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i> Assignments for <?php echo $course['course_name']; ?></h1>
        </div>
    </div>
</div>
   
    </div>
    <div class="row">
    <div class="col-12 text-center">
        <h2 class="mt-4">Assignments</h2> <!-- Title for the Assignments -->
    </div>
</div>
        <?php if (!empty($assignments)) { ?>
            <div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="margin-top:30px ;   margin-bottom:30px ;">
    <div class="row">
        <div class="d-flex flex-nowrap overflow-auto justify-content-center"> <!-- Use justify-content-center to center the cards -->
            <?php foreach ($assignments as $assignment) { ?>
                <div class="col-auto mb-4">
                    <div class="card" style="min-width: 18rem; margin-right: 20px;"> <!-- Add right margin to each card -->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $assignment['assignment_name']; ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Due Date:</strong> <?php echo $assignment['due_date']; ?></p>
                                <p><strong>Description:</strong> <?php echo $assignment['description']; ?></p>
                                <p><a href="<?php echo $assignment['link']; ?>" target="_blank">View Assignment</a></p>
                                <!-- Assignment Submission Form -->
                                <form method="post" action="student_course_assignments.php?course_id=<?php echo $_GET['course_id']; ?>">
                                    <input type="hidden" name="assignment_id" value="<?php echo $assignment['assignment_id']; ?>">
                                    <div class="form-group">
                                        <label for="submission_link">Submit Assignment Link:</label>
                                        <input type="text" class="form-control" name="submission_link" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="submit_link">Submit Link</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p>No assignments available for this course.</p>
        <?php } ?>
        
    </div>
</div>

  <!-- After the assignments section -->
<div class="row">
    <div class="col-12 text-center"> <!-- This centers the title -->
        <h2 class="mt-5">Your To-Do List</h2>
    </div>
</div>
<div class="row justify-content-center"> <!-- This centers the to-do list container -->
    <div class="col-lg-8"> <!-- Adjust the column size as needed -->
        <div class="todo-container">
            <!-- To-Do List content -->
            <div class="form-group">
                <input type="text" id="newTodo" class="form-control" placeholder="Add a new task">
                <button onclick="addTodo()" class="btn btn-success mt-2">Add Task</button>
            </div>
            <div class="form-group">
                <label for="todoColor">Choose To-Do List Color:</label>
                <input type="color" id="todoColor" class="form-control" style="width: 100%; max-width: 150px;">
            </div>
            <ul id="todoList" class="list-unstyled"></ul>
        </div>
    </div>
</div>

        
    

    

<script>
function addTodo() {
    var newTodo = document.getElementById('newTodo').value;
    var color = document.getElementById('todoColor').value; // Get the selected color
    if (newTodo.trim() !== '') {
        var li = document.createElement('li');
        li.classList.add('todo-item'); // Add class for consistent styling

        var textSpan = document.createElement('span');
        textSpan.textContent = newTodo;
        textSpan.style.backgroundColor = color; // Set the background color for the new item
        textSpan.classList.add('todo-text'); // Add class for text styling

        li.appendChild(textSpan);

        var editBtn = document.createElement('button');
        editBtn.textContent = 'Edit';
        editBtn.className = 'btn btn-info btn-sm ml-2'; // Bootstrap classes for styling
        editBtn.onclick = function() { editTodo(textSpan); };

        var deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.className = 'btn btn-danger btn-sm ml-2'; // Bootstrap classes for styling
        deleteBtn.onclick = function() { li.remove(); saveTodos(); };

        li.appendChild(editBtn);
        li.appendChild(deleteBtn);
        document.getElementById('todoList').appendChild(li);
        document.getElementById('newTodo').value = ''; // Clear the input field
        saveTodos(); // Save the todos after adding new one
    }
}


function editTodo(textSpan) {
    var newText = prompt("Edit your item:", textSpan.textContent);
    if (newText !== null && newText.trim() !== '') {
        textSpan.textContent = newText;
        saveTodos(); // Update local storage with new todo list
    }
}

function saveTodos() {
    var todos = [];
    document.querySelectorAll('#todoList li').forEach(function(item) {
        // Ensure to save the edited text
        todos.push({ text: item.firstChild.textContent, color: item.firstChild.style.backgroundColor });
    });
    localStorage.setItem('todos', JSON.stringify(todos));
}


// Updated loadTodos function
function loadTodos() {
    var todos = JSON.parse(localStorage.getItem('todos'));
    if (todos) {
        todos.forEach(function(todo) {
            var li = document.createElement('li');
            li.classList.add('todo-item'); // Add class for styling
            var textSpan = document.createElement('span');
            textSpan.textContent = todo.text;
            textSpan.style.backgroundColor = todo.color;
            li.appendChild(textSpan);

            var editBtn = document.createElement('button');
            editBtn.textContent = 'Edit';
            editBtn.className = 'btn btn-info btn-sm ml-2';
            editBtn.onclick = function() { editTodo(textSpan); };

            var deleteBtn = document.createElement('button');
            deleteBtn.textContent = 'Delete';
            deleteBtn.className = 'btn btn-danger btn-sm ml-2';
            deleteBtn.onclick = function() { li.remove(); saveTodos(); };

            li.appendChild(editBtn);
            li.appendChild(deleteBtn);
            document.getElementById('todoList').appendChild(li);
        });
    }
}

// Call loadTodos when the page loads
document.addEventListener('DOMContentLoaded', loadTodos);

var deleteBtn = document.createElement('button');
deleteBtn.textContent = 'Delete';
deleteBtn.className = 'btn btn-danger btn-sm'; // Add or remove Bootstrap classes as needed
deleteBtn.onclick = function() { li.remove(); saveTodos(); }; // Make sure this deletion logic is present
li.appendChild(deleteBtn);


</script>

</body>
</html>