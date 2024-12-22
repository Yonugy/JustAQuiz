<?php
include("../../main.php");
include('../session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="UserManagement.css">
    <script>
        function tab(t) {
            if (t == 1) {
                document.getElementById('content1').style.display = 'block';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('tab1').style.background = '#D5F3FE';
                document.getElementById('tab2').style.background = '#66D3FA';
                document.getElementById('tab3').style.background = '#3CAEA3';
            }
            else if (t == 2) {
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'block';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('tab1').style.background = '#D5F3FE';
                document.getElementById('tab2').style.background = '#66D3FA';
                document.getElementById('tab3').style.background = '#3CAEA3';
            }
            else if (t == 3) {
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'block';
                document.getElementById('tab1').style.background = '#D5F3FE';
                document.getElementById('tab2').style.background = '#66D3FA';
                document.getElementById('tab3').style.background = '#3CAEA3';
            };
        }

        function getQueryParam(param) {
            urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Automatically switch tabs based on the query parameter
        document.addEventListener('DOMContentLoaded', function () {
            tabParam = getQueryParam('tab');
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
        <a href="AdminHome.php">HOME</a>
        <a href="AdminCreateQuiz.php">CREATE QUIZ</a>
        <a href="UserManagement.php">MANAGEMENT</a>
        <a href="Badges.php">BADGES</a>
        <a href="../User/Login.php">LOGOUT</a>
    </nav>

    <main>
    <div id="main">
        <div class="flex-container-top">
          <div class="tab" id="tab1" onclick="tab(1)">Quizzes</div>
          <div class="tab" id="tab2" onclick="tab(2)">Instructor</div>
          <div class="tab" id="tab3" onclick="tab(3)">Students</div>
        </div>
        <div class="flex-container-bottom">
          <div class="content" id="content1" style="overflow-x: auto;">
              <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">QuizID</th>
                      <th scope="col">Subject</th>
                      <th scope="col">Title-Description</th>
                      <th scope="col">Total Questions</th>
                      <th scope="col">Date Created</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                      $sql = "SELECT * FROM Quiz";
                      $result = $conn->query($sql);
                  
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $quizid=$row['quiz_id'];
                            $subject = htmlspecialchars($row['subject']);
                            $title_description = htmlspecialchars($row['title']) . " - " . htmlspecialchars($row['description']);
                            $total_questions = total_question($row['quiz_id'], $conn);
                            $datetime = htmlspecialchars($row['date_created']);
                            $date = explode(" ", $datetime)[0];
                            echo '<tr>';
                            echo "<th scope='row' class='row'>$quizid</th>";
                            echo "<td>$subject</td>";
                            echo "<td>$title_description</td>";
                            echo "<td>$total_questions</td>";
                            echo "<td>$date</td>";
                            echo "<td><a class='edit' href='AdminEditQuiz.php?id={$quizid}'>Edit</a></td>";
                            echo "<td data-id='$quizid' class='Qbin'></td>";
                            echo '</tr>';
                          }
                      } else {
                          echo "No quiz available at the moment.";
                      }
                    ?>

                  </tbody>
                </table>
            </div>
          <div class="content" id="content2" style="overflow-x: auto;">
              <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Instructor ID</th>
                      <th scope="col">Instructor Name</th>
                      <th scope="col">Total Quizzes Created</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                      $sql = "SELECT user_id, name FROM Users WHERE role_id = 2";
                      $result = $conn->query($sql);
                  
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              $instructor_id = $row['user_id'];
                              $instructor_name = $row['name'];
                  
                              $sql_quiz_count = "SELECT COUNT(*) AS total FROM Quiz WHERE creator_id = ?";
                              $stmt = $conn->prepare($sql_quiz_count);
                              $stmt->bind_param("i", $instructor_id);
                              $stmt->execute();
                              $result_quiz_count = $stmt->get_result();
                              $total_quiz_create = 0;
                              if ($result_quiz_count->num_rows > 0) {
                                  $row_quiz_count = $result_quiz_count->fetch_assoc();
                                  $total_quiz_create = $row_quiz_count['total'];
                              }
                              echo '<tr>';
                              echo "<th scope='row' class='row'>$instructor_id</th>";
                              echo "<td>$instructor_name</td>";
                              echo "<td>$total_quiz_create</td>";
                              echo "<td data-id='$instructor_id' class='bin'></td>";
                              echo '</tr>';
                          }
                      } else {
                          echo "No instructors found.";
                      }
                    ?>

                  </tbody>
                </table>  
              </div>
          <div class="content" id="content3" style="overflow-x: auto;">
              <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Student ID</th>
                      <th scope="col">Student Name</th>
                      <th scope="col">Badges Collected</th>
                      <th scope="col">Quizzes Completed</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                      $sql = "SELECT user_id, name FROM Users WHERE role_id = 3";
                      $result = $conn->query($sql);
                  
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              $student_id = $row['user_id'];
                              $student_name = $row['name'];
                              $badges_collected = calculate_total_badges_collected($student_id, $conn);
                              $quiz_completed = total_quiz_done($student_id, $conn);
                              echo '<tr>';
                              echo "<th scope='row' class='row'>$student_id</th>";
                              echo "<td>$student_name</td>";
                              echo "<td>$badges_collected</td>";
                              echo "<td>$quiz_completed</td>";
                              echo "<td data-id='$student_id' class='bin'></td>";
                              echo '</tr>';
                          }
                      } else {
                          echo "No student found.";
                      }
                    ?>

                  </tbody>
                </table>  
          </div>
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

    <script>
    document.querySelectorAll('.Qbin').forEach(td => {
        td.onclick = function () {
            quizId = this.getAttribute('data-id');
            fetch('delete_quiz.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ quizId: quizId })
            })
            .then(response => response.json())
            .then(data => {
                // Simple feedback
                alert(data.message);
                location.reload(); // Reload page to reflect changes
            });
        };
    });
    document.querySelectorAll('.bin').forEach(td => {
        td.onclick = function () {
          userId = this.getAttribute('data-id');
            fetch('delete_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ userId: userId })
            })
            .then(response => response.json())
            .then(data => {
                // Simple feedback
                alert(data.message);
                location.reload(); // Reload page to reflect changes
            });
        };
    });
    </script>
</body>
</html>