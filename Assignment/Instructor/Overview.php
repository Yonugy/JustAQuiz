<?php
include("../../main.php");
include('../session.php');
 
 
// Handle the feedback submission via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  echo "<script>console.log('hi');</script>";
  if (isset($_POST['result_id']) && isset($_POST['feedback'])) {
      $result_id = intval($_POST['result_id']);
      echo "<script>console.log('$result_id');</script>";
      $feedback = $_POST['feedback'];
 
      // Call the function to update the feedback
      if (write_feedback($result_id, $feedback, $conn)) {
          echo "Feedback updated successfully";
      } else {
          echo "Failed to update feedback";
      }
  }
}
 
function display_attempt($conn) {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT a.attempt_id, a.student_id, u.name, q.title, q.description, r.time_remaining, r.feedback
      FROM Attempt a
      INNER JOIN Users u ON a.student_id = u.user_id
      INNER JOIN Quiz q ON a.quiz_id = q.quiz_id
      INNER JOIN Result r ON a.attempt_id = r.attempt_id
      WHERE creator_id = $user_id";
  $result = $conn->query($sql);
 
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $student_id = htmlspecialchars($row['student_id']);
          $student_name = htmlspecialchars($row['name']);
          $title = htmlspecialchars($row['title']) . " - " . htmlspecialchars($row['description']);
          $time_spent = calculate_used_time($row['attempt_id'], $conn)*60 . "s";
          $feedback = htmlspecialchars($row['feedback']);
 
          // Generate a table row
          echo "<tr data-result-id='{$row['attempt_id']}'>
                  <td>{$student_id}</td>
                  <td>{$student_name}</td>
                  <td>{$title}</td>
                  <td>{$time_spent}</td>
                  <td>{$feedback}</td>
                  <td class='edit'></td>
                </tr>";
      }
  } else {
      echo "<tr><td colspan='6'>No quiz attempts found. Your quiz hasn't got any student attempts yet :(</td></tr>";
  }
}
 
function view_available_quiz($conn) {
  $user_id=$_SESSION['user_id'];
  $sql = "SELECT * FROM quiz WHERE creator_id = $user_id";
  $result = $conn->query($sql);
 
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          // Sanitize output to prevent XSS attacks
          $quizid = htmlspecialchars($row['quiz_id']);
          $title = htmlspecialchars($row['title']);
          $description = htmlspecialchars($row['description']);
          $time_limit = htmlspecialchars($row['time_limit']);
          $total_questions = total_question($row['quiz_id'], $conn);
 
          // Echo the HTML for each row
          echo "<tr>
                  <td>{$quizid}</td>
                  <td>{$title}</td>
                  <td>{$description}</td>
                  <td>{$total_questions}</td>
                  <td>{$time_limit}</td>
                  <td><a href='InstructorEditQuiz.php?id={$quizid}'>Edit</a></td>
                </tr>";
      }
  } else {
      // No quizzes available
      echo "<tr><td colspan='5'>No quiz available at the moment.</td></tr>";
  }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="Overview.css">
    <script>
        function tab(t) {
            if (t == 1) {
                document.getElementById('content1').style.display = 'block';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('tab1').style.background = '#D5F3FE';
                document.getElementById('tab2').style.background = 'white';
            }
            else if (t == 2) {
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'block';
                document.getElementById('tab1').style.background = '#D5F3FE';
                document.getElementById('tab2').style.background = 'white';
            };
        }

        function getQueryParam(param) {
            urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Automatically switch tabs based on the query parameter
        document.addEventListener('DOMContentLoaded', function () {
            const tabParam = getQueryParam('tab');
            if (tabParam) {
                tab(parseInt(tabParam, 10)); // Call the `tab` function with the tab number
            }
        });
    </script>
</head>
<body>
    <header>
    <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
    </header>
 
    <nav class="navbar">
        <a href="InstructorHome.php">HOME</a>
        <a href="InstructorCreateQuiz.php">CREATE QUIZ</a>
        <a href="Overview.php">OVERVIEW</a>
        <a href="../User/Login.php">LOGOUT</a>
    </nav>
 
    <main>
    <div id="main">
    <div class="flex-container-top">
        <div class="tab" id="tab1" onclick="tab(1)">Quizzes</div>
        <div class="tab" id="tab2" onclick="tab(2)">Quizzes Attempts</div>
        </div>
        <div class="flex-container-bottom">
        <div class="content" id="content1" style="overflow-x: auto;">
            <table class="table">
                <thead>
                  <tr>
                  <th scope="col">Quiz ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Total Questions</th>
                    <th scope="col">Time Limit</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  // Ensure you have a valid database connection in $conn
                  view_available_quiz($conn);
                  ?>
                </tbody>
              </table>  
            </div>
        </div>
        <div class="content" id="content2" style="overflow-x: auto;">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Title-Description</th>
                    <th scope="col">Time Spent</th>
                    <th scope="col">Give Feedback</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php
                    display_attempt($conn);
                    ?>
                  </tr>
                </tbody>
              </table>  
        </div>
        </div>
    </main>
    <ul class="bg-bubbles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
    <div class="popup-edit">
        <div class="popup-content">
            <img id="close-button" src="../images/close.png" alt="close-button">
            <h1>Feedback</h1>
            <input class="pop-up-input" type="text"></input>
            <button class="pop-up-submit">Submit</button>
        </div>
    </div>
    <script src="Overview.js"></script>
</body>
</html>